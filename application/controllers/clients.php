<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){	
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "clients"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}else{
			redirect('login');
		}
		
	}	
	function index()
	{
		$this->view_data['companies'] = Company::find('all',array('conditions' => array('inactive=?','0')));
		$this->content_view = 'clients/all';
	}
	function create($company_id = FALSE)
	{	
		if($_POST){
			$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}else{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload. "){
								$this->session->set_flashdata('message', 'error:'.$error);
								redirect('clients');
							}
						}

			unset($_POST['send']);
			unset($_POST['userfile']);
			unset($_POST['file-name']);
			if(isset($_POST["access"])){ $_POST["access"] = implode(",", $_POST["access"]); }else{unset($_POST["access"]);}
			$_POST = array_map('htmlspecialchars', $_POST);
			$_POST["company_id"] = $company_id;
			$client = Client::create($_POST);
       		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_client_add_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_client_add_success'));
       		$company = Company::find($company_id);
       			if(!isset($company->client->id)){
       				$client = Client::last();
       				$company->update_attributes(array('client_id' => $client->id));
       			}
       		}
			redirect('clients/view/'.$company_id);
		}else
		{
			$this->view_data['clients'] = Client::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type = ?', 'client')));
			$this->view_data['next_reference'] = Client::last();
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_add_new_contact');
			$this->view_data['form_action'] = 'clients/create/'.$company_id;
			$this->content_view = 'clients/_clients';
		}	
	}	
	function update($id = FALSE, $getview = FALSE)
	{	
		if($_POST){
					$config['upload_path'] = './files/media/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_width'] = '180';
					$config['max_height'] = '180';

					$this->load->library('upload', $config);

					if ( $this->upload->do_upload())
						{
							$data = array('upload_data' => $this->upload->data());

							$_POST['userpic'] = $data['upload_data']['file_name'];
						}else{
							$error = $this->upload->display_errors('', ' ');
							if($error != "You did not select a file to upload. "){
								$this->session->set_flashdata('message', 'error:'.$error);
								redirect('clients');
							}
						}

			unset($_POST['send']);
			unset($_POST['userfile']);
			unset($_POST['file-name']);
			if(empty($_POST["password"])){unset($_POST['password']);}
			if(!empty($_POST["access"])){$_POST["access"] = implode(",", $_POST["access"]);}
			$id = $_POST['id'];
			if(isset($_POST['view'])){
				$view = $_POST['view'];
				unset($_POST['view']);
			}
			$_POST = array_map('htmlspecialchars', $_POST);
			$client = Client::find($id);
			$client->update_attributes($_POST);
       		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_client_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_client_success'));}
			redirect('clients/view/'.$client->company->id);
			
		}else
		{
			$this->view_data['client'] = Client::find($id);
			$this->view_data['modules'] = Module::find('all', array('order' => 'sort asc', 'conditions' => array('type = ?', 'client')));
			if($getview == "view"){$this->view_data['view'] = "true";}
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_client');
			$this->view_data['form_action'] = 'clients/update';
			$this->content_view = 'clients/_clients';
		}	
	}
	function notes($id = FALSE)
	{	
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$project = Company::find($id);
			$project->update_attributes($_POST);
		}
		$this->theme_view = 'ajax';
	}	
	function company($condition = FALSE, $id = FALSE)
	{	
		switch ($condition) {
			case 'create':
			if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$company = Company::create($_POST);
			$companyid = Company::last();
			$new_company_reference = $_POST['reference']+1;
			$company_reference = Setting::first();
			$company_reference->update_attributes(array('company_reference' => $new_company_reference));
       		if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_company_add_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_company_add_success'));}
			redirect('clients/view/'.$companyid->id);
		}else
		{
			$this->view_data['clients'] = Company::find('all',array('conditions' => array('inactive=?','0')));
			$this->view_data['next_reference'] = Company::last();
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_add_new_company');
			$this->view_data['form_action'] = 'clients/company/create';
			$this->content_view = 'clients/_company';
		}	
			break;
			case 'update':
			if($_POST){
			unset($_POST['send']);
			$id = $_POST['id'];
			if(isset($_POST['view'])){
				$view = $_POST['view'];
				unset($_POST['view']);
			}
			$_POST = array_map('htmlspecialchars', $_POST);
			$company = Company::find($id);
			$company->update_attributes($_POST);
       		if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_company_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_company_success'));}
			redirect('clients/view/'.$id);
			
		}else
		{
			$this->view_data['company'] = Company::find($id);
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_company');
			$this->view_data['form_action'] = 'clients/company/update';
			$this->content_view = 'clients/_company';
		}	
				break;
				case 'delete':
				$company = Company::find($id);
				$company->inactive = '1';
				$company->save();
				foreach ($company->clients as $value) {
				$client = Client::find($value->id);
				$client->inactive = '1';
				$client->save();
				}
				$this->content_view = 'clients/all';
				if(!$company){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_company_error'));}
		       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_company_success'));}
					redirect('clients');
				break;

		}
		
	}	
	function delete($id = FALSE)
	{	
		$client = Client::find($id);
		$client->inactive = '1';
		$client->save();
		$this->content_view = 'clients/all';
		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_client_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_client_success'));}
			redirect('clients');
	}	
	function view($id = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'clients',
				 		
				 		);	
		$this->view_data['company'] = Company::find($id);
		$this->content_view = 'clients/view';
	}
	function credentials($id = FALSE, $email = FALSE)
	{
		if($email){
			$this->load->helper('file');
			$client = Client::find($id);
			$setting = Setting::first();
			$this->email->from($setting->email, $setting->company);
			$this->email->to($client->email); 
			$this->email->subject($setting->credentials_mail_subject);
			$this->load->library('parser');
			$parse_data = array(
            					'client_contact' => $client->firstname.' '.$client->lastname,
            					'client_link' => $setting->domain,
            					'company' => $setting->company,
            					'username' => $client->email,
            					'password' => $client->password,
            					'logo' => '<img src="'.base_url().''.$setting->logo.'" alt="'.$setting->company.'"/>',
            					'invoice_logo' => '<img src="'.base_url().''.$setting->invoice_logo.'" alt="'.$setting->company.'"/>'
            					);
			
			$message = read_file('./application/views/'.$setting->template.'/templates/email_credentials.html');
  			$message = $this->parser->parse_string($message, $parse_data);
			$this->email->message($message);
			if($this->email->send()){$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_send_login_details_success'));}
       		else{$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_send_login_details_error'));}
			redirect('clients/view/'.$client->company_id);

		} else {
		$this->view_data['client'] = Client::find($id);
		$this->theme_view = 'modal';
		$this->view_data['title'] = $this->lang->line('application_login_details');
		$this->view_data['form_action'] = 'clients/credentials';
		$this->content_view = 'clients/_credentials';
		}
	}
}