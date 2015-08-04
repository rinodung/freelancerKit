<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller
{
	function login()
	{
			$this->view_data['error'] = "false";		
			$this->theme_view = 'login';

		
		if($_POST)
		{
			$user = User::validate_login($_POST['username'], $_POST['password']);
			if($user){
				if($this->input->cookie('fc2_link') != ""){
					redirect($this->input->cookie('fc2_link'));
				}else{
					redirect('');
				}
			}
			else {
				$this->view_data['error'] = "true";
				$this->view_data['username'] = $_POST['username'];
				$this->view_data['message'] = 'error:'.$this->lang->line('messages_login_incorrect');
			}
		}
		
	}
	
	function logout()
	{
	    	if($this->user){ 
			$update = User::find($this->user->id); 
			}else{
				$update = Client::find($this->client->id);
			}
				$update->last_active = 0;
				$update->save();
		User::logout();
			redirect('login');
	}
	
}
