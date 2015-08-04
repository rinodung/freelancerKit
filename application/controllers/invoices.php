<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){	
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "invoices"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_all') => 'invoices',
				 		$this->lang->line('application_open') => 'invoices/filter/open',
				 		$this->lang->line('application_Sent') => 'invoices/filter/sent',
				 		$this->lang->line('application_Paid') => 'invoices/filter/paid',
				 		);	
		
	}	
	function index()
	{
		$this->view_data['invoices'] = Invoice::all();
		$days_in_this_month = days_in_month(date('m'), date('Y'));
		$lastday_in_month =  strtotime(date('Y')."-".date('m')."-".$days_in_this_month);
		$firstday_in_month =  strtotime(date('Y')."-".date('m')."-01");

		$this->view_data['invoices_paid_this_month'] = Invoice::count(array('conditions' => 'UNIX_TIMESTAMP(`paid_date`) <= '.$lastday_in_month.' and UNIX_TIMESTAMP(`paid_date`) >= '.$firstday_in_month.''));
		$this->view_data['invoices_due_this_month'] = Invoice::count(array('conditions' => 'UNIX_TIMESTAMP(`due_date`) <= '.$lastday_in_month.' and UNIX_TIMESTAMP(`due_date`) >= '.$firstday_in_month.''));
		
		//statistic
		$now = time();
		$beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
		$end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
		$this->view_data['invoices_due_this_month_graph'] = Invoice::find_by_sql('select count(id) AS "amount", DATE_FORMAT(`due_date`, "%w") AS "date_day", DATE_FORMAT(`due_date`, "%Y-%m-%d") AS "date_formatted" from invoices where UNIX_TIMESTAMP(`due_date`) >= "'.$beginning_of_week.'" AND UNIX_TIMESTAMP(`due_date`) <= "'.$end_of_week.'" ');
		$this->view_data['invoices_paid_this_month_graph'] = Invoice::find_by_sql('select count(id) AS "amount", DATE_FORMAT(`paid_date`, "%w") AS "date_day", DATE_FORMAT(`paid_date`, "%Y-%m-%d") AS "date_formatted" from invoices where UNIX_TIMESTAMP(`paid_date`) >= "'.$beginning_of_week.'" AND UNIX_TIMESTAMP(`paid_date`) <= "'.$end_of_week.'" ');


		$this->content_view = 'invoices/all';
	}
	function filter($condition = FALSE)
	{
		$days_in_this_month = days_in_month(date('m'), date('Y'));
		$lastday_in_month =  date('Y')."-".date('m')."-".$days_in_this_month;
		$firstday_in_month =  date('Y')."-".date('m')."-01";
		$this->view_data['invoices_paid_this_month'] = Invoice::count(array('conditions' => 'paid_date <= '.$lastday_in_month.' and paid_date >= '.$firstday_in_month.''));
		$this->view_data['invoices_due_this_month'] = Invoice::count(array('conditions' => 'due_date <= '.$lastday_in_month.' and due_date >= '.$firstday_in_month.''));

		switch ($condition) {
			case 'open':
				$this->view_data['invoices'] = Invoice::find('all', array('conditions' => array('status = ?', 'Open')));
				break;
			case 'sent':
				$this->view_data['invoices'] = Invoice::find('all', array('conditions' => array('status = ?', 'Sent')));
				break;
			case 'paid':
				$this->view_data['invoices'] = Invoice::find('all', array('conditions' => array('status = ?', 'Paid')));
				break;
			default:
				$this->view_data['invoices'] = Invoice::all();
				break;
		}
		
		$this->content_view = 'invoices/all';
	}
	function create()
	{	
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$invoice = Invoice::create($_POST);
			$new_invoice_reference = $_POST['reference']+1;
			
			$invoice_reference = Setting::first();
			$invoice_reference->update_attributes(array('invoice_reference' => $new_invoice_reference));
       		if(!$invoice){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_invoice_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_invoice_success'));}
			redirect('invoices');
		}else
		{
			$this->view_data['invoices'] = Invoice::all();
			$this->view_data['next_reference'] = Invoice::last();
			$this->view_data['projects'] = Project::all();
			$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_create_invoice');
			$this->view_data['form_action'] = 'invoices/create';
			$this->content_view = 'invoices/_invoice';
		}	
	}	
	function update($id = FALSE, $getview = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			$view = FALSE;
			if(isset($_POST['view'])){$view = $_POST['view']; }
			unset($_POST['view']);
			if($_POST['status'] == "Paid"){ $_POST['paid_date'] = date('Y-m-d', time());}
			$invoice = Invoice::find($id);
			$invoice->update_attributes($_POST);
			
       		if(!$invoice){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_invoice_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_invoice_success'));}
			if($view == 'true'){redirect('invoices/view/'.$id);}else{redirect('invoices');}
			
		}else
		{
			$this->view_data['invoice'] = Invoice::find($id);
			$this->view_data['projects'] = Project::all();
			$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
			if($getview == "view"){$this->view_data['view'] = "true";}
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_invoice');
			$this->view_data['form_action'] = 'invoices/update';
			$this->content_view = 'invoices/_invoice';
		}	
	}	
	
	function view($id = FALSE)
	{

		
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'invoices',
				 		);	
		$this->view_data['invoice'] = Invoice::find($id);
		$this->view_data['items'] = InvoiceHasItem::find('all',array('conditions' => array('invoice_id=?',$id)));
		$this->content_view = 'invoices/view';
	}
	function stripepay($id = FALSE){
		$this->load->file(APPPATH.'helpers/stripe/lib/Stripe.php', true);

		$stripe_keys = array(
		  "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
		  "publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
		);


		if($_POST){
			unset($_POST['send']);
			$token = Stripe_Token::create(array(
			  "card" => array(
			    "number" => "4242424242424242",
			    "exp_month" => 11,
			    "exp_year" => 2015,
			    "cvc" => "314"
			  )
			));
			// Stores errors:
					$errors = array();
					$email = $this->user->email;
					// Need a payment token:
					if (isset($_POST['stripeToken'])) {
						
						$token = $_POST['stripeToken'];

						// Check for a duplicate submission, just in case:
						// Uses sessions, you could use a cookie instead.
						if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
							$errors['token'] = 'You have apparently resubmitted the form. Please do not do that.';
						} else { // New submission.
							$_SESSION['token'] = $token;
						}		
						
					} else {
						$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.'; die();
					}
					
					// Set the order amount somehow:
					$amount = 2000; //$_POST['amount']; // $20, in cents

					// Validate other form data!

					// If no errors, process the order:
					if (empty($errors)) {
						
						// create the charge on Stripe's servers - this will charge the user's card
						try {
							
							// set your secret key: remember to change this to your live secret key in production
							// see your keys here https://manage.stripe.com/account
							Stripe::setApiKey($stripe_keys['secret_key']);

							// Charge the order:
							$charge = Stripe_Charge::create(array(
								"amount" => $amount, // amount in cents, again
								"currency" => "usd",
								"card" => $token,
								"description" => $email
								)
							);

							// Check that it was paid:
							if ($charge->paid == true) {
								
								echo "leuft!"; die();
								// Store the order in the database.
								// Send the email.
								// Celebrate!
								
							} else { // Charge was not paid!	
								echo '<div class="alert alert-danger"><h4>Payment System Error!</h4>Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.</div>'; die();
							}
							
						} catch (Stripe_CardError $e) {
						    // Card was declined.
							$e_json = $e->getJsonBody();
							$err = $e_json['error'];
							$errors['stripe'] = $err['message'];
							echo $err; die();
						} catch (Stripe_ApiConnectionError $e) {
						    // Network problem, perhaps try again.
						    echo "Network problem, perhaps try again."; die();
						} catch (Stripe_InvalidRequestError $e) {
						    // You screwed up in your programming. Shouldn't happen!
						    echo $e; die();
						} catch (Stripe_ApiError $e) {
						    // Stripe's servers are down!
						    echo "Stripe's servers are down!"; die();
						} catch (Stripe_CardError $e) {
						    // Something else that's not the customer's fault.
						    echo "Something else that's not the customer's fault."; die();
						}

					} 

			/*$new_invoice_reference = $_POST['reference']+1;
			$invoice_reference = Setting::first();
			$invoice_reference->update_attributes(array('invoice_reference' => $new_invoice_reference));
       		*/



       		if(!$invoice){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_invoice_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_invoice_success'));}
			redirect('invoices');
		}else
		{
			$this->view_data['invoices'] = Invoice::find_by_id($id);
			$this->view_data['public_key'] = $stripe_keys['publishable_key'];
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_pay_via_credit_card');
			$this->view_data['form_action'] = 'invoices/stripepay';
			$this->content_view = 'invoices/_stripe';
		}

	}
	function delete($id = FALSE)
	{	
		$invoice = Invoice::find($id);
		$invoice->delete();
		$this->content_view = 'invoices/all';
		if(!$invoice){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_invoice_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_invoice_success'));}
			redirect('invoices');
	}
	function preview($id = FALSE){
     $this->load->helper(array('dompdf', 'file')); 
     $this->load->library('parser');
     $data["invoice"] = Invoice::find($id); 
     $data['items'] = InvoiceHasItem::find('all',array('conditions' => array('invoice_id=?',$id)));
     $data["core_settings"] = Setting::first();
   
     $due_date = date($data["core_settings"]->date_format, human_to_unix($data["invoice"]->due_date.' 00:00:00'));  
     $parse_data = array(
            					'due_date' => $due_date,
            					'invoice_id' => $data["invoice"]->reference,
            					'client_link' => $data["core_settings"]->domain,
            					'company' => $data["core_settings"]->company,
            					);
  	$html = $this->load->view($data["core_settings"]->template. '/' . 'invoices/preview', $data, true); 
  	$html = $this->parser->parse_string($html, $parse_data);
     
     $filename = $this->lang->line('application_invoice').'_'.$data["invoice"]->reference;
     pdf_create($html, $filename, TRUE);
	}
	function sendinvoice($id = FALSE){
			$this->load->helper(array('dompdf', 'file'));
			$this->load->library('parser');

			$data["invoice"] = Invoice::find($id); 
			$data['items'] = InvoiceHasItem::find('all',array('conditions' => array('invoice_id=?',$id)));
     		$data["core_settings"] = Setting::first();
    		$due_date = date($data["core_settings"]->date_format, human_to_unix($data["invoice"]->due_date.' 00:00:00')); 
  			//Set parse values
  			$parse_data = array(
            					'client_contact' => $data["invoice"]->company->client->firstname.' '.$data["invoice"]->company->client->lastname,
            					'due_date' => $due_date,
            					'invoice_id' => $data["invoice"]->reference,
            					'client_link' => $data["core_settings"]->domain,
            					'company' => $data["core_settings"]->company,
            					'logo' => '<img src="'.base_url().''.$data["core_settings"]->logo.'" alt="'.$data["core_settings"]->company.'"/>',
            					'invoice_logo' => '<img src="'.base_url().''.$data["core_settings"]->invoice_logo.'" alt="'.$data["core_settings"]->company.'"/>'
            					);
            // Generate PDF     
    		$html = $this->load->view($data["core_settings"]->template. '/' .'invoices/preview', $data, true);
    		$html = $this->parser->parse_string($html, $parse_data);
    		$filename = $this->lang->line('application_invoice').'_'.$data["invoice"]->reference;
     		pdf_create($html, $filename, FALSE);
     		//email
     		$subject = $this->parser->parse_string($data["core_settings"]->invoice_mail_subject, $parse_data);
			$this->email->from($data["core_settings"]->email, $data["core_settings"]->company);
			$this->email->to($data["invoice"]->company->client->email); 
			$this->email->subject($subject); 
  			$this->email->attach("files/temp/".$filename.".pdf");
  			


  			$email_invoice = read_file('./application/views/'.$data["core_settings"]->template.'/templates/email_invoice.html');
  			$message = $this->parser->parse_string($email_invoice, $parse_data);
			$this->email->message($message);			
			if($this->email->send()){$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_send_invoice_success'));
			$data["invoice"]->update_attributes(array('status' => 'Sent', 'sent_date' => date("Y-m-d")));
			log_message('error', 'Invoice #'.$data["invoice"]->reference.' has been send to '.$data["invoice"]->company->client->email);
			}
       		else{$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_send_invoice_error'));
       		log_message('error', 'ERROR: Invoice #'.$data["invoice"]->reference.' has not been send to '.$data["invoice"]->company->client->email.'. Please check your servers email settings.');
       		}
			unlink("files/temp/".$filename.".pdf");
			redirect('invoices/view/'.$id);
	}
	function item($id = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			if($_POST['name'] != ""){
				$_POST['name'] = $_POST['name'];
				$_POST['value'] = $_POST['value'];
				$_POST['type'] = $_POST['type'];
			}else{
				if($_POST['item_id'] == "-"){
					$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_add_item_error'));
					redirect('invoices/view/'.$_POST['invoice_id']);

				}else{
				$itemvalue = Item::find_by_id($_POST['item_id']);
				$_POST['name'] = $itemvalue->name;
				$_POST['type'] = $itemvalue->type;
				$_POST['value'] = $itemvalue->value;
				}
			}

			$item = InvoiceHasItem::create($_POST);
       		if(!$item){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_add_item_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_add_item_success'));}
			redirect('invoices/view/'.$_POST['invoice_id']);
			
		}else
		{
			$this->view_data['invoice'] = Invoice::find($id);
			$this->view_data['items'] = Item::find('all',array('conditions' => array('inactive=?','0')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_add_item');
			$this->view_data['form_action'] = 'invoices/item';
			$this->content_view = 'invoices/_item';
		}	
	}	
	function item_update($id = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$item = InvoiceHasItem::find($_POST['id']);
			$item = $item->update_attributes($_POST);
       		if(!$item){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_item_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_item_success'));}
			redirect('invoices/view/'.$_POST['invoice_id']);
			
		}else
		{
			$this->view_data['invoice_has_items'] = InvoiceHasItem::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_item');
			$this->view_data['form_action'] = 'invoices/item_update';
			$this->content_view = 'invoices/_item';
		}	
	}	
	function item_delete($id = FALSE, $invoice_id = FALSE)
	{	
		$item = InvoiceHasItem::find($id);
		$item->delete();
		$this->content_view = 'invoices/view';
		if(!$item){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_item_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_item_success'));}
			redirect('invoices/view/'.$invoice_id);
	}	
	
}