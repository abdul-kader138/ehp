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

        $this->form_validation->set_rules('start_date', $this->input->post('start_date'), 'required|xss_clean');
        $this->form_validation->set_rules('end_date', $this->input->post('end_date'), 'required|xss_clean');
        $this->form_validation->set_rules('building_code', $this->input->post('building_code'), 'required|xss_clean');


        if ($this->form_validation->run() == true) {
            $startDate1 = $this->input->post('start_date');
            $startDate = $this->ion_auth->fsd($startDate1);
            $endDate1 = $this->input->post('end_date');
            $endDate = $this->ion_auth->fsd($endDate1);
            $eBuildingCode = $this->input->post('building_code');


            $valid_invoice = $this->reports_model->getValidInvoiceItemsWithDetails($startDate, $endDate, $eBuildingCode);


            if (!$valid_invoice) { //check to see if we are creating the customer
                $this->session->set_flashdata('message', 'No Valid info are available for creating invoice');
                redirect("module=home", 'refresh');
            }

            $data['rows'] = $this->reports_model->getAllInvoiceItemsWithDetails($startDate, $endDate, $eBuildingCode);
            $reference_no = $this->reports_model->getRQNextInvoice();
            $inv_data = array(
                'reference_no' => $reference_no,
                'vendor_name' => $valid_invoice[0]->c_name,
                'building_name' => $valid_invoice[0]->building_name,
                'building_location' => $valid_invoice[0]->location,
                'inv_val' => $valid_invoice[0]->rents,
                'inv_start_date' => $startDate,
                'inv_end_date' => $endDate,
                'date' => date('Y-m-d'),
                'created_by' => USER_NAME,
                'created_on' => date('Y-m-d H:i:s'));
            for ($i = 0; $i < count($data['rows']); $i++) {
                $reference_no1[] = $reference_no;
                $vendor_name[] = $data['rows'][$i]->name;
                $ssn[] = $data['rows'][$i]->ssn;
                $room_name[] = $data['rows'][$i]->room_name;
                $building_name[] = $data['rows'][$i]->building_name;
                $building_code[] = $data['rows'][$i]->building_code;
                $location[] = $data['rows'][$i]->location;
                $room_rent[] = $data['rows'][$i]->room_rent;
                $move_in_date[] = $data['rows'][$i]->move_in_date;
                $move_out_date[] = $data['rows'][$i]->move_out_date;
            }
            $keys = array("reference_no", "vendor_name", "ssn", "room_name", "building_name", "building_code", "location", "room_rent", "move_in_date", 'move_out_date');
//
            $items = array();
            foreach (array_map(null, $reference_no1, $vendor_name, $ssn, $room_name, $building_name, $building_code, $location, $room_rent, $move_in_date, $move_out_date) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }
        }
//        if ($this->form_validation->run() == true ) {
        if ($this->form_validation->run() == true && $this->reports_model->add_invoice($inv_data, $items)) {
            $this->session->set_flashdata('success_message', "Invoice Successfully created.");
//            var_dump($data['rows'] );
            redirect("module=reports&view=invoice_list", 'refresh');
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
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }
//
        $inv = $this->reports_model->getValidInvoiceById($id);
        $inv_details = $this->reports_model->getValidInvoiceItemsWithDetailsById($id);
        $data['inv'] = $inv;
        $data['rows'] = $inv_details;
        $data['page_title'] = $this->lang->line("invoice");
        $this->load->view('invoice_view_details', $data);

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

        $meta['page_title'] = $this->lang->line("invoice_list");
        $data['page_title'] = $this->lang->line("invoice_list");
        $this->load->view('commons/header', $meta);
        $this->load->view('invoice_list', $data);
        $this->load->view('commons/footer');
    }


    function getDataTableInvoiceAjax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("c.id as id,c.date,c.reference_no as doc,c.vendor_name,c.building_name,c.building_location,c.inv_start_date,c.inv_end_date,c.inv_val", false)
            ->from("invoice c")
//            ->gourp_by('c.id')
            ->add_column("Actions",
                "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=reports&view=invoice_view_details&id=$1', 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\" title='Print Invoice' class='tip'><i class='icon-fullscreen'></i></a>
                <a href='index.php?module=reports&amp;view=delete&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_invoice') . "')\" class='tip' title='" . $this->lang->line("delete_invoice") . "'><i class=\"icon-remove\"></i></a></center>", "id")
            ->unset_column('id');
        echo $this->datatables->generate();

    }


    function delete($id = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

//        @todo need to implement later
//
//        if($this->level->getRackByShelfID($id)) {
//            $this->session->set_flashdata('message', $this->lang->line("Shelf Has Rack"));
//            redirect("module=shelfs", 'refresh');
//        }
        if ($this->reports_model->deleteInvoice($id)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("invoice_deleted"));
            redirect("module=reports&view=invoice_list", 'refresh');
        }

    }
}

