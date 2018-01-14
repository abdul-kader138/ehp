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
            ->from("inspection")
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
        if ($this->inspection_model->getInspectionByName(trim($name))) {
            $this->session->set_flashdata('message', $this->lang->line("category_name_exist"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=inspection', 'refresh');
        }

        if ($this->form_validation->run() == true) {
            $inspection_category_data = array();
            if ($this->form_validation->run() == true) {
                $inspection_category_data[] = array('category_code' => $code,
                    'category_name' => $name,
                    'isTaggedWithBuilding' => 'No',
                    'created_by' => USER_NAME,
                    'created_date' => date('Y-m-d H:i:s')
                );

            }
        }


//
        if ($this->form_validation->run() == true && $this->inspection_model->addLevel($inspection_category_data)) { //check to see if we are creating the customer
//            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_added"));
            redirect("module=level", 'refresh');
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

}