<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MX_Controller {

/*
| -----------------------------------------------------
| PRODUCT NAME: 	STOCK MANAGER ADVANCE 
| -----------------------------------------------------
| AUTHER:			MIAN SALEEM 
| -----------------------------------------------------
| EMAIL:			saleem@tecdiary.com 
| -----------------------------------------------------
| COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
| -----------------------------------------------------
| WEBSITE:			http://tecdiary.net
| -----------------------------------------------------
|
| MODULE: 			SETTINGS
| -----------------------------------------------------
| This is setting module controller file.
| -----------------------------------------------------
*/

	 
	function __construct()
	{
		parent::__construct();
		
		// check if user logged in 
		if (!$this->ion_auth->logged_in())
	  	{
			redirect('module=auth&view=login');
	  	}
		
		$groups = array('admin', 'purchaser', 'salesman');
		if ($this->ion_auth->in_group($groups))
		{
			$this->session->set_flashdata('message', $this->lang->line('access_denied'));
			$data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
			redirect('module=home', 'refresh');
		}
		
		$this->load->library('form_validation');
		$this->load->model('settings_model');
		$this->load->model('inventories/inventories_model');


	}
	
	function index()
   {
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
      $meta['page_title'] = $this->lang->line('setting');
	  $data['page_title'] = $this->lang->line('setting');
      $this->load->view('commons/header', $meta);
      $this->load->view('content', $data);
      $this->load->view('commons/footer');
	  
   }
   
   function system_setting()
   {
	   

		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('site_name', $this->lang->line('site_name'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('language', $this->lang->line('language'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('warehouse', $this->lang->line('default_warehouse'), 'trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('currency_prefix', $this->lang->line('currency_code'), 'trim|required|max_length[3]|xss_clean');
		$this->form_validation->set_rules('tax_rate', $this->lang->line('product_tax'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('tax_rate2', $this->lang->line('invoice_tax'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('date_format', $this->lang->line('date_format'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('sales_prefix', $this->lang->line('sales_prefix'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('quote_prefix', $this->lang->line('quote_prefix'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('purchase_prefix', $this->lang->line('purchase_prefix'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('transfer_prefix', $this->lang->line('transfer_prefix'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('barcode_symbology', $this->lang->line('barcode_symbology'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('theme', $this->lang->line('theme'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('rows_per_page', $this->lang->line('rows_per_page'), 'trim|required|greater_than[9]|less_than[501]|xss_clean');
		$this->form_validation->set_rules('total_rows', $this->lang->line('total_rows'), 'trim|required|greater_than[9]|less_than[100]|xss_clean');
		$this->form_validation->set_rules('product_serial', $this->lang->line('product_serial'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('discount_option', $this->lang->line('discount_option'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('discount_method', $this->lang->line('discount_method'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('default_discount', $this->lang->line('default_discount'), 'trim|required|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
		$language = $this->input->post('language');
			
		if((file_exists('sma/language/'.$language.'/sma_lang.php') && is_dir('sma/language/'.$language)) || $language == 'en'){ 
			$lang = $language;
		} else {
			$this->session->set_flashdata('message', $this->lang->line('language_x_found'));
			redirect("module=settings&view=system_setting", 'refresh');
			$lang = 'en';
		}
		
		if($this->input->post('tax_rate') != 0) { $tax1 = 1; } else { $tax1 = 0; }
		if($this->input->post('tax_rate2') != 0) { $tax2 = 1; } else { $tax2 = 0; }
		
			$data = array('site_name' => $this->input->post('site_name'),
				'language' => $lang,
				'default_warehouse' => $this->input->post('warehouse'),
				'currency_prefix' => $this->input->post('currency_prefix'),
				'default_tax_rate' => $this->input->post('tax_rate'),
				'default_tax_rate2' => $this->input->post('tax_rate2'),
				'dateformat' => $this->input->post('date_format'),
				'sales_prefix' => $this->input->post('sales_prefix'),
				'quote_prefix' => $this->input->post('quote_prefix'),
				'purchase_prefix' => $this->input->post('purchase_prefix'),
				'transfer_prefix' => $this->input->post('transfer_prefix'),
				'barcode_symbology' => trim($this->input->post('barcode_symbology')),
				'theme' => trim($this->input->post('theme')),
				'rows_per_page' => $this->input->post('rows_per_page'),
				'total_rows' => $this->input->post('total_rows'),
				'product_serial' => $this->input->post('product_serial'),
				'discount_option' => $this->input->post('discount_option'),
				'discount_method' => $this->input->post('discount_method'),
				'default_discount' => $this->input->post('default_discount'),
				'tax1' => $tax1,
				'tax2' => $tax2,
                            'restrict_sale' => $this->input->post('restrict_sale'),
                            'restrict_calendar' => $this->input->post('restrict_calendar'),
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateSetting($data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('setting_updated'));
			redirect("module=settings&view=system_setting", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
	   $data['settings'] = $this->settings_model->getSettings();
	   $data['tax_rates'] = $this->settings_model->getAllTaxRates();
	   $data['discounts'] = $this->settings_model->getAllDiscounts();
	   $data['warehouses'] = $this->settings_model->getAllWarehouses(); 
	   $data['date_formats'] = $this->settings_model->getDateFormats();
	   
      $meta['page_title'] = $this->lang->line('system_setting');
	  $data['page_title'] = $this->lang->line('system_setting');
      $this->load->view('commons/header', $meta);
      $this->load->view('setting', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function tax_rates()
   {
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
	   $data['tax_rates'] = $this->settings_model->getAllTaxRates(); 
	   
      $meta['page_title'] = $this->lang->line('tax_rates');
	  $data['page_title'] = $this->lang->line('tax_rates');
      $this->load->view('commons/header', $meta);
      $this->load->view('tax_rates', $data);
      $this->load->view('commons/footer');
	  
   }
   
   function add_tax_rate()
   {
	 
		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('rate', $this->lang->line('rate'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			
			$data = array('name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'type' => $this->input->post('type')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->addTaxRate($data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('tax_rate_added'));
			redirect("module=settings&view=tax_rates", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
      $meta['page_title'] = $this->lang->line('new_tax_rate');
	  $data['page_title'] = $this->lang->line('new_tax_rate');
      $this->load->view('commons/header', $meta);
      $this->load->view('add_tax_rate', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function edit_tax_rate($id = NULL)
   {
	   if($this->input->get('id')){ $id = $this->input->get('id'); }

		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('rate', $this->lang->line('rate'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			
			$data = array('name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'type' => $this->input->post('type')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateTaxRate($id, $data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('tax_rate_updated'));
			redirect("module=settings&view=tax_rates", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
	   $data['tax_rate'] = $this->settings_model->getTaxRateByID($id);
	   $data['id'] = $id;
	   
      $meta['page_title'] = $this->lang->line('update_tax_rate');
	  $data['page_title'] = $this->lang->line('update_tax_rate');
      $this->load->view('commons/header', $meta);
      $this->load->view('edit_tax_rate', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function invoice_types() {
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
	   $data['invoice_types'] = $this->settings_model->getAllInvoiceTypes(); 
	   
      $meta['page_title'] = $this->lang->line('invoice_types');
	  $data['page_title'] = $this->lang->line('invoice_types');
      $this->load->view('commons/header', $meta);
      $this->load->view('invoice_types', $data);
      $this->load->view('commons/footer');
   }
   
   function add_invoice_type() {
	   
		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$type =$this->input->post('type');
			switch ($type) {
					case 1:
						$type=  "real";
						break;
					case 2:
						$type=  "draft";
						break;
			}
			
			$data = array('name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'type' => $type
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->addInvoiceType($data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('invoice_type_added'));
			redirect("module=settings&view=invoice_types", 'refresh');
		}
		else
		{
			
	  $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
      $meta['page_title'] = $this->lang->line('new_invoice_type');
	  $data['page_title'] = $this->lang->line('new_invoice_type');
      $this->load->view('commons/header', $meta);
      $this->load->view('add_invoice_type', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function edit_invoice_type($id = NULL) 
   {
	
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		   
		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$type =$this->input->post('type');
			switch ($type) {
					case 1:
						$type=  "real";
						break;
					case 2:
						$type=  "draft";
						break;
			}
			
			$data = array('name' => $this->input->post('name'),
				'rate' => $this->input->post('rate'),
				'type' => $type
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateInvoiceType($id, $data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('invoice_type_updated'));
			redirect("module=settings&view=invoice_types", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
	   $data['invoice_type'] = $this->settings_model->getInvoiceTypeByID($id);
	   $data['id'] = $id;
      $meta['page_title'] = $this->lang->line('update_invoice_type');
	  $data['page_title'] = $this->lang->line('update_invoice_type');
      $this->load->view('commons/header', $meta);
      $this->load->view('edit_invoice_type', $data);
      $this->load->view('commons/footer');
	}
   }
	
   function change_logo()
   {
   
	   $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
		
		if ($this->form_validation->run() == true)
		{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($_FILES['userfile']['size'] > 0){
				
		$this->load->library('upload_photo');
		
		$config['upload_path'] = 'assets/img/';
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size'] = '300';
		$config['max_width'] = '200';
		$config['max_height'] = '40';
		$config['overwrite'] = FALSE; 
		
			$this->upload_photo->initialize($config);
			
			if( ! $this->upload_photo->do_upload()){
			
				$error = $this->upload_photo->display_errors();
				$this->session->set_flashdata('message', $error);
				redirect("module=settings&view=change_logo", 'refresh');
			} 
		
		$photo = $this->upload_photo->file_name;
		
		} else {
			$this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
			redirect("module=settings&view=change_logo", 'refresh');	
		}
			
		
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateLogo($photo))
		{  
			$this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
			redirect("module=settings&view=change_logo", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
      $meta['page_title'] = $this->lang->line('change_logo');
	  $data['page_title'] = $this->lang->line('change_logo');
      $this->load->view('commons/header', $meta);
      $this->load->view('logo', $data);
      $this->load->view('commons/footer');
	  
   	  }
   }
   
   function change_login_logo()
   {
   
	   $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
		
		if ($this->form_validation->run() == true)
		{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($_FILES['userfile']['size'] > 0){
				
		$this->load->library('upload_photo');
		
		$config['upload_path'] = 'assets/img/'; 
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size'] = '300';
		$config['max_width'] = '300';
		$config['max_height'] = '80';
		$config['overwrite'] = FALSE; 
		
			$this->upload_photo->initialize($config);
			
			if( ! $this->upload_photo->do_upload()){
			
				$error = $this->upload_photo->display_errors();
				$this->session->set_flashdata('message', $error);
				redirect("module=settings&view=change_logo", 'refresh');
			} 
		
		$photo = $this->upload_photo->file_name;
		
		} else {
			$this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
			redirect("module=settings&view=change_logo", 'refresh');	
		}
			
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateLoginLogo($photo))
		{  
			$this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
			redirect("module=settings&view=change_logo", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
      $meta['page_title'] = $this->lang->line('change_logo');
	  $data['page_title'] = $this->lang->line('change_logo');
      $this->load->view('commons/header', $meta);
      $this->load->view('logo', $data);
      $this->load->view('commons/footer');
	  
   	  }
   }
   
   function upload_biller_logo ()
   {
	   $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
		
		if ($this->form_validation->run() == true)
		{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($_FILES['userfile']['size'] > 0){
				
		$this->load->library('upload_photo');
		
		$config['upload_path'] = 'assets/uploads/logos/'; 
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size'] = '300';
		$config['max_width'] = '300';
		$config['max_height'] = '80';
		$config['overwrite'] = FALSE; 
		
			$this->upload_photo->initialize($config);
			
			if( ! $this->upload_photo->do_upload()){
			
				$error = $this->upload_photo->display_errors();
				$this->session->set_flashdata('message', $error);
				redirect("module=settings&view=upload_biller_logo", 'refresh');
			} 
		
		$photo = $this->upload_photo->file_name;
		
		} else {
			$this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
			redirect("module=settings&view=upload_biller_logo", 'refresh');	
		}
		
		}
		
		if (!empty($photo))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('biller_logo_uploaded'));
			redirect("module=settings&view=upload_biller_logo", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	    $data['success_message'] = $this->session->flashdata('success_message');
	   
	   
      $meta['page_title'] = $this->lang->line('upload_biller_logo');
	  $data['page_title'] = $this->lang->line('upload_biller_logo');
      $this->load->view('commons/header', $meta);
      $this->load->view('upload_biller_logo', $data);
      $this->load->view('commons/footer');
	  
   	  }
   }
   
   function backup_database()
   {
		$this->load->dbutil();
		
		$prefs = array(     
                'format'      => 'zip',             
                'filename'    => 'sma_db_backup.sql'
              );


		$backup =& $this->dbutil->backup($prefs); 
		$this->dbutil->optimize_database();
		
		$db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
		$save = 'assets/DB_BACKUPS/'.$db_name;
		
		$this->load->helper('file');
		write_file($save, $backup); 
		
		
		$this->load->helper('download');
		force_download($db_name, $backup);   
   }
   
   function warehouses() {
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
	   $data['warehouses'] = $this->settings_model->getAllWarehouses(); 
	   
      $meta['page_title'] = $this->lang->line('warehouses');
	  $data['page_title'] = $this->lang->line('warehouses');
      $this->load->view('commons/header', $meta);
      $this->load->view('warehouses', $data);
      $this->load->view('commons/footer');
   }

   function add_warehouse() {
	  
		//validate form input	
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('code', $this->lang->line('warehouse_code'), 'trim|is_unique[warehouses.code]|min_length[3]|required|xss_clean');	
		$this->form_validation->set_rules('name', $this->lang->line('warehouse_name'), 'required|xss_clean');
		$this->form_validation->set_rules('address', $this->lang->line('address'), 'required|xss_clean');
		$this->form_validation->set_rules('city', $this->lang->line('city'), 'required|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$data = array('code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->addWarehouse($data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('warehouse_added'));
			redirect("module=settings&view=warehouses", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
      $meta['page_title'] = $this->lang->line('new_warehouse');
	  $data['page_title'] = $this->lang->line('new_warehouse');
      $this->load->view('commons/header', $meta);
      $this->load->view('add_warehouse', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function edit_warehouse($id = NULL) 
   {
	   
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		
		//validate form input	
		$this->form_validation->set_rules('code', $this->lang->line('warehouse_code'), 'trim|min_length[3]|required|xss_clean');
		$wh = $this->settings_model->getWarehouseByID($id);
		if($this->input->post('code') != $wh->code) {	
			$this->form_validation->set_rules('code', $this->lang->line('warehouse_code'), 'is_unique[warehouses.code]');
		}
		$this->form_validation->set_rules('name', $this->lang->line('warehouse_name'), 'required|xss_clean');
		$this->form_validation->set_rules('address', $this->lang->line('address'), 'required|xss_clean');
		$this->form_validation->set_rules('city', $this->lang->line('city'), 'required|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			$data = array('code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateWarehouse($id, $data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('warehouse_updated'));
			redirect("module=settings&view=warehouses", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
	   $data['warehouse'] = $this->settings_model->getWarehouseByID($id);
	   $data['id'] = $id;
      $meta['page_title'] = $this->lang->line('update_warehouse');
	  $data['page_title'] = $this->lang->line('update_warehouse');
      $this->load->view('commons/header', $meta);
      $this->load->view('edit_warehouse', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function delete_tax_rate($id = NULL)
	{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		
		if ( $this->settings_model->deleteTaxRate($id) )
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line("tax_rate_deleted"));
			redirect('module=settings&view=tax_rates', 'refresh');
		}
		
	}
	
	function delete_invoice_type($id = NULL)
	{
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		
		if ( $this->settings_model->deleteInvoiceType($id) )
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line("invoice_type_deleted"));
			redirect('module=settings&view=invoice_types', 'refresh');
		}
		
	}
	
	function delete_warehouse($id = NULL)
	{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		
		if ( $this->settings_model->deleteWarehouse($id) )
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line("warehouse_deleted"));
			redirect('module=settings&view=warehouses', 'refresh');
		}
		
	}
	
	function discounts()
   {
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   $data['success_message'] = $this->session->flashdata('success_message');
	   
	   $data['discounts'] = $this->settings_model->getAllDiscounts(); 
	   
      $meta['page_title'] = $this->lang->line('discounts');
	  $data['page_title'] = $this->lang->line('discounts');
      $this->load->view('commons/header', $meta);
      $this->load->view('discounts', $data);
      $this->load->view('commons/footer');
	  
   }
   
   function add_discount()
   {
	 
		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('discount', $this->lang->line('discount'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			
			$data = array('name' => $this->input->post('name'),
				'discount' => $this->input->post('discount'),
				'type' => $this->input->post('type')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->addDiscount($data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('discount_added'));
			redirect("module=settings&view=discounts", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
      $meta['page_title'] = $this->lang->line('new_discount');
	  $data['page_title'] = $this->lang->line('new_discount');
      $this->load->view('commons/header', $meta);
      $this->load->view('add_discount', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function edit_discount($id = NULL)
   {
	   if($this->input->get('id')){ $id = $this->input->get('id'); }

		//validate form input
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
		$this->form_validation->set_rules('name', $this->lang->line('title'), 'required|xss_clean');
		$this->form_validation->set_rules('discount', $this->lang->line('discount'), 'required|xss_clean');
		$this->form_validation->set_rules('type', $this->lang->line('type'), 'required|is_natural_no_zero|xss_clean');
		
		
		if ($this->form_validation->run() == true)
		{
			
			$data = array('name' => $this->input->post('name'),
				'discount' => $this->input->post('discount'),
				'type' => $this->input->post('type')
			);
		}
		
		if ( $this->form_validation->run() == true && $this->settings_model->updateDiscount($id, $data))
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line('discount_updated'));
			redirect("module=settings&view=discounts", 'refresh');
		}
		else
		{
			
	   $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	   
	   $data['discount'] = $this->settings_model->getDiscountByID($id);
	   $data['id'] = $id;
	   
      $meta['page_title'] = $this->lang->line('update_discount');
	  $data['page_title'] = $this->lang->line('update_discount');
      $this->load->view('commons/header', $meta);
      $this->load->view('edit_discount', $data);
      $this->load->view('commons/footer');
	}
   }
   
   function delete_discount($id = NULL)
	{
			if(DEMO) { 
				$this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
				redirect("module=home", 'refresh');
			}
			
		if($this->input->get('id')){ $id = $this->input->get('id'); }
		
		if ( $this->settings_model->deleteDiscount($id) )
		{ 
			$this->session->set_flashdata('success_message', $this->lang->line("discount_deleted"));
			redirect('module=settings&view=discounts', 'refresh');
		}
		
	}


//    abdul Kader

    function package() {

        if (!$this->ion_auth->in_group('owner'))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=settings', 'refresh');
        }

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

//        $data['packages'] = $this->settings_model->getPackageDataTableAjax();

        $meta['page_title'] = "Packages";
        $data['page_title'] = "Packages";
        $this->load->view('commons/header', $meta);
        $this->load->view('package', $data);
        $this->load->view('commons/footer');
    }


    function delete_package($name = NULL)
    {


        if($this->input->get('name')) { $name = $this->input->get('name'); }
        if (!$this->ion_auth->in_group('owner'))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=settings&view=package', 'refresh');
        }

        if($this->settings_model->getProductByPackageName(trim($name))) {
            $this->session->set_flashdata('message', $this->lang->line("package_has_product"));
            redirect("module=settings&view=package", 'refresh');
        }

        if ( $this->settings_model->deletePackage(trim($name)) )
        {
            $this->session->set_flashdata('success_message', $this->lang->line("package_deleted"));
            redirect("module=settings&view=package", 'refresh');
        }

    }


//    -----------------------------------/

//Add new inventory

    function add_pck($alert = NULL)
    {

        $groups = array('salesman', 'viewer');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        //validate form input
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required|xss_clean');
        $this->form_validation->set_rules('package_name', $this->lang->line("package_name"), 'required|xss_clean');

        $quantity = "quantity";
        $product = "product";
        $item_um = "item_um";


        $item_list = array();
        if ($this->form_validation->run() == true) {
            $package_name = $this->input->post('package_name');
            $date = $this->ion_auth->fsd(trim($this->input->post('date')));

            if($this->form_validation->run() == true && $this->settings_model->getPackageByName(trim($package_name))){
                $this->session->set_flashdata('message', $this->lang->line("package_name_exist"));
                redirect("module=settings&view=package", 'refresh');
            }

            for ($i = 1; $i <= 500; $i++) {
                if ($this->input->post($quantity . $i) && $this->input->post($product . $i) && $this->input->post($item_um . $i)) {


                    $product_details = $this->inventories_model->getProductByCode($this->input->post($product . $i));
                    $product_id[] = $product_details->id;
                    $product_name[] = $product_details->name;
                    $product_code[] = $product_details->code;
                    $product_um[] = $this->input->post($item_um . $i);
                    $pck_name[]=$package_name;
                    $user_name[]=USER_ID;
                    $inv_quantity[] = $this->input->post($quantity . $i);


                }
            }



            $keys = array("product_id", "product_code", "product_name", "package_name", "product_qty", "product_um","created_by");


            foreach (array_map(null,$product_id, $product_code, $product_name, $pck_name,$inv_quantity, $product_um,$user_name) as $key => $value) {
                $item_list[] = array_combine($keys, $value);
            }


        }


        if ($this->form_validation->run() == true && $this->settings_model->addPackage($item_list)) {
            $this->session->set_flashdata('success_message', $this->lang->line("package_added"));
            redirect("module=settings&view=package", 'refresh');

        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['reference_no'] = array('name' => 'reference_no',
                'id' => 'reference_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reference_no'),
            );
            $data['date'] = array('name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date'),
            );
            $data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('note'),
            );

            if ($this->input->get('alert')) {
                $alert = $this->input->get('alert');
            }

            if (isset($alert) && $alert != '') {

                $data['inv_products'] = $this->inventories_model->getAllInventoryIAlerttems();

            }
            $data['rnumber'] = $this->inventories_model->getRQNextAI();
            $meta['page_title'] = $this->lang->line("add_package");
            $data['page_title'] = $this->lang->line("add_package");
            $this->load->view('commons/header', $meta);
            $this->load->view('add_pck', $data);
            $this->load->view('commons/footer');

        }
    }


    function getPackageDataTableAjax()
    {

        if ($this->input->get('search_term')) {
            $search_term = $this->input->get('search_term');
        } else {
            $search_term = false;
        }
        $this->load->library('datatables');

        $this->datatables
            ->select("package_name as name,count(product_qty) as qty", FALSE)
            ->from('item_package')
            ->group_by('item_package.package_name');


        $this->datatables->add_column("Actions",
            "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=settings&amp;view=view_package&amp;name=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\" title='" . $this->lang->line("view_package") . "' class='tip'><i class='icon-fullscreen'></i>
			  &nbsp;<a href='index.php?module=settings&amp;view=delete_package&amp;name=$1' title='Delete Package' class='tip'><i class='icon-trash'></i></a>&nbsp; </center>", "name");

        echo $this->datatables->generate();

    }


    function view_package($name = NULL)
    {
        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

        $pck = $this->settings_model->getPackageInfoByName($name);
        $data['pck'] = $pck;
        $data['page_title'] = "View Package Details";

        $this->load->view('view_package', $data);

    }


}