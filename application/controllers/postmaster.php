<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postmaster extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();

		$this->load->database();
		
	}	
	function index()
	{


			$this->load->helper('notification');
			$this->load->helper('string');
			$emailconfig = Setting::first();
			if($emailconfig->ticket_config_active == "1"){
			$emailconfig->ticket_config_timestamp = time();
			$emailconfig->save();	

			// this shows basic IMAP, no TLS required
			$config['login'] = $emailconfig->ticket_config_login;
			$config['pass'] = $emailconfig->ticket_config_pass;
			$config['host'] = $emailconfig->ticket_config_host;
			$config['port'] = $emailconfig->ticket_config_port;
			$config['mailbox'] = $emailconfig->ticket_config_mailbox;

			if($emailconfig->ticket_config_imap == "1"){$flags = "/imap";}else{$flags = "/pop3";}
			if($emailconfig->ticket_config_ssl == "1"){$flags .= "/ssl";}

			$config['service_flags'] = $flags.$emailconfig->ticket_config_flags;

			$this->load->library('peeker', $config);
			//attachment folder
			$bool = $this->peeker->set_attachment_dir('files/media/');
			//Search Filter
			$this->peeker->set_search($emailconfig->ticket_config_search);
			
			if ($this->peeker->search_and_count_messages() != "0")
			{

			log_message('error', 'Postmaster fetched '.$this->peeker->search_and_count_messages().' new email tickets.');

				$id_array = $this->peeker->get_ids_from_search();
				//walk trough emails
				foreach($id_array as $email_id){
						$ticket = false;
						$email = $this->peeker->get_message($email_id);
						$email->rewrite_html_transform_img_tags('files/media/');
						$emailbody = utf8_encode((nl2br($email->get_plain())));
					    $emailaddr = $email->get_from_array();  
					    $emailaddr = $emailaddr[0]->mailbox.'@'.$emailaddr[0]->host;  

						//get next ticket number
						$settings = Setting::first();
						$ticket_reference = $settings->ticket_reference;
						$settings->ticket_reference = $settings->ticket_reference+1;
						$settings->save();			
						

						if (preg_match('/(?<=\[Ticket\#)(.+)(?=\])/is', $email->get_subject(), $matches)) {
						    $ticket = Ticket::find_by_reference($matches[1]);
						}

						if($ticket){
								log_message('error', 'Fetched email merged to ticket #'.$matches[1]);
								$article_attributes = array(
										'ticket_id' => $ticket->id,
										'internal' => '0',
										'from' => $email->get_from().' - '.$emailaddr, 
										'reply_to' => $emailaddr,
										'to' => $email->get_to(),
										'cc' => $email->get_cc(),
										'subject' => htmlspecialchars($email->get_subject()),
										'message' => $emailbody,
										'datetime' => time(),

								);
								if($ticket->status == "closed"){
									$ticket->status = 'reopened';
									$ticket->updated = '1';
									$ticket->save();
								}
								$ticket->updated = '1';
								$ticket->save();
								$article = TicketHasArticle::create($article_attributes);

								if(isset($ticket->user->email)){
									send_ticket_notification($ticket->user->email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $emailbody, $ticket->id);
								}
								//Attachments
									$parts = $email->get_parts_array();

									if($email->has_attachment()){
										foreach ($parts as $part)
										{
											$savename = $email->get_fingerprint().random_string('alnum', 8).$part->get_filename();
											$orgname = $part->get_filename();
											$part->filename = $savename;
											$attributes = array('article_id' => $article->id, 'filename' => $orgname, 'savename' => $savename);
											$attachment = ArticleHasAttachment::create($attributes);
										}
										$email->save_all_attachments('files/media/');
									}
									
							}else{

									//Ticket Attributes
									$ticket_attributes = array(
										'reference' => $ticket_reference,
										'from' => $email->get_from().' - '.$emailaddr, 
										'subject' => $email->get_subject(),
										'text' => $emailbody,
										'updated' => "1",
										'created' => time(),
										'user_id' => $settings->ticket_default_owner,
										'type_id' => $settings->ticket_default_type,
										'status' => $settings->ticket_default_status,
										'queue_id' => $settings->ticket_default_queue,

									);

									//check if sender is client
									$client = Client::find_by_email($emailaddr);
									if(isset($client)){
										$ticket_attributes['client_id'] = $client->id;
										$ticket_attributes['company_id'] = $client->company->id;
									}
									
									//create Ticket
									$ticket = Ticket::create($ticket_attributes);

									//Attachments
									$parts = $email->get_parts_array();

									if($email->has_attachment()){
										foreach ($parts as $part)
										{
											$savename = $email->get_fingerprint().random_string('alnum', 8).$part->get_filename();
											$orgname = $part->get_filename();
											$part->filename = $savename;
											$attributes = array('ticket_id' => $ticket->id, 'filename' => $orgname, 'savename' => $savename);
											$attachment = TicketHasAttachment::create($attributes);
										}
										$email->save_all_attachments('files/media/');
									}
                                    send_ticket_notification($ticket->user->email, '[Ticket#'.$ticket->reference.'] - '.$ticket->subject, $emailbody, $ticket->id);
									
									log_message('error', 'New ticket created #'.$ticket->reference);
							}

					if($emailconfig->ticket_config_delete == "1"){	
						$email->set_delete();
						$email->expunge();
						$this->peeker->delete_and_expunge($email_id);
					}
				}
			}

			$this->peeker->close();

			// tell the story of the connection (only for debuging)
			//echo "<pre>"; print_r($this->peeker->trace()); echo "</pre>";


			
		}
		die();
	}

}