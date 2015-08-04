<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		if($this->client){	
		}elseif($this->user){
		}else{
			redirect('login');
		}
	}
	
	function index(){
		
		if($this->client){	
				$user = Client::find($this->client->id);
				if($_POST){
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}
					unset($_POST['send']);
					unset($_POST['userfile']);
					unset($_POST['file-name']);
	 		if(!empty($_POST['password'])){ $attr['password'] = $_POST['password'];}
	 		if(!empty($_POST['userpic'])){ $attr['userpic'] = $_POST['userpic']; }
	 		$user->update_attributes($attr);
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_password_changed'));
	 		redirect('');
	 		}else{
	 			$this->view_data['user'] = $user;
				$this->theme_view = 'modal';
				$this->view_data['title'] = $this->lang->line('application_change_password');
				$this->view_data['form_action'] = 'agent/';
				$this->content_view = 'settings/_clientform';
	 		}
		}elseif($this->user){
			 $user = User::find($this->user->id);

			 if($_POST){
			 	
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}
					
					unset($_POST['send']);
					unset($_POST['userfile']);
					unset($_POST['file-name']);
					unset($_POST['access']);
			 	$_POST = array_map('htmlspecialchars', $_POST);
	 			$attr = array(
					 		'username' => $_POST['username'],
					 		'firstname' => $_POST['firstname'],
					 		'lastname' => $_POST['lastname'],
					 		'email' => $_POST['email'],
					 		);
	 		if(!empty($_POST['userpic'])){ $attr['userpic'] = $_POST['userpic']; }
	 		if(!empty($_POST['password'])){ $attr['password'] = $_POST['password'];}
	 		$user->update_attributes($attr);
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_changes_saved'));
	 		redirect('');
	 		}else{
	 			$this->view_data['user'] = $user;
				$this->theme_view = 'modal';
				$this->view_data['title'] = $this->lang->line('application_change_password');
				$this->view_data['agent'] = true;
				$this->view_data['form_action'] = 'agent/';
				$this->content_view = 'settings/_userform';
	 		}
		}

 		
 		
	}
	function language($lang = false){
		$folder = 'application/language/';
		$languagefiles = scandir($folder);
		if(in_array($lang, $languagefiles)){
		$cookie = array(
                   'name'   => 'language',
                   'value'  => $lang,
                   'expire' => '31536000',
               );
 
		$this->input->set_cookie($cookie);
		}
		redirect(''); 
	}

	
}