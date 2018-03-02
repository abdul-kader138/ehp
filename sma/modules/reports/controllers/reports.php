<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MX_Controller
{

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
    | MODULE: 			REPORTS
    | -----------------------------------------------------
    | This is reports module controller file.
    | -----------------------------------------------------
    */


    function __construct()
    {
        parent::__construct();

        // check if user logged in
        if (!$this->ion_auth->logged_in()) {
            redirect('module=auth&view=login');
        }

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


        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['buildings'] = $this->reports_model->getAllBuildingForInvoice();
        $meta['page_title'] = $this->lang->line("sale_reports");
        $data['page_title'] = $this->lang->line("sale_reports");
        $this->load->view('commons/header', $meta);
        $this->load->view('invoice_view', $data);
        $this->load->view('commons/footer');

    }




    function invoice_view_details()
    {


        if ($this->input->get('sDate')) {
            $startDate = $this->input->get('sDate');
            $sale_start_date=$this->input->get('sDate');
            $startDate = $this->ion_auth->fsd($startDate);
        } else {
            $startDate = NULL;
        }
        if ($this->input->get('eDate')) {
            $endDate = $this->input->get('eDate');
            $sale_end_date=$this->input->get('eDate');
            $endDate = $this->ion_auth->fsd($endDate);
        } else {
            $endDate = NULL;
        }
        if ($this->input->get('eBuildingCode')) {
            $eBuildingCode = $this->input->get('eBuildingCode');
        } else {
            $eBuildingCode = NULL;
        }


        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
//        $data['rows'] = $this->reports_model->getAllInvoiceItemsWithDetails($startDate, $endDate);
//        $inv = $this->reports_model->getInvoiceBySaleID($startDate, $endDate);
//        $data['inv'] = $inv;
//        $data['sale_start_date'] = $sale_start_date;
//        $data['sale_end_date'] = $sale_end_date;
//        $data['inv'] = $inv;


        $data['page_title'] = $this->lang->line("invoice");
//        $data['sid'] = $sale_id;


        $this->load->view('invoice_view_details', $data);

    }


}

