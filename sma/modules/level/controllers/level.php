<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Level extends MX_Controller {

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
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->model('level_model');
        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=level', 'refresh');
        }

    }

    function index()
    {

        if (!$this->ion_auth->in_group('owner'))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_level");
        $data['page_title'] = $this->lang->line("list_level");
        $this->load->view('commons/header', $meta);
        $this->load->view('levels', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("level_code,level_name")
            ->from("level")
            ->add_column("Actions",
                "<center><a href='index.php?module=level&amp;view=edit&amp;name=$2' class='tip' title='".$this->lang->line("edit_level")."'><i class=\"icon-edit\"></i></a> <a href='index.php?module=level&amp;view=delete&amp;name=$2' onClick=\"return confirm('". $this->lang->line('alert_x_level') ."')\" class='tip' title='".$this->lang->line("delete_level")."'><i class=\"icon-remove\"></i></a></center>", "level_code,level_name");

        echo $this->datatables->generate();

    }

    function add()
    {


        if (!$this->ion_auth->in_group('owner'))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("level_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("level_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true)
        {
            $name = strtolower($this->input->post('name'));
            $code = $this->input->post('code');

        }

        if($this->form_validation->run() == true && $this->level_model->getLevelByName(trim($name))){
            $this->session->set_flashdata('message', $this->lang->line("level_name_exist"));
            redirect("module=level", 'refresh');
        }


        if ( $this->form_validation->run() == true && $this->level_model->addLevel($name, $code))
        { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_added"));
            redirect("module=level", 'refresh');
        }
        else
        { //display the create customer form
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


            $meta['page_title'] = $this->lang->line("new_level");
            $data['page_title'] = $this->lang->line("new_level");
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');

        }
    }

    function edit($name = NULL)

    {
        if (!$this->ion_auth->in_group('owner'))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        if($this->input->get('name')) { $name = $this->input->get('name'); }

        //validate form input
        $this->form_validation->set_rules('level_code', $this->lang->line("level_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('level_code', $this->lang->line("level_name"), 'required|min_length[3]|xss_clean');



        if ($this->form_validation->run() == true)
        {

            $data = array('code' => $this->input->post('level_code'),
                'name' => $this->input->post('level_name')
            );
        }

        if ( $this->form_validation->run() == true && $this->level_model->updateLevel($name, $data))
        { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_updated"));
            redirect("module=level", 'refresh');
        }
        else
        { //display the update form
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


            $data['level'] = $this->level_model->getLevelByName($name);

            $meta['page_title'] = $this->lang->line("edit_level");
            $data['name'] = $name;
            $data['page_title'] = $this->lang->line("edit_level");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');

        }
    }

    function delete($name = NULL)
    {

        if($this->input->get('name')) { $name = $this->input->get('name'); }
        if (!$this->ion_auth->in_group('owner'))
        {
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
        if ( $this->level_model->deleteLevel($name) )
        { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_deleted"));
            redirect("module=level", 'refresh');
        }

    }




}

/* End of file level.php */
/* Location: ./sma/modules/level/controllers/level.php */