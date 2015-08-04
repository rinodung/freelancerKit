<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		unset($_POST['DataTables_Table_0_length']);
		if($this->client){	
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "settings"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		if(!$this->user->admin) {
			$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_no_access'));
			redirect('dashboard');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_settings') => 'settings',
				 		$this->lang->line('application_templates') => 'settings/templates',
				 		$this->lang->line('application_paypal') => 'settings/paypal',
				 		$this->lang->line('application_users') => 'settings/users',
				 		$this->lang->line('application_system_updates') => 'settings/updates',
				 		$this->lang->line('application_backup') => 'settings/backup',
				 		$this->lang->line('application_cronjob') => 'settings/cronjob',
				 		$this->lang->line('application_ticket') => 'settings/ticket',
				 		$this->lang->line('application_customize') => 'settings/customize',
				 		$this->lang->line('application_logs') => 'settings/logs',
				 		);	
		$this->config->load('defaults');
		$settings = Setting::first();
		$this->load->helper('curl');
		$object = remote_get_contents('http://fc2.luxsys-apps.com/updates/xml.php?code='.$settings->pc);
		$object = json_decode($object);
		$this->view_data['update_count'] = FALSE;
		if(isset($object->error)) {
			if($object->error == FALSE && $object->lastupdate > $settings->version){
			$this->view_data['update_count'] = "1";
			}
		}
	}
	
	function index()
	{
		$this->view_data['breadcrumb'] = $this->lang->line('application_settings');
		$this->view_data['breadcrumb_id'] = "settings";

		$this->view_data['settings'] = Setting::first();
		$this->view_data['form_action'] = 'settings/settings_update';
		$this->content_view = 'settings/settings_all';


	}

	function settings_update(){
		if($_POST){

					$config['upload_path'] = './files/media/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '600';
					$config['max_width']  = '300';
					$config['max_height']  = '300';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload())
						{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload."){
								//$this->session->set_flashdata('message', 'error:'.$error);
						}
						}
						else
						{
							$data = array('upload_data' => $this->upload->data());
							$_POST['logo'] = "files/media/".$data['upload_data']['file_name'];
							
						}
					if ( ! $this->upload->do_upload("userfile2"))
						{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload."){
								//$this->session->set_flashdata('message', 'error:'.$error);	
						}
						}
						else
						{
							$data = array('upload_data' => $this->upload->data());
							$_POST['invoice_logo'] = "files/media/".$data['upload_data']['file_name'];
							
						}
				
		unset($_POST['userfile']);	
		unset($_POST['userfile2']);
		unset($_POST['file-name']);	
		unset($_POST['file-name2']);
		unset($_POST['_wysihtml5_mode']);				
		unset($_POST['send']);
		$settings = Setting::first();
		$settings->update_attributes($_POST);
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
 		redirect('settings');
 		}else{
 			$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
 			redirect('settings');
 		}
	}
	function settings_reset($template = FALSE){
		$this->load->helper('file');
		$settings = Setting::first();
			if($template){
				$data = read_file('./application/views/'.$settings->template.'/templates/default/'.$template.'.html');
				if(write_file('./application/views/'.$settings->template.'/templates/'.$template.'.html', $data)){
					$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_reset_mail_body_success'));
	 				redirect('settings/templates');
	 			}
			

			}
			
	}
	function templates($template = "invoice"){
		$this->load->helper('file');
		$settings = Setting::first();
		$filename = './application/views/'.$settings->template.'/templates/email_'.$template.'.html';
		$this->view_data['folder_path'] = '/application/views/'.$settings->template.'/templates/ ';
		if (!is_writable($filename)) {
		    $this->view_data['not_writable'] = true;
		}else{
			$this->view_data['not_writable'] = false;
		}
		$this->view_data['breadcrumb'] = $this->lang->line('application_templates');
		$this->view_data['breadcrumb_id'] = "templates";

		$this->view_data['breadcrumb_sub'] = $this->lang->line('application_'.$template);
		$this->view_data['breadcrumb_sub_id'] = $template;
		
				if($_POST){
						$data = $_POST["mail_body"];
						unset($_POST["mail_body"]);
						unset($_POST["send"]);
						
						$settings->update_attributes($_POST);
						if(write_file('./application/views/'.$settings->template.'/templates/email_'.$template.'.html', $data)){
						$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_template_success'));
				 		redirect('settings/templates/'.$template);
							}else{
								$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_template_error'));
					 			redirect('settings/templates/'.$template);
					 			}
				 		}else{

				 		$this->view_data['email'] = read_file('./application/views/'.$settings->template.'/templates/email_'.$template.'.html');
				 		$this->view_data['template'] = $template;
				 		$this->view_data['template_files'] = get_filenames('./application/views/'.$settings->template.'/templates/default/');
				 		$this->view_data['template_files'] = str_replace('.html', '', $this->view_data['template_files']);
				 		$this->view_data['template_files'] = str_replace('email_', '', $this->view_data['template_files']);

				 		$this->view_data['settings'] = Setting::first();
						$this->view_data['form_action'] = 'settings/templates/'.$template;
						$this->content_view = 'settings/templates';
				 }
		
	}
	function paypal(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_paypal');
		$this->view_data['breadcrumb_id'] = "paypal";

		if($_POST){
						
		unset($_POST['send']);
		if(isset($_POST['paypal'])){
		if($_POST['paypal'] != "1"){$_POST['paypal'] = "0";}
		}else{$_POST['paypal'] = "0";}
		$settings = Setting::first();
		$settings->update_attributes($_POST);
		if($settings){
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
 		redirect('settings/paypal');
			}else{
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
	 			redirect('settings/paypal');
	 			}
 		}else{
 			
 		$this->view_data['settings'] = Setting::first();
		$this->view_data['form_action'] = 'settings/paypal';
		$this->content_view = 'settings/paypal';
 		}
	}
	function cronjob(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_cronjob');
		$this->view_data['breadcrumb_id'] = "cronjob";
		if($_POST){
						
		unset($_POST['send']);
		if($_POST['cronjob'] != "1"){$_POST['cronjob'] = "0";}
		if($_POST['autobackup'] != "1"){$_POST['autobackup'] = "0";}
		$settings = Setting::first();
		$settings->update_attributes($_POST);
		if($settings){
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
 		redirect('settings/cronjob');
			}else{
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
	 			redirect('settings/cronjob');
	 			}
 		}else{
 			
 		$this->view_data['settings'] = Setting::first();
		$this->view_data['form_action'] = 'settings/cronjob';
		$this->content_view = 'settings/cronjob';
 		}
	}
	function ticket(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_ticket');
		$this->view_data['breadcrumb_id'] = "ticket";
		if($_POST){
						
		unset($_POST['send']);
		if(!isset($_POST['ticket_config_active'])){$_POST['ticket_config_active'] = "0";}
		if(!isset($_POST['ticket_config_delete'])){$_POST['ticket_config_delete'] = "0";}
		if(!isset($_POST['ticket_config_ssl'])){$_POST['ticket_config_ssl'] = "0";}
		if(!isset($_POST['ticket_config_imap'])){$_POST['ticket_config_imap'] = "0";}
		$settings = Setting::first();
		$settings->update_attributes($_POST);
		if($settings){
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
 		redirect('settings/ticket');
			}else{
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
	 			redirect('settings/ticket');
	 			}
 		}else{
 			
 		$this->view_data['settings'] = Setting::first();
 		$this->view_data['types'] = Type::find('all', array('conditions' => array('inactive = ?', '0')));
 		$this->view_data['queues'] = Queue::find('all', array('conditions' => array('inactive = ?', '0')));
 		$this->view_data['owners'] = User::find('all', array('conditions' => array('status = ?', 'active')));
		$this->view_data['form_action'] = 'settings/ticket';
		$this->content_view = 'settings/ticket';
 		}
	}
	function ticket_type($id = FALSE, $condition = FALSE){
		if($condition == "delete"){
			$_POST["inactive"] = "1";
			$type = Type::find_by_id($id);
			$type->update_attributes($_POST);
		}else{

			if($_POST){
						
			unset($_POST['send']);
		
			if($id){
				$type = Type::find_by_id($id);
				$type->update_attributes($_POST);
				
			}else{
				$type = Type::create($_POST);
			}
			if($type){
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
	 		redirect('settings/ticket');
				}else{
					$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
		 			redirect('settings/ticket');
		 			}
	 		}else{
	 		if($id){
	 			$this->view_data['type'] = Type::find_by_id($id);
	 		}
	 		
	 		$this->view_data['title'] = $this->lang->line('application_type');
			$this->view_data['form_action'] = 'settings/ticket_type/'.$id;
			$this->content_view = 'settings/_ticket_type';
	 		}
 		}
 		$this->theme_view = 'modal_nojs';
	}
	function ticket_queue($id = FALSE, $condition = FALSE){
		if($condition == "delete"){
			$_POST["inactive"] = "1";
			$type = Queue::find_by_id($id);
			$type->update_attributes($_POST);
		}else{

			if($_POST){
							
			unset($_POST['send']);
			if($id){
			$queue = Queue::find_by_id($id);
			$queue->update_attributes($_POST);
			}else{
			$queue = Queue::create($_POST);
			}
			if($queue){
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_settings_success'));
	 		redirect('settings/ticket');
				}else{
					$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_settings_error'));
		 			redirect('settings/ticket');
		 			}
	 		}else{
	 		if($id){
	 			$this->view_data['queue'] = Queue::find_by_id($id);
	 		}
	 		$this->theme_view = 'modal_nojs';
	 		$this->view_data['title'] = $this->lang->line('application_queue');
			$this->view_data['form_action'] = 'settings/ticket_queue/'.$id;
			$this->content_view = 'settings/_ticket_queue';
	 		}
	 	}
	}
	function testpostmaster(){

			$emailconfig = Setting::first();
			$config['login'] = $emailconfig->ticket_config_login;
			$config['pass'] = $emailconfig->ticket_config_pass;
			$config['host'] = $emailconfig->ticket_config_host;
			$config['port'] = $emailconfig->ticket_config_port;
			$config['mailbox'] = $emailconfig->ticket_config_mailbox;

			if($emailconfig->ticket_config_imap == "1"){$flags = "/imap";}else{$flags = "/pop3";}
			if($emailconfig->ticket_config_ssl == "1"){$flags .= "/ssl";}

			$config['service_flags'] = $flags.$emailconfig->ticket_config_flags; 

			$this->load->library('peeker_connect');
			$this->peeker_connect->initialize($config);
			
			if($this->peeker_connect->is_connected()){
				$this->view_data['msgresult'] = "success";
				$this->view_data['result'] = "Connection to email mailbox successful!";
			}else{
				$this->view_data['msgresult'] = "error";
				$this->view_data['result'] = "Connection to email mailbox not successful!";
			}
			$this->peeker_connect->message_waiting();
			
			$this->peeker_connect->close();
			$this->view_data['trace'] = $this->peeker_connect->trace();
		$this->content_view = 'settings/_testpostmaster';
		$this->theme_view = 'modal_nojs';
		$this->view_data['title'] = $this->lang->line('application_postmaster_test');
	}
	function customize(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_customize');
		$this->view_data['breadcrumb_id'] = "customize";

		$this->load->helper('file');
		$this->view_data['settings'] = Setting::first();
		if($_POST){
		$data = $_POST['css-area'];			
		//$settings = Setting::first();
		//$settings->update_attributes($_POST);
		

		if(write_file('./assets/'.$this->view_data['settings']->template.'/css/user.css', $data)){
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_customize_success'));
 		redirect('settings/customize');
			}else{
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_customize_error'));
	 			redirect('settings/customize');
	 			}
 		}else{
 			$this->view_data['writable'] = FALSE;
		if (is_writable('./assets/'.$this->view_data['settings']->template.'/css/user.css')) {
    		$this->view_data['writable'] = TRUE;
		}
 		$this->view_data['css'] = read_file('./assets/'.$this->view_data['settings']->template.'/css/user.css');
		$this->view_data['form_action'] = 'settings/customize';
		$this->content_view = 'settings/customize';
 		}
	}
	function users()
	{
		$this->view_data['breadcrumb'] = $this->lang->line('application_users');
		$this->view_data['breadcrumb_id'] = "users";

		$options = array('conditions' => array('status != ?', 'deleted'));
		$users = User::all($options);
		$this->view_data['users'] = $users;
		$this->content_view = 'settings/user';
	}

	function user_delete($user = FALSE)
	{
		$user = User::find($user);
		if($this->user != $user) {
		$user->status = 'deleted';
		$user->save();
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_user_success'));
		}else{
		$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_user_error'));
		}
		redirect('settings/users');
	}

	function user_create()
	{
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
					
			unset($_POST['file-name']);
			unset($_POST['send']);
			unset($_POST['confirm_password']);
			$_POST["access"] = implode(",", $_POST["access"]);
			$_POST = array_map('htmlspecialchars', $_POST);
			$user_exists = User::find_by_username($_POST['username']);
			if(empty($user_exists)){
			$user = User::create($_POST);
       		if(!$user){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_user_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_user_success'));}
       		}else{
       			$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_user_exists'));
       		}
			redirect('settings/users');
		}else
		{
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_create_user');
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type != ?', 'client')));
			$this->view_data['form_action'] = 'settings/user_create/';
			$this->content_view = 'settings/_userform';
		}
	
	}

	function user_update($user = FALSE){
 		$user = User::find($user);

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
					
		unset($_POST['file-name']);
 		unset($_POST['send']);
 		unset($_POST['confirm_password']);
 		if(!empty($_POST["access"])){$_POST["access"] = implode(",", $_POST["access"]);}
 		$_POST = array_map('htmlspecialchars', $_POST);
 		if(empty($_POST['password'])){ unset($_POST['password']);}
 		if($_POST['admin'] == "0" && $_POST['username'] == "Admin"){ $_POST['admin'] = "1";}
 		$user->update_attributes($_POST);
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_user_success'));
 		redirect('settings/users');
 		}else{
 			$this->view_data['user'] = $user;
			$this->theme_view = 'modal';
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type != ?', 'client')));
			$this->view_data['title'] = $this->lang->line('application_edit_user');
			$this->view_data['form_action'] = 'settings/user_update/'.$user->id;
			$this->content_view = 'settings/_userform';
 		}
 		
	}
	function updates(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_updates');
		$this->view_data['breadcrumb_id'] = "updates";
		$this->view_data['settings'] = Setting::first();
		$this->load->helper('file');
		$this->load->helper('curl');

		$filename = './application/controllers/projects.php';
		if (is_writable($filename)) {
    		$this->view_data['writable'] = "TRUE";
		} else {
		    $this->view_data['writable'] = "FALSE";
		}

		$fileversion = read_file('./application/version.txt');

		if ($fileversion != $this->view_data['settings']->version) {
    		$this->view_data['version_mismatch'] = "TRUE";
		} else {
		    $this->view_data['version_mismatch'] = "FALSE";
		}

		
		
		$this->view_data['downloaded_updates'] = get_filenames('./files/updates/');

		$object = remote_get_contents('http://fc2.luxsys-apps.com/updates/xml.php?code='.$this->view_data['settings']->pc);
		$object = json_decode($object);
		$this->view_data['curl_error'] = FALSE;

		if(isset($object->error)) {
			if($object->error == FALSE){
			$this->view_data['lists'] = $object->updatelist;
			}else{
				$this->view_data['lists'] = array();
				$this->session->set_flashdata('message', 'error: '.$object->error);
			}

		}else{
				$this->view_data['curl_error'] = TRUE;
				$this->view_data['lists'] = array();
			}

		$this->content_view = 'settings/updates';
	}
	function backup(){
		$this->view_data['breadcrumb'] = $this->lang->line('application_backup');
		$this->view_data['breadcrumb_id'] = "backup";

		$this->view_data['settings'] = Setting::first();
		$this->load->helper('file');
		$this->view_data['backups'] = get_filenames('./files/backup/');
		if(!isset($this->view_data['backups'])){$this->session->set_flashdata('message', 'error: Could not check backup folder');}

		$this->content_view = 'settings/backup';
	}
	function logs($val = FALSE){
		$this->view_data['breadcrumb'] = $this->lang->line('application_logs');
		$this->view_data['breadcrumb_id'] = "logs";

		$this->load->helper('file');
		if($val == "clear"){
				delete_files('./application/logs/');		
				$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_log_cleared'));
	 			redirect('settings/logs');

 		}else{
 		$lognames =	get_filenames('./application/logs/');
 		$lognames = array_diff($lognames, array("index.html"));
 		$this->view_data['logs'] = "";
 		foreach ($lognames as $value) {
 			$this->view_data['logs'] .= read_file('./application/logs/'.$value);
 		}
 		$this->view_data['logs'] = explode("\n", $this->view_data['logs']);
 		$this->view_data['logs'] = array_diff($this->view_data['logs'], array("<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>", ""));
 		rsort($this->view_data['logs']);
 		$this->view_data['settings'] = Setting::first();
		$this->view_data['form_action'] = 'settings/logs';
		$this->content_view = 'settings/logs';
 		}
	}
	function update_download($update = FALSE){

		if($update){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'http://fc2.luxsys-apps.com/updates/files/'.$update);

		$fp = fopen('./files/updates/'.$update, 'w+');
		curl_setopt($ch, CURLOPT_FILE, $fp);

		curl_exec ($ch);

		curl_close ($ch);
		fclose($fp);
			
		}
		redirect('settings/updates');
	}
	function update_install($file = FALSE, $version = FALSE){
		$this->load->helper('unzip');
		if(!unzip("files/updates/".$file, "", true, true)){
			$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_install_update_error'));
		}else{
			$_POST['version'] = $version;
			$migration = str_replace('.', '-', $version);
			if (file_exists("application/migrations/".$migration.".php"))
			{
				$this->load->dbforge();
				include("application/migrations/".$migration.".php");
			}
			$settings = Setting::first();
			$settings->update_attributes($_POST);
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_install_update_success'));
		}
		redirect('settings/updates');
	}
	function update_man(){
		$this->load->helper('file');
		$settings = Setting::first();
		$_POST['version'] = read_file('application/version.txt');
		if($_POST['version'] > $settings->version){
		$update = str_replace('.', '-', $_POST['version']);
		if (file_exists("application/migrations/".$update.".php"))
			{
				$this->load->dbforge();
				include("application/migrations/".$update.".php");
			}
			$settings->update_attributes($_POST);
			$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_install_update_success'));
		}
			redirect('settings/updates');
	}
	function mysql_backup(){
		$this->load->helper('file');
		$this->load->dbutil();
		$prefs = array('format' => 'zip', 'filename' => 'Database-full-backup_'.date('Y-m-d_H-i'));

		$backup =& $this->dbutil->backup($prefs); 

		if ( ! write_file('./files/backup/Database-full-backup_'.date('Y-m-d_H-i').'.zip', $backup))
			{
			    $this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_create_backup_error'));
			}
			else
			{ 
				$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_create_backup_success')); 
			}
 		
 		redirect('settings/backup');
	}
	function mysql_download($file){
		$this->load->helper('file');

		$this->load->helper('download');
		$data = file_get_contents('./files/backup/'.$file);
		force_download($file, $data);
 		
 		redirect('settings/backup');
	}
	function mysql_restore(){
		if($_POST){
		$this->load->helper('file');
		$this->load->helper('unzip');
		$this->load->database();

					$config['upload_path'] = './files/temp/';
					$config['allowed_types'] = 'zip';
					$config['max_size']	= '9000';

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload())
						{
							$error = $this->upload->display_errors('', ' ');
							$this->session->set_flashdata('message', 'error:'.$error);
							redirect('settings/updates');
						}
						else
						{
							$data = array('upload_data' => $this->upload->data());
							$backup = "files/temp/".$data['upload_data']['file_name'];
							
						}
				

			if(!unzip($backup, "files/temp/", true, true)){
				$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_restore_backup_error'));
			}else{
				$this->load->dbforge();
				$backup = str_replace('.zip', '', $backup);
				$file_content =  file_get_contents($backup.".sql");
			 	$this->db->query('USE '.$this->db->database.';');
			 	foreach (explode(";\n", $file_content) as $sql) 
	       {
	         $sql = trim($sql);
	           if($sql) 
	               {
	                $this->db->query($sql);
	               } 
	      } 
			 	$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_restore_backup_success'));
			 	
	 		}
	 	unlink($backup.".sql");
		unlink($backup.".zip");
	 	redirect('settings/updates');
		}else{
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_upload_backup');
			$this->view_data['form_action'] = 'settings/mysql_restore';
			$this->content_view = 'settings/_backup';
		}
	}

}