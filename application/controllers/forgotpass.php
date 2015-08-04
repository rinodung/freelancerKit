<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgotpass extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	function index()
	{	
			$this->load->database();
			$this->view_data['error'] = 'false';
			$this->theme_view = 'login';
			$this->content_view = 'auth/forgotpass';
			$sql = "DELETE FROM pw_reset WHERE `timestamp`+ (24 * 60 * 60) < timestamp";
			$query = $this->db->query($sql);
		
		if($_POST)
		{
			$user = User::find_by_email($_POST['email']);
			$usertrue = "1";
			if(!$user){$user = Client::find_by_email($_POST['email']); $usertrue = "0";}
			if(($user && $usertrue == "1" && $user->status == "active") || ($user && $usertrue == "0" &&  $user->inactive == "0")){
			    
				$timestamp = time();
				$token = md5($timestamp);

				$this->load->library('parser');
				$this->load->helper('file');
				$sql = "INSERT INTO `pw_reset` (`email`, `timestamp`, `token`, `user`) VALUES ('".$user->email."', '".$timestamp."', '".$token."', '".$usertrue."');";
				$query = $this->db->query($sql);			
				$data["core_settings"] = Setting::first();
				$this->email->from($data["core_settings"]->email, $data["core_settings"]->company);
				$this->email->to($user->email); 

				$this->email->subject('Forgot your password');
				$parse_data = array(
            					'link' => base_url().'forgotpass/token/'.$token,
            					'company' => $data["core_settings"]->company,
            					'logo' => '<img src="'.base_url().''.$data["core_settings"]->logo.'" alt="'.$data["core_settings"]->company.'"/>',
            					'invoice_logo' => '<img src="'.base_url().''.$data["core_settings"]->invoice_logo.'" alt="'.$data["core_settings"]->company.'"/>'
            					);
	  			$email = read_file('./application/views/'.$data["core_settings"]->template.'/templates/email_pw_reset_link.html');
	  			$message = $this->parser->parse_string($email, $parse_data);
				$this->email->message($message);
				$this->email->send();
				$this->view_data['message'] = 'success:'.$this->lang->line('messages_password_reset_email');
			    redirect('login');
			}else{
			    $this->view_data['message'] = 'error:'.$this->lang->line('messages_password_reset_email_error');
			    redirect('login');
			}
			
			
				
		}
		
	}
	function token($token = FALSE){
				$this->load->database();
				$sql = "SELECT * FROM `pw_reset` WHERE token = '".$token."'";
				$query = $this->db->query($sql);
				$result = $query->result();
				if($result){
					$lees = $result[0]->timestamp + (24 * 60 * 60);
					if(time() < $lees){
						$new_password = substr(str_shuffle(strtolower(sha1(rand() . time() . "nekdotlggjaoudlpqwejvlfk"))),0, 8);
						if($result[0]->user == "1"){
							$user = User::find_by_email($result[0]->email);	
							$user->set_password($new_password);
							$user->save();
							
						}else{
							$client = Client::find_by_email($result[0]->email);	
							$client->password = $new_password;
							$client->save();
						}
						$sql = "DELETE FROM `pw_reset` WHERE `email`='".$result[0]->email."'";
						$query = $this->db->query($sql);

						$data["core_settings"] = Setting::first();
						$this->email->from($data["core_settings"]->email, $data["core_settings"]->company);
						$this->email->to($result[0]->email); 
						$this->load->library('parser');
						$this->load->helper('file');
						$this->email->subject('Reset of your password');
						$parse_data = array(
										'password' => $new_password,
		            					'link' => base_url(),
		            					'company' => $data["core_settings"]->company,
		            					'logo' => '<img src="'.base_url().''.$data["core_settings"]->logo.'" alt="'.$data["core_settings"]->company.'"/>',
		            					'invoice_logo' => '<img src="'.base_url().''.$data["core_settings"]->invoice_logo.'" alt="'.$data["core_settings"]->company.'"/>'
		            					);
			  			$email = read_file('./application/views/'.$data["core_settings"]->template.'/templates/email_pw_reset.html');
			  			$message = $this->parser->parse_string($email, $parse_data);
						$this->email->message($message);
						$this->email->send();
						$this->view_data['message'] = 'success:'.$this->lang->line('messages_password_reset');
						redirect('login');
					}

				}else{
					redirect('login');
				}	
	}
	
}
