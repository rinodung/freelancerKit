<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypalipn extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
	}
	function index()
	{		
			$this->load->helper('notification');
			$this->theme_view = 'blank';
			$settings = Setting::first();
			log_message('error', "Paypal IPN called");

			$req = 'cmd=_notify-validate';
			foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
			}

			$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Host: www.paypal.com\r\n";  // www.sandbox.paypal.com for a test site
			$header .= "Content-Length: " . strlen($req) . "\r\n";
			$header .= "Connection: close\r\n\r\n";

			//$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);


			if (!$fp) {
			// HTTP ERROR Failed to connect
			 $mail_From = "From: IPN@paypal-tester.com";
			 $mail_To = $settings->email;
			 $mail_Subject = "HTTP ERROR";
			 $mail_Body = $errstr;
			 log_message('error', 'Paypal IPN - HTTP ERROR: '.$errstr);

			}
			else
			{
			  fputs($fp, $header . $req);
			  while (!feof($fp)) {
			    $res = fgets ($fp, 1024);
			    //log_message('error', 'Paypal IPN - fp handler -'.$res);
			    if (stripos($res, "VERIFIED") !== false) {
			      log_message('error', 'Paypal IPN - VERIFIED');

			      $item_name = $_POST['item_name'];
			      $item_number = $_POST['item_number'];

			      log_message('error', 'Paypal IPN - Invoice number: #'.$item_number);

			      $custom = explode('-', $_POST['custom']);  
				  $payment_currency = $_POST['mc_currency'];
			      $receiver_email = $_POST['receiver_email'];
			      $payer_email = $_POST['payer_email'];
			      
		if($custom[0] == "invoice"){
			$txn_id = $_POST['txn_id']; 
			$payment_amount = $_POST['mc_gross'];
			$payment_status = $_POST['payment_status'];

			      if (($payment_status == 'Completed' || $payment_status == 'Processed' || $payment_status == 'Sent' || $payment_status == 'Pending') &&   //payment_status = Completed
			         ($receiver_email == $settings->paypal_account) &&   // receiver_email is same as your account email
			         ($payment_amount == $custom[1]) &&  //check they payed what they should have
			         ($payment_currency == $settings->paypal_currency)) {  

			    	
				    	$invoice = Invoice::find_by_reference($item_number);
						$invoice->paid_date = date("Y-m-d", time());
						$invoice->status = "Paid";
						$invoice->save();
						log_message('error', 'Paypal IPN - Success: Invoice #'.$item_number.' payment processed via Paypal.');
						send_notification($settings->email, $this->lang->line('application_notification_payment_processed_subject'), $this->lang->line('application_notification_payment_processed').' #'.$item_number);
					
			      }
			      else
			      {


			          $mail_To =  $settings->email;
			          $mail_Subject = "PayPal IPN status not completed or security check fail";

			          $mail_Body = "Something wrong. \n\nThe transaction ID number is: $txn_id \n\n Payment status = $payment_status \n\n Payment amount = $payment_amount";
			          mail($mail_To, $mail_Subject, $mail_Body);
			          log_message('error', 'Paypal IPN - Error: Invoice #'.$item_number.'. PayPal IPN status not completed or security check fail');
			      }

			}elseif($custom[0] == "subscription"){
				$txn_type = $_POST["txn_type"];
				log_message('error', 'Paypal IPN - '.$_POST["subscr_id"]);
				if (($txn_type == "subscr_signup") &&  
			         ($receiver_email == $settings->paypal_account) && 
			         ($_POST['mc_amount3'] == $custom[1]) && 
			         ($payment_currency == $settings->paypal_currency)) {  

						$Subscription = Subscription::find_by_reference($item_number);
						$Subscription->subscribed = date("Y-m-d", time());
						$Subscription->save();
						log_message('error', 'Paypal IPN - Success: Subscription #'.$item_number.' payment processed via Paypal.');
						send_notification($settings->email, $this->lang->line('application_notification_subscribed_subject'), $this->lang->line('application_notification_subscribed').' #'.$item_number);
				}
			}


			    }
			    else if (stripos ($res, "INVALID") !== false) {
				if(!$_POST){echo "IPN cannot be called outside of a paypal reuqest!";}else{
			      log_message('error', 'Paypal IPN - Error: Invoice #'.$item_number.'. We have had an INVALID response. \n\nThe transaction ID number is: $txn_id \n\n username = $username');
				 
				}
			    }
			  } //end of while
			fclose ($fp);
			}

		
	}
	
	
}
