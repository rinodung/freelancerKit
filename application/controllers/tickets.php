<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		$link = '/'.$this->uri->uri_string();

		if($this->client){
			if($this->input->cookie('fc2_link') != ""){
				$link = str_replace("/tickets/", "/ctickets/", $link);
				redirect($link);
			}
			redirect('ctickets');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "tickets"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			
			$cookie = array(
                   'name'   => 'fc2_link',
                   'value'  => $link,
                   'expire' => '500',
               );

			$this->input->set_cookie($cookie);
			redirect('login');

		}
		$this->view_data['submenu'] = array(
				 		
				 		$this->lang->line('application_open') => 'tickets',
				 		$this->lang->line('application_my_tickets') => 'tickets/filter/assigned',
				 		$this->lang->line('application_closed') => 'tickets/filter/closed'
				 		);	
		$this->load->database();

		$this->view_data['tickets_assigned_to_me'] = Ticket::count(array('conditions' => 'user_id = '.$this->user->id.' and status != "closed"'));
		$this->view_data['tickets_in_my_queue'] = Ticket::count(array('conditions' => 'queue_id <= '.$this->user->queue.' and status != "closed"'));
		
		$now = time();
		$beginning_of_week = strtotime('last Monday', $now); // BEGINNING of the week
		$end_of_week = strtotime('next Sunday', $now) + 86400; // END of the last day of the week
		$this->view_data['tickets_opened_this_week'] = Ticket::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`created`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`created`), "%Y-%m-%d") AS "date_formatted" from tickets where created >= "'.$beginning_of_week.'" AND created <= "'.$end_of_week.'" ');
		//$this->view_data['tickets_closed_this_week'] = Ticket::find_by_sql('select count(id) AS "amount", DATE_FORMAT(FROM_UNIXTIME(`created`), "%w") AS "date_day", DATE_FORMAT(FROM_UNIXTIME(`created`), "%Y-%m-%d") AS "date_formatted" from tickets where created >= "'.$beginning_of_week.'" AND created <= "'.$end_of_week.'" ');

		
	}	
	function index()
	{
		$options = array('conditions' => 'status != "closed"');
		$this->view_data['ticket'] = Ticket::all($options);
		$this->content_view = 'tickets/all';
		$this->view_data['queues'] = Queue::find('all',array('conditions' => array('inactive=?','0')));
		
		
	}
	function queues($id)
	{
		
		$options = array('conditions' => 'status != "closed" AND queue_id = '.$id);
		$this->view_data['queues'] = Queue::find('all',array('conditions' => array('inactive=?','0')));
		
		$this->view_data['ticket'] = Ticket::all($options);
		$this->content_view = 'tickets/all';
	}
	function filter($condition)
	{
		$this->view_data['queues'] = Queue::find('all',array('conditions' => array('inactive=?','0')));
		switch ($condition) {
			case 'open':
				$options = array('conditions' => 'status = "open"');
				break;
			case 'closed':
				$options = array('conditions' => 'status = "closed"');
				break;
			case 'assigned':
				$options = array('conditions' => 'status != "closed" AND user_id = '.$this->user->id);
				break;
		}
		
		$this->view_data['ticket'] = Ticket::all($options);
		$this->content_view = 'tickets/all';
	}
	function create()
	{	
		if($_POST){
			$config['upload_path'] = './files/media/';
			$config['encrypt_name'] = TRUE;
			$config['allowed_types'] = '*';

			$this->load->library('upload', $config);
			$this->load->helper('notification');

			unset($_POST['userfile']);
			unset($_POST['file-name']);

			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);

			$client = Client::find_by_id($_POST['client_id']);
			$user = User::find_by_id($_POST['user_id']);
			if(isset($client->email)){ $_POST['from'] = $client->firstname.' '.$client->lastname.' - '.$client->email; } else {$_POST['from'] = $this->user->firstname.' '.$this->user->lastname.' - '.$this->user->email;}

			if(isset($_POST['notify_agent'])){
				$notify_agent = "true";
				}
				if(isset($_POST['notify_client'])){
				$notify_client = "true";
				}
			unset($_POST['notify_agent']);
			unset($_POST['notify_client']);
			if(isset($client->company->id)){
				$_POST['company_id'] = $client->company->id;
			}
			$_POST['created'] = time();
			$_POST['updated'] = "1";
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$ticket_reference = Setting::first();
			$_POST['reference'] = $ticket_reference->ticket_reference;
			$_POST['status'] = $ticket_reference->ticket_default_status;
			$ticket = Ticket::create($_POST);
			$new_ticket_reference = $_POST['reference']+1;			
			$ticket_reference->update_attributes(array('ticket_reference' => $new_ticket_reference));

			if ( ! $this->upload->do_upload())
						{
							$error = $this->upload->display_errors('', ' ');
							$this->session->set_flashdata('message', 'error:'.$error);

						}
						else
						{
							$data = array('upload_data' => $this->upload->data());

							$attributes = array('ticket_id' => $ticket->id, 'filename' => $data['upload_data']['orig_name'], 'savename' => $data['upload_data']['file_name']);
							$attachment = TicketHasAttachment::create($attributes);
						}


       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_ticket_error'));
       					redirect('tickets');
       					}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_ticket_success'));

       		if(isset($notify_agent)){
				send_ticket_notification($user->email, '[Ticket#'.$ticket->reference.'] - '.$_POST['subject'], $_POST['text'], $ticket->id);
				}
				if(isset($notify_client)){
				send_ticket_notification($client->email, '[Ticket#'.$ticket->reference.'] - '.$_POST['subject'], $_POST['text'], $ticket->id);
				}

       			redirect('tickets/view/'.$ticket->id);
       			}
			
		}else
		{
			$this->view_data['clients'] = Client::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['users'] = User::find('all',array('conditions' => array('status=?','active')));
			$this->view_data['queues'] = Queue::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['types'] = Type::find('all',array('conditions' => array('inactive=?','0')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_create_ticket');
			$this->view_data['form_action'] = 'tickets/create';
			$this->content_view = 'tickets/_ticket';
		}	
	}	
	function assign($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			$id = $_POST['id'];
			unset($_POST['id']);
			unset($_POST['files']);
			$user = User::find_by_id($_POST['user_id']);
			$assign = Ticket::find_by_id($id);
			$attr = array('user_id' => $_POST['user_id']);
			$assign->update_attributes($attr);

			if(isset($_POST['notify'])){
			send_ticket_notification($user->email, '[Ticket#'.$assign->reference.'] - '.$_POST['subject'], $_POST['message'], $id);
			}
			unset($_POST['notify']);
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$_POST['datetime'] = time();
			$_POST['from'] = $this->user->firstname." ".$this->user->lastname.' - '.$this->user->email;
			$_POST['reply_to'] = $this->user->email;
			$_POST['ticket_id'] = $id;
			$_POST['to'] = $_POST['user_id'];
			unset($_POST['user_id']);
			$article = TicketHasArticle::create($_POST);
       		if(!$assign){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_ticket_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_assign_ticket_success'));}
			redirect('tickets/view/'.$id);
		}else
		{
			$this->view_data['users'] = User::find('all',array('conditions' => array('status=?','active')));
			$this->view_data['ticket'] = Ticket::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_assign_to_agents');
			$this->view_data['form_action'] = 'tickets/assign';
			$this->content_view = 'tickets/_assign';
		}	
	}	
	function client($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			unset($_POST['id']);
			$client = Client::find_by_id($_POST['client_id']);
			$assign = Ticket::find_by_id($id);
			$attr = array('client_id' => $client->id, 'company_id' => $client->company->id);
			$assign->update_attributes($attr);

			if(isset($_POST['notify'])){
			send_ticket_notification($client->email, '[Ticket#'.$assign->reference.'] - '.$_POST['subject'], $_POST['message'], $assign->id);
			$_POST['internal'] = "0";
			}
			unset($_POST['notify']);
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$_POST['datetime'] = time();
			$_POST['from'] = $this->user->firstname." ".$this->user->lastname.' - '.$this->user->email;
			$_POST['reply_to'] = $this->user->email;
			$_POST['ticket_id'] = $id;
			$_POST['to'] = $_POST['client_id'];
			unset($_POST['client_id']);
			$article = TicketHasArticle::create($_POST);
       		if(!$assign){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_ticket_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_assign_ticket_success'));}
			redirect('tickets/view/'.$id);
		}else
		{
			$this->view_data['clients'] = Client::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['ticket'] = Ticket::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_client');
			$this->view_data['form_action'] = 'tickets/client';
			$this->content_view = 'tickets/_client';
		}	
	}	
	function queue($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			unset($_POST['id']);
			$ticket = Ticket::find_by_id($id);
			$attr = array('queue_id' => $_POST['queue_id']);
			$ticket->update_attributes($attr);

       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_assign_queue_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_assign_queue_success'));}
			redirect('tickets/view/'.$id);
		}else
		{
			$this->view_data['queues'] = Queue::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['ticket'] = Ticket::find_by_id($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_queue');
			$this->view_data['form_action'] = 'tickets/queue';
			$this->content_view = 'tickets/_queue';
		}	
	}	
	function type($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			unset($_POST['id']);
			$ticket = Ticket::find_by_id($id);
			$attr = array('type_id' => $_POST['type_id']);
			$ticket->update_attributes($attr);

       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_assign_type_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_assign_type_success'));}
			redirect('tickets/view/'.$id);
		}else
		{
			$this->view_data['types'] = Type::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['ticket'] = Ticket::find_by_id($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_type');
			$this->view_data['form_action'] = 'tickets/type';
			$this->content_view = 'tickets/_type';
		}	
	}	
	function status($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			unset($_POST['id']);
			$ticket = Ticket::find_by_id($id);
			$attr = array('status' => $_POST['status']);
			$ticket->update_attributes($attr);

       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_status_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_status_success'));}
			redirect('tickets/view/'.$id);
		}else
		{
			
			$this->view_data['ticket'] = Ticket::find_by_id($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_status');
			$this->view_data['form_action'] = 'tickets/status';
			$this->content_view = 'tickets/_status';
		}	
	}	
	function close($id = FALSE)
	{	
		$this->load->helper('notification');
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['ticket_id'];
			unset($_POST['ticket_id']);
			$ticket = Ticket::find_by_id($id);
			$attr = array('status' => "closed");
			$ticket->update_attributes($attr);
			if(isset($ticket->client->email)){ $email = $ticket->client->email; } else {$emailex = explode(' - ', $ticket->from); $email = $emailex[1]; }
			if(isset($_POST['notify'])){
				
			send_ticket_notification($email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $_POST['message'], $ticket->id);
			}
			send_ticket_notification($ticket->user->email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $_POST['message'], $ticket->id);
			$_POST['internal'] = "0";
			unset($_POST['notify']);
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$_POST['datetime'] = time();
			$_POST['from'] = $this->user->firstname." ".$this->user->lastname.' - '.$this->user->email;
			$_POST['reply_to'] = $this->user->email;
			$_POST['ticket_id'] = $id;
			$_POST['to'] = $email;
			unset($_POST['client_id']);
			$article = TicketHasArticle::create($_POST);
       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_ticket_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_ticket_close_success'));}
			redirect('tickets');
		}else
		{
			$this->view_data['ticket'] = Ticket::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_close');
			$this->view_data['form_action'] = 'tickets/close';
			$this->content_view = 'tickets/_close';
		}	
	}	
	function view($id = FALSE)
	{ 
		$this->view_data['submenu'] = array();
		$this->content_view = 'tickets/view';
		$this->view_data['ticket'] = Ticket::find_by_id($id);
		if($this->view_data['ticket']->status == "new"){
			$this->view_data['ticket']->status = "open"; 
			$this->view_data['ticket']->save();
		}
		if(isset($this->view_data['ticket']->user->id)){ $ticket_id = $this->view_data['ticket']->user->id;}else{ $ticket_id = "0"; }
		if($this->view_data['ticket']->updated == "1" AND $ticket_id == $this->user->id){
			$this->view_data['ticket']->updated = "0"; 
			$this->view_data['ticket']->save();
		}
		$this->view_data['form_action'] = 'tickets/article/'.$id.'/add';
		if(!$this->view_data['ticket']){redirect('tickets');}
	}
	function article($id = FALSE, $condition = FALSE, $article_id = FALSE)
	{
		$this->view_data['submenu'] = array(
								$this->lang->line('application_back') => 'tickets',
								$this->lang->line('application_overview') => 'tickets/view/'.$id,
						 		);
		switch ($condition) {
			case 'add':
				$this->content_view = 'tickets/_note';
				if($_POST){
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = '*';

					$this->load->library('upload', $config);
					$this->load->helper('notification');
					

					unset($_POST['userfile']);
					unset($_POST['file-name']);

					unset($_POST['send']);
					unset($_POST['_wysihtml5_mode']);
					unset($_POST['files']);
					$ticket = Ticket::find($id);
					if(isset($_POST['notify'])){
						if(isset($ticket->client->email)){$to = $ticket->client->email;}else{$emailex = explode(' - ', $ticket->from); $to = $emailex[1];}
					send_ticket_notification($to, '[Ticket#'.$ticket->reference.'] - '.$_POST['subject'], $_POST['message'], $ticket->id);
					$_POST['internal'] = "0";
					}
					unset($_POST['notify']);
					$_POST['subject'] = htmlspecialchars($_POST['subject']);
					$_POST['datetime'] = time();
					$_POST['from'] = $this->user->firstname." ".$this->user->lastname.' - '.$this->user->email;
					$_POST['reply_to'] = $this->user->email;
					$article = TicketHasArticle::create($_POST);

					if ( ! $this->upload->do_upload())
						{
							$error = $this->upload->display_errors('', ' ');
							$this->session->set_flashdata('message', 'error:'.$error);

						}
						else
						{
							$data = array('upload_data' => $this->upload->data());

							$attributes = array('article_id' => $article->id, 'filename' => $data['upload_data']['orig_name'], 'savename' => $data['upload_data']['file_name']);
							$attachment = ArticleHasAttachment::create($attributes);
						}

		       		if(!$article){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_article_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_article_success'));}
					redirect('tickets/view/'.$id);
				}else
				{
					$this->theme_view = 'modal';
					$this->view_data['ticket'] = Ticket::find($id);
					$this->view_data['title'] = $this->lang->line('application_add_note');
					$this->view_data['form_action'] = 'tickets/article/'.$id.'/add';
					$this->content_view = 'tickets/_note';
				}	
				break;

			default:
				redirect('tickets');
				break;
		}

	}
	function bulk($action)
	{	
		$this->load->helper('notification');
		if($_POST){
			if(empty($_POST['list'])){redirect('tickets');}
			$list = explode(",", $_POST['list']);
			
			switch ($action) {
				case 'close':
					$attr = array('status' => "closed");
					$email_message = $this->lang->line('messages_bulk_ticket_closed');
					$success_message = $this->lang->line('messages_bulk_ticket_closed_success');
					break;

				default:
					redirect('tickets');
				break;
			}
			
			foreach ($list as $value) {
				$ticket = Ticket::find_by_id($value);
				$ticket->update_attributes($attr);
				send_ticket_notification($ticket->user->email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $email_message, $ticket->id);
				if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_ticket_error'));}
       			else{$this->session->set_flashdata('message', 'success:'.$success_message);}
				
			}
			redirect('tickets');
			
			/*
			if(isset($ticket->client->email)){ $email = $ticket->client->email; } else {$emailex = explode(' - ', $ticket->from); $email = $emailex[1]; }
			if(isset($_POST['notify'])){
				
			send_ticket_notification($email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $_POST['message'], $ticket->id);
			}
			send_ticket_notification($ticket->user->email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $_POST['message'], $ticket->id);
			$_POST['internal'] = "0";
			unset($_POST['notify']);
			$_POST['subject'] = htmlspecialchars($_POST['subject']);
			$_POST['datetime'] = time();
			$_POST['from'] = $this->user->firstname." ".$this->user->lastname.' - '.$this->user->email;
			$_POST['reply_to'] = $this->user->email;
			$_POST['ticket_id'] = $id;
			$_POST['to'] = $email;
			unset($_POST['client_id']);
			$article = TicketHasArticle::create($_POST);
       		if(!$ticket){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_ticket_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_ticket_close_success'));}
			redirect('tickets');
			*/
		}else
		{
			$this->view_data['ticket'] = Ticket::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_close');
			$this->view_data['form_action'] = 'tickets/close';
			$this->content_view = 'tickets/_close';
		}	
	}	

	function attachment($id = FALSE){
		$this->load->helper('download');
		$attachment = TicketHasAttachment::find_by_savename($id);
		$data = file_get_contents('./files/media/'.$attachment->savename); 
		force_download($attachment->filename, $data);
	}
	function articleattachment($id = FALSE){
		$this->load->helper('download');
		$attachment = ArticleHasAttachment::find_by_savename($id);
		$data = file_get_contents('./files/media/'.$attachment->savename); 
		force_download($attachment->filename, $data);
	}

}