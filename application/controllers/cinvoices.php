<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cInvoices extends MY_Controller {
               
	function __construct()
	{
		parent::__construct();
		$access = FALSE;
		if($this->client){	
			foreach ($this->view_data['menu'] as $key => $value) { 
				if($value->link == "cinvoices"){ $access = TRUE;}
			}
			if(!$access){redirect('login');}
		}elseif($this->user){
				redirect('invoices');
		}else{

			redirect('login');
		}
		$this->view_data['submenu'] = array(
				 		$this->lang->line('application_all_invoices') => 'cinvoices',
				 		);	
		
	}	
	function index()
	{
		$this->view_data['invoices'] = Invoice::find('all',array('conditions' => array('company_id=? AND issue_date<=?',$this->client->company->id,date('Y-m-d', time()))));
		$this->content_view = 'invoices/client_views/all';
	}

	function view($id = FALSE)
	{
		$this->view_data['submenu'] = array(
						$this->lang->line('application_back') => 'invoices',
				 		);	
		$this->view_data['invoice'] = Invoice::find($id);
		$this->view_data['items'] = InvoiceHasItem::find('all',array('conditions' => array('invoice_id=?',$id)));
		if($this->view_data['invoice']->company_id != $this->client->company->id){ redirect('cinvoices');}
		$this->content_view = 'invoices/client_views/view';
	}
	function download($id = FALSE){
     $this->load->helper(array('dompdf', 'file')); 
     $this->load->library('parser');
     $data["invoice"] = Invoice::find($id); 
     $data['items'] = InvoiceHasItem::find('all',array('conditions' => array('invoice_id=?',$id)));
     if($data['invoice']->company_id != $this->client->company->id){ redirect('cinvoices');}
     $data["core_settings"] = Setting::first(); 
     $due_date = date($data["core_settings"]->date_format, human_to_unix($data["invoice"]->due_date.' 00:00:00'));  
     $parse_data = array(
            					'due_date' => $due_date,
            					'invoice_id' => $data["invoice"]->reference,
            					'client_link' => $data["core_settings"]->domain,
            					'company' => $data["core_settings"]->company,
            					); 
     $html = $this->load->view($data["core_settings"]->template. '/' .'invoices/preview', $data, true);
     $html = $this->parser->parse_string($html, $parse_data); 
     $filename = 'Invoice_'.$data["invoice"]->reference;
     pdf_create($html, $filename, TRUE);
       
	}
		function success($id = FALSE){
		$this->session->set_flashdata('message', 'success:'.$this->lang->line('messages_payment_success'));
		redirect('cinvoices/view/'.$id);
	}

	
	
}