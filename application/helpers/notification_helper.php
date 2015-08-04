<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Notification Helper
 */
function send_notification( $email, $subject, $text ) {
	$instance =& get_instance();
  $instance->email->clear();
	$instance->load->helper('file');
	$instance->load->library('parser');
	$data["core_settings"] = Setting::first();
    $instance->email->from($data["core_settings"]->email, $data["core_settings"]->company);
			$instance->email->to($email); 
			$instance->email->subject($subject); 
  			//Set parse values
  			$parse_data = array(
            					'company' => $data["core_settings"]->company,
            					'link' => base_url(),
            					'logo' => '<img src="'.base_url().''.$data["core_settings"]->logo.'" alt="'.$data["core_settings"]->company.'"/>',
            					'invoice_logo' => '<img src="'.base_url().''.$data["core_settings"]->invoice_logo.'" alt="'.$data["core_settings"]->company.'"/>',
            					'message' => $text
            					);
  			$email_invoice = read_file('./application/views/'.$data["core_settings"]->template.'/templates/email_notification.html');
  			$message = $instance->parser->parse_string($email_invoice, $parse_data);
			$instance->email->message($message);
			$instance->email->send();

}

function send_ticket_notification( $email, $subject, $text, $ticket_id ) {
  $instance =& get_instance();
  $instance->email->clear();
  $instance->load->helper('file');
  $instance->load->library('parser');
  $data["core_settings"] = Setting::first();
  
  $ticket = Ticket::find_by_id($ticket_id);
  $ticket_link = base_url().'tickets/view/'.$ticket->id;
  
    $instance->email->from($data["core_settings"]->email, $data["core_settings"]->company);
    $instance->email->reply_to($data["core_settings"]->ticket_email); 
      $instance->email->to($email); 
      $instance->email->subject($subject); 
        //Set parse values
        $parse_data = array(
                      'company' => $data["core_settings"]->company,
                      'link' => base_url(),
                      'ticket_link' => $ticket_link,
                      'ticket_number' => $ticket->reference,
                      'ticket_created_date' => date($data["core_settings"]->date_format.'  '.$data["core_settings"]->date_time_format, $ticket->created),
                      'ticket_status' => $instance->lang->line('application_ticket_status_'.$ticket->status),
                      'logo' => '<img src="'.base_url().''.$data["core_settings"]->logo.'" alt="'.$data["core_settings"]->company.'"/>',
                      'invoice_logo' => '<img src="'.base_url().''.$data["core_settings"]->invoice_logo.'" alt="'.$data["core_settings"]->company.'"/>',
                      'message' => $text
                      );
        $email_invoice = read_file('./application/views/'.$data["core_settings"]->template.'/templates/email_ticket_notification.html');
        $message = $instance->parser->parse_string($email_invoice, $parse_data);
      $instance->email->message($message);
      $instance->email->send();

}
