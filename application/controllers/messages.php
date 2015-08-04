<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){	
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "messages"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_new_messages') => 'messages',
				 		$this->lang->line('application_read_messages') => 'messages/filter/read',
				 		);	
		$this->load->database();
	}	
	function index()
	{	
		$this->content_view = 'messages/all';
	}
	function messagelist($con = FALSE, $deleted = FALSE)
	{
		$max_value = 20;
		if($deleted == "deleted"){ $qdeleted = " AND privatemessages.status = 'deleted' ";}else{ $qdeleted = " AND privatemessages.status != 'deleted' ";  }

		if(is_numeric($con)){ $limit = $con.','; } else{$limit = FALSE;}
		$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" '.$qdeleted.' ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
		
		$sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" '.$qdeleted.' ORDER BY privatemessages.`time` DESC';


		$query = $this->db->query($sql);
		$query2 = $this->db->query($sql2);				
		$rows = $query2->num_rows();		
		$this->view_data["message"] = array_filter($query->result());
		$this->view_data["message_rows"] = $rows;
		if($deleted){$this->view_data["deleted"] = "/".$deleted;}
		$this->view_data["message_list_page_next"] = $con+$max_value;
		$this->view_data["message_list_page_prev"] = $con-$max_value;
    $this->view_data["filter"] = FALSE;
		$this->theme_view = 'ajax';
		$this->content_view = 'messages/list';
	}
	function filter($condition = FALSE, $con = FALSE)
	{
	    $max_value = 20;
	    if(is_numeric($con)){ $limit = $con.','; } else{$limit = FALSE;}
		switch ($condition) {
			case 'read':
				$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND (privatemessages.`status`="Replied" OR privatemessages.`status`="Read") ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
				
				$sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" ORDER BY privatemessages.`time` DESC';
				$this->view_data["filter"] = "Read";
				break;
			case 'sent':
				$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.sender = "u'.$this->user->id.'" ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
			    
			    $sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.sender = "u'.$this->user->id.'" ORDER BY privatemessages.`time` DESC';
			    
			    $this->view_data["filter"] = "Sent";
				break;
			case 'marked':
				$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.`status`="Marked" ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
				
				$sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.`status`="Marked" ORDER BY privatemessages.`time` DESC';
				$this->view_data["filter"] = "Marked";
				break;
			case 'deleted':
		        $sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.status = "deleted" ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
		
		        $sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.conversation, privatemessages.sender, privatemessages.recipient, privatemessages.message, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.status = "deleted" ORDER BY privatemessages.`time` DESC';
				$this->view_data["filter"] = "Deleted";
				break;
			default:
				$sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c
				FROM privatemessages
				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender 
				GROUP by privatemessages.id HAVING privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.`status`="New" ORDER BY privatemessages.`time` DESC LIMIT '.$limit.$max_value;
				$this->view_data["filter"] = FALSE;
				break;
		}
		
		$query = $this->db->query($sql);
		$query2 = $this->db->query($sql2);
		$rows = $query2->num_rows();
		$this->view_data["message"] = array_filter($query->result());
		$this->view_data["message_rows"] = $rows;
		$this->view_data["message_list_page_next"] = $con+$max_value;
		$this->view_data["message_list_page_prev"] = $con-$max_value;
		
	    $this->theme_view = 'ajax';
		$this->content_view = 'messages/list';
	}
	function write($ajax = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			unset($_POST['note-codable']);
			unset($_POST['files']);
			$message = $_POST['message'];
			$receiverart = substr($_POST['recipient'], 0, 1);
			$receiverid = substr($_POST['recipient'], 1, 9999);
			if( $receiverart == "u"){
				$receiver = User::find($receiverid);
				$receiveremail = $receiver->email;
			}else{
				$receiver = Client::find($receiverid);
				$receiveremail = $receiver->email;
			}
			$_POST = array_map('htmlspecialchars', $_POST);
			$_POST['message'] = $message;
			$_POST['time'] = date('Y-m-d H:i', time());
			$_POST['sender'] = "u".$this->user->id;
			$_POST['status'] = "New";
			if(!isset($_POST['conversation'])){$_POST['conversation'] = random_string('sha1');}
			if(isset($_POST['previousmessage'])){
			    $status = Privatemessage::find_by_id($_POST['previousmessage']);
        		$status->status = 'Replied';
        		$status->save();
        		unset($_POST['previousmessage']);
			}
			$message = Privatemessage::create($_POST);
       		if(!$message){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_write_message_error'));}
       		else{
       				$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_write_message_success'));
       				$this->load->helper('notification');
       				send_notification($receiveremail, $this->lang->line('application_notification_new_message_subject'), $this->lang->line('application_notification_new_message').'<br><hr style="border-top: 1px solid #CCCCCC; border-left: 1px solid whitesmoke; border-bottom: 1px solid whitesmoke;"/>'.$_POST['message'].'<hr style="border-top: 1px solid #CCCCCC; border-left: 1px solid whitesmoke; border-bottom: 1px solid whitesmoke;"/>');
       			}
			if($ajax != "reply"){ redirect('messages'); }else{
					$this->theme_view = 'ajax';
				}
		}else
		{
			$this->view_data['clients'] = Client::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['users'] = User::find('all',array('conditions' => array('status=?','active')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_write_message');
			$this->view_data['form_action'] = 'messages/write';
			$this->content_view = 'messages/_messages';
		}	
	}	
	function update($id = FALSE, $getview = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			unset($_POST['_wysihtml5_mode']);
			unset($_POST['files']);
			$id = $_POST['id'];
			$message = Privatemessage::find($id);
			$message->update_attributes($_POST);
       		if(!$message){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_write_message_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_write_message_success'));}
			if(isset($view)){redirect('messages/view/'.$id);}else{redirect('messages');}
			
		}else
		{
			$this->view_data['id'] = $id;
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_message');
			$this->view_data['form_action'] = 'messages/update';
			$this->content_view = 'messages/_messages_update';
		}	
	}
	function delete($id = FALSE)
	{	
		$message = Privatemessage::find_by_id($id);
		$message->status = 'deleted';
		$message->save();
		$this->content_view = 'messages/all';
		if(!$message){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_message_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_message_success'));}
			redirect('messages');
	}	
	function mark($id = FALSE)
	{	
		$message = Privatemessage::find_by_id($id);
		if($message->status == 'Marked'){
		    $message->status = 'Read';
		}else{
		    $message->status = 'Marked';
		}
		$message->save();
		$this->content_view = 'messages/all';
		
	}
	function view($id = FALSE, $filter = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'messages',
				 		);	
		$message = Privatemessage::find_by_id($id);
		$this->view_data["count"] = "1";
		if(!$filter){
            		if($message->status == "New"){
            			$message->status = 'Read';
            			$message->save();
            		}
        		$this->view_data["filter"] = FALSE;
        		$sql = 'SELECT privatemessages.id, privatemessages.conversation FROM privatemessages
        				WHERE privatemessages.recipient = "u'.$this->user->id.'" AND privatemessages.`id`="'.$id.'"';
        		$query = $this->db->query($sql);
        		$row = $query->row();	
        		$sql2 = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.conversation, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, privatemessages.`sender` , clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c, CONCAT(rec_u.firstname," ", rec_u.lastname) as recipient_u, CONCAT(rec_c.firstname," ", rec_c.lastname) as recipient_c
        				FROM privatemessages
        				LEFT JOIN clients ON CONCAT("c",clients.id) = privatemessages.sender
        				LEFT JOIN users ON CONCAT("u",users.id) = privatemessages.sender
        				LEFT JOIN clients AS rec_c ON CONCAT("c",rec_c.id) = privatemessages.recipient
        				LEFT JOIN users AS rec_u ON CONCAT("u",rec_u.id) = privatemessages.recipient

        				GROUP by privatemessages.id HAVING privatemessages.conversation = "'.$row->conversation.'" ORDER BY privatemessages.`id` DESC LIMIT 100';
        		$query2 = $this->db->query($sql2);
        		
        		
        		$this->view_data["conversation"] = array_filter($query2->result());
        		$this->view_data["count"] = count ($this->view_data["conversation"]);
		    }else{
		        if($message->status == 'deleted'){
		        $sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.conversation, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, privatemessages.`sender` , clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c, CONCAT(users.firstname," ", users.lastname) as recipient_u, CONCAT(clients.firstname," ", clients.lastname) as recipient_c
        				FROM privatemessages
        				LEFT JOIN clients ON (CONCAT("c",clients.id) = privatemessages.sender) OR (CONCAT("c",clients.id) = privatemessages.recipient)
        				LEFT JOIN users ON (CONCAT("u",users.id) = privatemessages.sender) OR (CONCAT("u",users.id) = privatemessages.recipient)
        				GROUP by privatemessages.id HAVING privatemessages.id = "'.$id.'" AND privatemessages.recipient = "u'.$this->user->id.'" ORDER BY privatemessages.`id` DESC LIMIT 100';
		       
		        }else{
		        $sql = 'SELECT privatemessages.id, privatemessages.`status`, privatemessages.conversation, privatemessages.subject, privatemessages.message, privatemessages.sender, privatemessages.recipient, privatemessages.`time`, privatemessages.`sender` , clients.`userpic` as userpic_c, users.`userpic` as userpic_u , users.`email` as email_u , clients.`email` as email_c , CONCAT(users.firstname," ", users.lastname) as sender_u, CONCAT(clients.firstname," ", clients.lastname) as sender_c, CONCAT(users.firstname," ", users.lastname) as recipient_u, CONCAT(clients.firstname," ", clients.lastname) as recipient_c
        				FROM privatemessages
        				LEFT JOIN clients ON (CONCAT("c",clients.id) = privatemessages.sender) OR (CONCAT("c",clients.id) = privatemessages.recipient)
        				LEFT JOIN users ON (CONCAT("u",users.id) = privatemessages.sender) OR (CONCAT("u",users.id) = privatemessages.recipient)
        				GROUP by privatemessages.id HAVING privatemessages.id = "'.$id.'" AND privatemessages.sender = "u'.$this->user->id.'" ORDER BY privatemessages.`id` DESC LIMIT 100';
		        }
        		$query = $this->db->query($sql);
        		
		        $this->view_data["conversation"] = array_filter($query->result());
		        $this->view_data["filter"] = $filter;
		    }
		$this->theme_view = 'ajax';
		$this->view_data['form_action'] = 'messages/write';
		$this->view_data['id'] = $id;
		$this->content_view = 'messages/view';
	}
	
}