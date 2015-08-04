<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotations extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->input->cookie('language') != ""){ $language = $this->input->cookie('language');}else{ $language = "english";}
		$this->lang->load('quotation', $language);
		if($this->client){	
			redirect('cprojects');
		}elseif($this->user){
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "quotations"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}

		}else{
			redirect('login');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_all') => 'quotations',
				 		$this->lang->line('application_New') => 'quotations/filter/new',
				 		$this->lang->line('application_Reviewed') => 'quotations/filter/reviewed',
				 		$this->lang->line('application_Accepted') => 'quotations/filter/accepted'
				 		);	
		$this->view_data['submenu2'] = array(
				 		$this->lang->line('application_all') => 'quotations',
				 		$this->lang->line('application_New') => 'quotations/customfilter/new',
				 		$this->lang->line('application_Reviewed') => 'quotations/customfilter/reviewed',
				 		$this->lang->line('application_Accepted') => 'quotations/customfilter/accepted'
				 		);	
		
	}	
	function index()
	{
		$this->view_data['quotations'] = Quote::all();
		$this->view_data['custom_quotations'] = Quoterequest::all();
		$this->content_view = 'quotations/all';
	}
	function filter($condition)
	{
		$this->view_data['custom_quotations'] = Quoterequest::all();
		switch ($condition) {
			case 'new':
				$this->view_data['quotations'] = Quote::find('all', array('conditions' => array('status = ?', 'New')));
				break;
			case 'reviewed':
				$this->view_data['quotations'] = Quote::find('all', array('conditions' => array('status = ?', 'reviewed')));
				break;
			case 'accepted':
				$this->view_data['quotations'] = Quote::find('all', array('conditions' => array('status = ?', 'accepted')));
				break;
			default:
				$this->view_data['quotations'] = Quote::all();
				break;
		}
		
		$this->content_view = 'quotations/all';
	}
	function customfilter($condition)
	{
		$this->view_data['quotations'] = Quote::all();
		switch ($condition) {
			case 'new':

								$this->view_data['custom_quotations'] = Quoterequest::find('all', array('conditions' => array('status = ?', 'New')));
				break;
			case 'reviewed':
				$this->view_data['custom_quotations'] = Quoterequest::find('all', array('conditions' => array('status = ?', 'reviewed')));
				break;
			case 'accepted':
				$this->view_data['custom_quotations'] = Quoterequest::find('all', array('conditions' => array('status = ?', 'accepted')));
				break;
			default:
				$this->view_data['custom_quotations'] = Quoterequest::all();
				break;
		}
		
		$this->content_view = 'quotations/all';
	}
	function custom()
	{
		$this->view_data['quotations'] = Quoterequest::all();
		$this->content_view = 'quotations/custom_all';
	}
	function quoteforms()
	{
		$this->view_data['quotations'] = Customquote::all();
		$this->content_view = 'quotations/customquote_form_all';
	}
	function cview($id = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'quotations',
				 		);	
		$this->view_data['quotation'] = Quoterequest::find($id);

		
		//$this->view_data['client'] = Company::find('all',array('conditions' => array('inactive=? AND name=?','0', $this->view_data['quotation']->company)));
		$this->content_view = 'quotations/custom_view';
	}
	function view($id = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'quotations',
				 		);	
		$this->view_data['quotation'] = Quote::find($id);
		$this->view_data['client'] = Company::find('all',array('conditions' => array('inactive=? AND name=?','0', $this->view_data['quotation']->company)));
		$this->content_view = 'quotations/view';
	}

	function create_client($id = FALSE){
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$client = Company::create($_POST);
			$new_client_reference = $_POST['reference']+1;
			$client_reference = Setting::first();
			$client_reference->update_attributes(array('company_reference' => $new_client_reference));
       		if(!$client){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_company_add_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_company_add_success'));}
			redirect('clients');
		}else
		{

			$this->view_data['client'] = Quote::find($id);
			$next_reference = Company::last();
			$reference = $next_reference->reference+1;
			$this->view_data['client_reference'] = $reference;
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_add_new_company');
			$this->view_data['form_action'] = 'quotations/create_client';
			$this->content_view = 'quotations/_clients';
		}	
		
	}
	function update($id = FALSE){
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$quotation = Quote::find($id);
			$quotation = $quotation->update_attributes($_POST);
       		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_quotation_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_quotation_success'));}
			redirect('quotations/view/'.$id);
			
		}else
		{
			$this->view_data['quotations'] = Quote::find($id);
			$this->view_data['users'] = user::find('all',array('conditions' => array('status=?','active')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_quotation');
			$this->view_data['form_action'] = 'quotations/update/'.$id;
			$this->content_view = 'quotations/_quotations';
		}	
	}
	function formupdate($id = FALSE){
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$quotation = Customquote::find($id);
			$quotation = $quotation->update_attributes($_POST);
       		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_quotation_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_quotation_success'));}
			redirect('quotations/quoteforms');
			
		}else
		{
			$this->view_data['quotation'] = Customquote::find($id);
			$this->view_data['users'] = user::find('all',array('conditions' => array('status=?','active')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_quotation');
			$this->view_data['form_action'] = 'quotations/formupdate/'.$id;
			$this->content_view = 'quotations/_formupdate';
		}	
	}
	function cupdate($id = FALSE){
		if($_POST){
			unset($_POST['send']);
			$_POST = array_map('htmlspecialchars', $_POST);
			$quotation = Quoterequest::find_by_id($id);
			$quotation = $quotation->update_attributes($_POST);
       		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_save_quotation_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_save_quotation_success'));}
			redirect('quotations/cview/'.$id);
			
		}else
		{
			$this->view_data['quotations'] = Quoterequest::find_by_id($id);
			$this->view_data['users'] = user::find('all',array('conditions' => array('status=?','active')));
			$this->theme_view = 'modal';
			$this->view_data['title'] = $this->lang->line('application_edit_quotation');
			$this->view_data['form_action'] = 'quotations/cupdate/'.$id;
			$this->content_view = 'quotations/_quotations';
		}	
	}
	function delete($id = FALSE){
		$quotation = Quote::find_by_id($id);
		$quotation->delete();
		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_quotation_error'));}
       	else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_quotation_success'));}
		redirect('quotations');
	}
	function cdelete($id = FALSE){
		$quotation = Quoterequest::find_by_id($id);
		$quotation->delete();
		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_quotation_error'));}
       	else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_quotation_success'));}
		redirect('quotations');
	}
	function formdelete($id = FALSE){
		$quotation = Customquote::find_by_id($id);
		$quotation->delete();
		if(!$quotation){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_delete_quotation_error'));}
       	else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_delete_quotation_success'));}
		redirect('quotations/quoteforms');
	}

	function formbuilder($id = FALSE)
	{
		if($id != FALSE){
			$this->view_data['quotation'] = Customquote::find_by_id($id); 
		}
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'quotations',
				 		);	
		$this->content_view = 'quotations/formbuilder';
	}
	
	function build($id = FALSE)
	{

		if($_POST){


			unset($_POST['send']);
			$_POST["user_id"] = $this->user->id; 
			if($id != FALSE){
				$quote = Customquote::find_by_id($id);
				$quote = $quote->update_attributes($_POST);
				if(!$quote){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_quotation_form_update_error'));}
       		else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_quotation_form_update_success'));}
			}else{
				$quote = Customquote::create($_POST);
				if(!$quote){$this->session->set_flashdata('message', 'error:'.$this->lang->line('messages_quotation_form_add_error'));}
       			else{$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_quotation_form_add_success'));}
       		}
       		
       		redirect('quotations/quoteforms');
		}
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'quotations',
				 		);	
		$this->content_view = 'quotations/formbuilder';
	}
}