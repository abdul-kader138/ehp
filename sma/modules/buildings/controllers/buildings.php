<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buildings extends MX_Controller
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
    | MODULE: 			level
    | -----------------------------------------------------
    | This is level module controller file.
    | -----------------------------------------------------
    */


    function __construct()
    {
        parent::__construct();

        // check if user logged in
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->model('buildings_model');
        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=level', 'refresh');
        }

    }

    function index()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_buildings");
        $data['page_title'] = $this->lang->line("list_buildings");
        $this->load->view('commons/header', $meta);
        $this->load->view('buildings', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("building_code,building_name,location,CASE WHEN hasMedicalSupport = '1' THEN 'Yes' ELSE 'No' END AS hasMedicalSupport1,CASE WHEN hasHandicapAccess = '1' THEN 'Yes' ELSE 'No' END AS hasHandicapAccess1,CASE WHEN isSmokeFreeZone = '1' THEN 'Yes' ELSE 'No' END AS isSmokeFreeZone1,CASE WHEN hasElevatorSupport = '1' THEN 'Yes' ELSE 'No' END AS hasElevatorSupport1", FALSE)
            ->from("building")
            ->add_column("Actions",
                "<center><a href='index.php?module=buildings&amp;view=edit&amp;name=$2' class='tip' title='" . $this->lang->line("edit_buildings") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=buildings&amp;view=delete&amp;name=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_buildings') . "')\" class='tip' title='" . $this->lang->line("delete_buildings") . "'><i class=\"icon-remove\"></i></a></center>", "building_code,building_name");

        echo $this->datatables->generate();

    }

    function add()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("buildings_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("buildings_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('location', $this->lang->line("buildings_name"), 'required|max_length[200]|xss_clean');
        $this->form_validation->set_rules('hasMedicalSupport', 'hasMedicalSupport', 'required|xss_clean');
        $this->form_validation->set_rules('hasHandicapAccess', 'hasHandicapAccess', 'required|xss_clean');
        $this->form_validation->set_rules('isSmokeFreeZone', 'isSmokeFreeZone', 'required|xss_clean');
        $this->form_validation->set_rules('hasElevatorSupport', 'hasElevatorSupport', 'required|xss_clean');

        if ($this->form_validation->run() == true) {

            $name = strtolower($this->input->post('name'));
            $data = array(
                'building_name' => strtolower($this->input->post('name')),
                'building_code' => $this->input->post('code'),
                'location' => $this->input->post('location'),
                'hasMedicalSupport' => $this->input->post('hasMedicalSupport'),
                'hasHandicapAccess' => $this->input->post('hasHandicapAccess'),
                'isSmokeFreeZone' => $this->input->post('isSmokeFreeZone'),
                'hasElevatorSupport' => $this->input->post('hasElevatorSupport'),
                'isTaggedWithVendor' => 'No',
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s'));

        }

        if ($this->form_validation->run() == true && $this->buildings_model->getBuildingsByName(trim($name))) {
            $this->session->set_flashdata('message', $this->lang->line("buildings_name_exist"));
            redirect("module=buildings", 'refresh');
        }


        if ($this->form_validation->run() == true && $this->buildings_model->addBuilding($data)) { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("buildings_added"));
            redirect("module=buildings", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('code'),
            );

            $meta['page_title'] = $this->lang->line("new_buildings");
            $data['page_title'] = $this->lang->line("new_buildings");
            $data['rnumber'] = $this->buildings_model->getRQNextAI();
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');

        }
    }

    function edit($name = NULL)

    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        $this->form_validation->set_rules('buildings_code', $this->lang->line("buildings_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('buildings_name', $this->lang->line("buildings_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('location', $this->lang->line("buildings_name"), 'required|max_length[200]|xss_clean');
        $this->form_validation->set_rules('hasMedicalSupport', 'hasMedicalSupport', 'required|xss_clean');
        $this->form_validation->set_rules('hasHandicapAccess', 'hasHandicapAccess', 'required|xss_clean');
        $this->form_validation->set_rules('isSmokeFreeZone', 'isSmokeFreeZone', 'required|xss_clean');
        $this->form_validation->set_rules('hasElevatorSupport', 'hasElevatorSupport', 'required|xss_clean');

        if ($this->form_validation->run() == true) {

            $obj = array(
                'building_name' => strtolower($this->input->post('buildings_name')),
                'building_code' => $this->input->post('buildings_code'),
                'location' => $this->input->post('location'),
                'hasMedicalSupport' => $this->input->post('hasMedicalSupport'),
                'hasHandicapAccess' => $this->input->post('hasHandicapAccess'),
                'isSmokeFreeZone' => $this->input->post('isSmokeFreeZone'),
                'hasElevatorSupport' => $this->input->post('hasElevatorSupport'),
                'isTaggedWithVendor' => 'No',
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s'));

        }


        if ($this->form_validation->run() == true && $this->buildings_model->updateBuildings($name, $obj)) {
            $this->session->set_flashdata('success_message', $this->lang->line("buildings_updated"));
            redirect("module=buildings", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('code'),
            );


            $data['buildings'] = $this->buildings_model->getBuildingsByName($name);

            $meta['page_title'] = $this->lang->line("edit_buildings");
            $data['name'] = $name;
            $data['page_title'] = $this->lang->line("edit_buildings");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');

        }
    }

    function delete($name = NULL)
    {

        if ($this->input->get('name')) {
            $name = $this->input->get('name');
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
        if ($this->buildings_model->deleteBuildings($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("buildings_deleted"));
            redirect("module=buildings", 'refresh');
        }

    }


    function getBuildings()
    {

        if ($rows = $this->buildings_model->getAllBuildings()) {
            $ct[""] = '';
            foreach ($rows as $building) {
                $ct[$building->name] = $building->code;
            }
            $data = form_dropdown('building', $ct, '', 'class="span4" id="building" data-placeholder="Select Building"');
        } else {
            $data = "";
        }
        echo $data;
    }

    function add_building_details()
    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }



        $this->form_validation->set_rules('level_name', $this->lang->line("level_name"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('buildings_name', $this->lang->line("buildings_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('total_room_qty', $this->lang->line("total_room_qty"), 'required|max_length[200]|xss_clean');
        $this->form_validation->set_rules('total_bed_qty', 'total_bed_qty', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            $object = array(
                'building_name' => strtolower($this->input->post('buildings_name')),
                'level_name' => $this->input->post('level_name'),
                'no_of_room' => $this->input->post('total_room_qty'),
                'no_of_bed' => $this->input->post('total_bed_qty'),
                'total_occupied_bed' => 0,
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s'));
        }

        if ($this->form_validation->run() == true && $this->buildings_model->addBuildingDetails($object)){
            $this->session->set_flashdata('success_message', $this->lang->line("level_added_buildings"));
            redirect('module=buildings&view=building_details', 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['level_name'] = array('name' => 'total_bed_qty',
                'id' => 'total_bed_qty',
                'type' => 'text',
                'value' => $this->form_validation->set_value('total_bed_qty'),
            );
            $data['name'] = array('name' => 'total_bed_qty',
                'id' => 'total_bed_qty',
                'type' => 'text',
                'value' => $this->form_validation->set_value('total_bed_qty'),
            );

            $data['buildings'] = $this->buildings_model->getAllBuildings();
            $data['levels'] = $this->buildings_model->getAllLevels();
            $meta['page_title'] = $this->lang->line("new_level_buildings");
            $data['page_title'] = $this->lang->line("new_level_buildings");
            $this->load->view('commons/header', $meta);
            $this->load->view('add_building_details', $data);
            $this->load->view('commons/footer');
        }

    }


    function building_details()
    {


        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_level_buildings");
        $data['page_title'] = $this->lang->line("list_level_buildings");
        $this->load->view('commons/header', $meta);
        $this->load->view('building_details', $data);
        $this->load->view('commons/footer');
    }

    function getDataTableAjaxForDetails()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id,building_name,level_name,no_of_room,no_of_bed,total_occupied_bed")
            ->from("building_details")
            ->add_column("Actions",
                "<center><a href='index.php?module=buildings&amp;view=edit_building_details&amp;id=$1' class='tip' title='" . $this->lang->line("edit_level_buildings") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=buildings&amp;view=delete_building_details&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_level_buildings') . "')\" class='tip' title='" . $this->lang->line("delete_level_buildings") . "'><i class=\"icon-remove\"></i></a></center>", "id")
            ->unset_column('id');

        echo $this->datatables->generate();

    }


    function edit_building_details($id = NULL)

    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('level_name', $this->lang->line("level_name"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('buildings_name', $this->lang->line("buildings_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('total_room_qty', $this->lang->line("total_room_qty"), 'required|max_length[200]|xss_clean');
        $this->form_validation->set_rules('total_bed_qty', 'total_bed_qty', 'required|xss_clean');

        if ($this->form_validation->run() == true) {
            $object = array(
                'building_name' => strtolower($this->input->post('buildings_name')),
                'level_name' => $this->input->post('level_name'),
                'no_of_room' => $this->input->post('total_room_qty'),
                'no_of_bed' => $this->input->post('total_bed_qty'),
                'total_occupied_bed' => 0,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s'));
        }

        if ($this->form_validation->run() == true && $this->buildings_model->updateBuildingDetails($id, $object)) {
            $this->session->set_flashdata('success_message', $this->lang->line("level_updated_buildings"));
            redirect("module=buildings&view=building_details", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('code'),
            );

            $data['buildings'] = $this->buildings_model->getAllBuildings();
            $data['levels'] = $this->buildings_model->getAllLevels();
            $data['building_details'] = $this->buildings_model->getBuildingDetailsById($id);

            $meta['page_title'] = $this->lang->line("edit_level_buildings");
            $data['id'] = $id;
            $data['page_title'] = $this->lang->line("edit_level_buildings");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit_building_details', $data);
            $this->load->view('commons/footer');

        }
    }

    function delete_building_details($id = NULL)
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
        if ($this->buildings_model->deleteBuildingDetails($id)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("delete_level_buildings"));
            redirect("module=buildings&view=building_details", 'refresh');
        }

    }

}

/* End of file level.php */
/* Location: ./sma/modules/buildings/controllers/buildings.php */