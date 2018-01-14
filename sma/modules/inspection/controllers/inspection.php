<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inspection extends MX_Controller
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
    | MODULE: 			inspection
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
        $this->load->model('inspection_model');
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

    }


    function deficiency_category()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_category");
        $data['page_title'] = $this->lang->line("list_category");
        $this->load->view('commons/header', $meta);
        $this->load->view('deficiency_category', $data);
        $this->load->view('commons/footer');
    }


    function getDataTableAjaxForInspectionCategory()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("category_code,category_name")
            ->from("deficiency_category")
            ->add_column("Actions",
                "<center><a href='index.php?module=inspection&amp;view=edit_deficiency_category&amp;name=$1' class='tip' title='" . $this->lang->line("edit_deficiency_category") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=inspection&amp;view=delete_deficiency_category&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_category') . "')\" class='tip' title='" . $this->lang->line("delete_deficiency_category") . "'><i class=\"icon-remove\"></i></a></center>", "category_code");
        echo $this->datatables->generate();

    }


    function add_deficiency_category()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("category_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("category_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');

        }
        if ($this->inspection_model->getDeficiencyCategoryBName(trim($name))) {
            $this->session->set_flashdata('message', $this->lang->line("category_name_exist"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=inspection', 'refresh');
        }

        if ($this->form_validation->run() == true) {
            $inspection_category_data = array();
            if ($this->form_validation->run() == true) {
                $inspection_category_data = array('category_code' => $code,
                    'category_name' => $name,
                    'created_by' => USER_NAME,
                    'created_date' => date('Y-m-d H:i:s')
                );

            }
        }


//
        if ($this->form_validation->run() == true && $this->inspection_model->addDeficiencyCategory($inspection_category_data)) { //check to see if we are creating the customer
//            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_category_added"));
            redirect("module=inspection&view=deficiency_category", 'refresh');
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


            $meta['page_title'] = $this->lang->line("add_deficiency_category");
            $data['page_title'] = $this->lang->line("add_deficiency_category");
            $data['rnumber'] = $this->inspection_model->getRQNextAI();
            $this->load->view('commons/header', $meta);
            $this->load->view('add_deficiency_category', $data);
            $this->load->view('commons/footer');

        }
    }


    function edit_deficiency_category($name = NULL)

    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        //validate form input
        $this->form_validation->set_rules('category_code', $this->lang->line("category_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('category_name', $this->lang->line("category_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {
            $category_code = $this->input->post('category_code');
            $category_name = $this->input->post('category_name');

        }

        if ($this->form_validation->run() == true) {

            $data = array('category_code' => $category_code,
                'category_name' => $category_name,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }

        if ($this->form_validation->run() == true && $this->inspection_model->updateDeficiencyCategory($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_category_updated"));
            redirect("module=inspection&view=deficiency_category", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $data['deficiency'] = $this->inspection_model->getDeficiencyCategoryByCode($name);
            $data['name'] = $name;
            $meta['page_title'] = $this->lang->line("edit_deficiency_category");
            $data['page_title'] = $this->lang->line("edit_deficiency_category");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit_deficiency_category', $data);
            $this->load->view('commons/footer');

        }
    }


    function delete_deficiency_category($name = NULL)
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
        if ($this->inspection_model->deleteDeficiencyCategory($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_category_deleted"));
            redirect("module=inspection&view=deficiency_category", 'refresh');
        }

    }



    function deficiency_details()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_details");
        $data['page_title'] = $this->lang->line("list_details");
        $this->load->view('commons/header', $meta);
        $this->load->view('deficiency_details', $data);
        $this->load->view('commons/footer');
    }

    function getDataTableAjaxForInspectionDetails()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("deficiency_details.details_code as code,deficiency_details.details_name,deficiency_category.category_name,deficiency_details.details_comment")
            ->from("deficiency_details")
            ->join("deficiency_category" ,'deficiency_details.category_code = deficiency_category.category_code', 'left')
            ->add_column("Actions",
                "<center><a href='index.php?module=inspection&amp;view=edit_deficiency_details&amp;name=$1' class='tip' title='" . $this->lang->line("edit_deficiency_details") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=inspection&amp;view=delete_deficiency_details&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_details') . "')\" class='tip' title='" . $this->lang->line("delete_deficiency_details") . "'><i class=\"icon-remove\"></i></a></center>", "code");
        echo $this->datatables->generate();

    }


    function add_deficiency_details()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('details_code', $this->lang->line("details_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('details_comment', $this->lang->line("details_comment"), 'trim|xss_clean');
        $this->form_validation->set_rules('details_name', $this->lang->line("details_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('category_code', $this->lang->line("category_name"), 'required|xss_clean');
        if ($this->form_validation->run() == true) {
            $details_code = $this->input->post('details_code');
            $details_comment = $this->input->post('details_comment');
            $details_name = $this->input->post('details_name');
            $category_code = $this->input->post('category_code');

        }

        if ($this->inspection_model->getDeficiencyDetailsByName(trim($details_name))) {
            $this->session->set_flashdata('message', $this->lang->line("details_name_exist"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=inspection', 'refresh');
        }

        if ($this->form_validation->run() == true) {
            $inspection_details_data = array();
            if ($this->form_validation->run() == true) {
                $inspection_details_data = array(
                    'details_code' => $details_code,
                    'details_comment' => $details_comment,
                    'details_name' => $details_name,
                    'category_code' => $category_code,
                    'created_by' => USER_NAME,
                    'created_date' => date('Y-m-d H:i:s')
                );

            }
        }



//
        if ($this->form_validation->run() == true && $this->inspection_model->addDeficiencyDetails($inspection_details_data)) { //check to see if we are creating the customer
//            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_details_added"));
            redirect("module=inspection&view=deficiency_details", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $meta['page_title'] = $this->lang->line("add_deficiency_details");
            $data['page_title'] = $this->lang->line("add_deficiency_details");
            $data['rnumber'] = $this->inspection_model->getRQNextAIForDetails();
            $data['categories'] = $this->inspection_model->getAllCategories();
            $this->load->view('commons/header', $meta);
            $this->load->view('add_deficiency_details', $data);
            $this->load->view('commons/footer');

        }
    }



    function edit_deficiency_details($name = NULL)

    {
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        //validate form input
        $this->form_validation->set_rules('details_code', $this->lang->line("details_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('details_comment', $this->lang->line("details_comment"), 'trim|xss_clean');
        $this->form_validation->set_rules('details_name', $this->lang->line("details_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('category_code', $this->lang->line("category_name"), 'required|xss_clean');
        if ($this->form_validation->run() == true) {
            $details_code = $this->input->post('details_code');
            $details_comment = $this->input->post('details_comment');
            $details_name = $this->input->post('details_name');
            $category_code = $this->input->post('category_code');

        }


        if ($this->form_validation->run() == true) {

            $data = array(
                'details_code' => $details_code,
                'details_comment' => $details_comment,
                'details_name' => $details_name,
                'category_code' => $category_code,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }

        if ($this->form_validation->run() == true && $this->inspection_model->updateDeficiencyDetails($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_details_updated"));
            redirect("module=inspection&view=deficiency_details", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['categories'] = $this->inspection_model->getAllCategories();
            $data['details'] = $this->inspection_model->getDeficiencyDetailsByCode($name);
            $data['name'] = $name;
            $meta['page_title'] = $this->lang->line("edit_deficiency_details");
            $data['page_title'] = $this->lang->line("edit_deficiency_details");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit_deficiency_details', $data);
            $this->load->view('commons/footer');

        }
    }


    function delete_deficiency_details($name = NULL)
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
        if ($this->inspection_model->deleteDeficiencydetails($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_details_deleted"));
            redirect("module=inspection&view=deficiency_details", 'refresh');
        }

    }



}