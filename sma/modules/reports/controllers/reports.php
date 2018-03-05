<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MX_Controller
{

    /*
 | -----------------------------------------------------
     | PRODUCT NAME: 	EHP
 | -----------------------------------------------------
     | AUTHER:			Abdul Kader
 | -----------------------------------------------------
     | EMAIL:			babu313136@gmail.com
     | -----------------------------------------------------
     | COPYRIGHTS:		RESERVED BY One Click SOLUTIONS
 | -----------------------------------------------------
     | WEBSITE:
     | -----------------------------------------------------
     |
     | MODULE: 			Report
 | -----------------------------------------------------
     | This is level module model file.
     | -----------------------------------------------------
     */

    function __construct()
    {
        parent::__construct();

        // check if user logged in
        if (!$this->ion_auth->logged_in()) {
            redirect('module=auth&view=login');
        }
        $this->load->library('form_validation');
        $this->load->model('reports_model');

    }


    function building_facilities()
    {
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['buildings'] = $this->reports_model->getAllBuildings();
        $meta['page_title'] = $this->lang->line("building_facilities_reports");
        $data['page_title'] = $this->lang->line("building_facilities_reports");
        $this->load->view('commons/header', $meta);
        $this->load->view('building_facilities', $data);
        $this->load->view('commons/footer');
    }


    function getBuildings()
    {
        if ($this->input->get('building_facilities')) {
            $building_facilities = $this->input->get('building_facilities');
        } else {
            $building_facilities = NULL;
        }
        if ($this->input->get('building_code')) {
            $building_code = $this->input->get('building_code');
        } else {
            $building_code = NULL;
        }

        $this->load->library('datatables');
        $this->datatables
            ->select("building.building_code,building.building_name as name, level.level_code,rooms.room_code, building.location, DATEDIFF( CURDATE(), rooms.vacant_date ) as date", FALSE)
            ->from('building')
            ->join('building_details', 'building.building_code=building_details.building_code', 'left')
            ->join('level', 'level.level_code=building_details.level_code', 'left')
            ->join('rooms', 'rooms.room_code=level.room_code', 'left')
            ->group_by('level.room_code');

        if ($building_facilities == 'hasMedicalSupport') {
            $this->datatables->where('building.hasMedicalSupport', '1');
        }
        if ($building_facilities == 'hasHandicapAccess') {
            $this->datatables->where('building.hasHandicapAccess', '1');
        }
        if ($building_facilities == 'isSmokeFreeZone') {
            $this->datatables->where('building.isSmokeFreeZone', '1');
        }
        if ($building_facilities == 'hasElevatorSupport') {
            $this->datatables->where('building.hasElevatorSupport', '1');
        }
        if ($building_code) {
            $this->datatables->where('building.building_code', $building_code);
        }
//        $this->datatables->where('building.isTaggedWithVendor', 'Yes');
//        $this->datatables->where('rooms.vacant_date IS NOT NULL');
        echo $this->datatables->generate();
    }

    function invoice_view()
    {

//        var_dump("test");

        $this->form_validation->set_rules('start_date', $this->input->get('start_date'), 'required|xss_clean');
        $this->form_validation->set_rules('end_date', $this->input->get('end_date'), 'required|xss_clean');
        $this->form_validation->set_rules('building_code', $this->input->get('building_code'), 'required|xss_clean');


        if ($this->form_validation->run() == true) {
            if ($this->input->get('start_date')) {
                $startDate = $this->input->get('start_date');
                $startDate = $this->ion_auth->fsd($startDate);
            } else {
                $startDate = NULL;
            }
            if ($this->input->get('end_date')) {
                $endDate = $this->input->get('end_date');
                $endDate = $this->ion_auth->fsd($endDate);
            } else {
                $endDate = NULL;
            }
            if ($this->input->get('building_code')) {
                $eBuildingCode = $this->input->get('building_code');
            } else {
                $eBuildingCode = NULL;
            }

        }


        $valid_invoice = $this->reports_model->getValidInvoiceItemsWithDetails($startDate, $endDate, $eBuildingCode);

        if (!$valid_invoice) { //check to see if we are creating the customer
            $this->session->set_flashdata('message', 'No Valid info are available for creating invoice');
            redirect("module=home", 'refresh');
        }


        if ($this->form_validation->run() == true) {
            //         $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            //         $data['rows'] = $this->reports_model->getAllInvoiceItemsWithDetails($startDate, $endDate, $eBuildingCode);
//            $reference_no = $this->reports_model->getRQNextInvoice();
            $inv_data = array(
                'reference_no' => $reference_no,
                'Vendor_name' => $valid_invoice[0]->c_name,
                'building_name' => $valid_invoice[0]->building_name,
                'building_location' => $valid_invoice[0]->location,
                'inv_val' => $valid_invoice[0]->rents,
                'inv_start_date' => $startDate,
                'inv_end_date' => $endDate,
                'date' => date('Y-m-d'),
                'created_by' => USER_NAME,
                'created_on' => date('Y-m-d H:i:s'));

            if ($this->reports_model->add_invoice($inv_data)) {
                $this->session->set_flashdata('success_message', "Invoice Successfully created.");
                redirect("module=reports&view=invoice_list", 'refresh');
            }

        } else {

            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['buildings'] = $this->reports_model->getAllBuildingForInvoice();
            $meta['page_title'] = $this->lang->line("invoice");
            $data['page_title'] = $this->lang->line("invoice");
            $this->load->view('commons/header', $meta);
            $this->load->view('invoice_view', $data);
            $this->load->view('commons/footer');
        }


    }


    function invoice_view_details()
    {
//        if ($this->input->get('sDate')) {
//            $startDate = $this->input->get('sDate');
//            $startDate = $this->ion_auth->fsd($startDate);
//        } else {
//            $startDate = NULL;
//        }
//        if ($this->input->get('eDate')) {
//            $endDate = $this->input->get('eDate');
//            $endDate = $this->ion_auth->fsd($endDate);
//        } else {
//            $endDate = NULL;
//        }
//        if ($this->input->get('eID')) {
//            $eBuildingCode = $this->input->get('eID');
//        } else {
//            $eBuildingCode = NULL;
//        }
//
//
//        $valid_invoice=$this->reports_model->getValidInvoiceItemsWithDetails($startDate, $endDate,$eBuildingCode);
////
//        if (!$valid_invoice) { //check to see if we are creating the customer
//            $this->session->set_flashdata('message', 'No Valid info are available for creating invoice');
//            redirect("module=home", 'refresh');
//        }
//
//
//
//        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
//        $data['rows'] = $this->reports_model->getAllInvoiceItemsWithDetails($startDate, $endDate,$eBuildingCode);
//        $reference_no = $this->reports_model->getRQNextInvoice();
//        $inv_data = array(
//            'reference_no' => $reference_no,
//            'Vendor_name' =>  $valid_invoice[0]->c_name,
//            'building_name' => $valid_invoice[0]->building_name,
//            'building_location' =>  $valid_invoice[0]->location,
//            'inv_val' => $valid_invoice[0]->rents,
//            'inv_start_date' => $startDate,
//            'inv_end_date' =>$endDate,
//            'date' => date('Y-m-d'),
//            'created_by' => USER_NAME,
//            'created_on' => date('Y-m-d H:i:s'));
////
////        if($this->reports_model->add_invoice($inv_data)){
////            $data['sDate'] = $this->input->get('sDate');
////            $data['eDate'] = $endDate;
////            $data['c_name'] = $valid_invoice[0]->c_name;
////            $data['page_title'] = $this->lang->line("invoice");
////            $this->load->view('invoice_view_details', $data);
////        }
//
//
//        if($this->reports_model->add_invoice($inv_data)){
//            $this->session->set_flashdata('success_message', "Invoice Successfully created.");
//            redirect("module=reports&view=invoice_list", 'refresh');
//        }
//        else{
//            $this->session->set_flashdata('message', 'No Valid info are available for creating invoice');
//            redirect("module=reports", 'refresh');
//        }


    }

    public function invoice_list()
    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("clients");
        $data['page_title'] = $this->lang->line("clients");
        $this->load->view('commons/header', $meta);
        $this->load->view('clients', $data);
        $this->load->view('commons/footer');
    }


    function getDataTableInvoiceAjax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("c.date as date,c.reference_no as doc,c.vendor_name,c.building_name,c.building_location,c.inv_val,c.date_of_birth")
            ->from("invoice c")
            ->gourp_by('c.id')
            ->add_column("Actions",
                "<center><a href='index.php?module=clients&amp;view=client_details&amp;name=$1' class='tip' title='Client Intake Details'><i class=\"icon-fullscreen\"></i></a><a href='" . $url . "$2' download class='tip' title='Doc. Download'><i class=\"icon-download-alt\"></i></a><a href='index.php?module=clients&amp;view=edit&amp;name=$1' class='tip' title='" . $this->lang->line("edit_client") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=clients&amp;view=delete&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_client') . "')\" class='tip' title='" . $this->lang->line("delete_client") . "'><i class=\"icon-remove\"></i></a></center>", "code,doc")
            ->unset_column('doc');
        echo $this->datatables->generate();

    }

}

