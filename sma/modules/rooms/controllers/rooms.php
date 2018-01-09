<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rooms extends MX_Controller {

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
        $this->load->model('rooms_model');
        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups))
        {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=rooms', 'refresh');
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

        $meta['page_title'] = $this->lang->line("list_room");
        $data['page_title'] = $this->lang->line("list_room");
        $this->load->view('commons/header', $meta);
        $this->load->view('rooms', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("room_code,room_name,total_bed_qty,room_rent")
            ->from("rooms")
            ->add_column("Actions",
                "<center><a href='index.php?module=rooms&amp;view=edit&amp;name=$2' class='tip' title='".$this->lang->line("edit_room")."'><i class=\"icon-edit\"></i></a> <a href='index.php?module=rooms&amp;view=delete&amp;name=$2' onClick=\"return confirm('". $this->lang->line('alert_x_room') ."')\" class='tip' title='".$this->lang->line("delete_room")."'><i class=\"icon-remove\"></i></a></center>", "room_code,room_name");

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
        $this->form_validation->set_rules('code', $this->lang->line("room_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("room_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('room_rent', $this->lang->line("room_rent"), 'required|xss_clean');
        $this->form_validation->set_rules('vacant_date', $this->lang->line("vacant_date"), 'xss_clean');

        if ($this->form_validation->run() == true)
        {

           $data=array(
               'room_name' => $this->input->post('name'),
               'room_code' => $this->input->post('code'),
               'total_bed_qty' => 1,
               'room_rent' => $this->input->post('room_rent'),
               'bed_occupied'=>0,
               'created_by'=>USER_NAME,
               'created_date'=>date('Y-m-d H:i:s'));
        }
        if($this->form_validation->run() == true && $this->rooms_model->getRoomByName(trim($this->lang->line("room_name")))){
            $this->session->set_flashdata('message', $this->lang->line("room_name_exist"));
            redirect("module=rooms", 'refresh');
        }


        if ( $this->form_validation->run() == true && $this->rooms_model->addRoom($data))
        { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("room_added"));
            redirect("module=rooms", 'refresh');
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


            $meta['page_title'] = $this->lang->line("new_room");
            $data['page_title'] = $this->lang->line("new_room");
            $data['rnumber'] = $this->rooms_model->getRQNextAI();
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
        $this->form_validation->set_rules('room_code', $this->lang->line("room_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('room_name', $this->lang->line("room_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('room_rent', $this->lang->line("room_rent"), 'required|xss_clean');
        $this->form_validation->set_rules('vacant_date', $this->lang->line("vacant_date"), 'xss_clean');

        if ($this->form_validation->run() == true)
        {

            $data = array('room_code' => $this->input->post('room_code'),
                'room_name' => $this->input->post('room_name'),
                'total_bed_qty' => $this->input->post('total_bed_qty'),
                'room_rent' => $this->input->post('room_rent'),
                'vacant_date' =>$this->ion_auth->fsd($this->input->post('vacant_date')),
                'updated_by'=>USER_NAME,
                'updated_date'=>date('Y-m-d H:i:s')
            );
        }

        if ( $this->form_validation->run() == true && $this->rooms_model->updateRoom($name, $data))
        { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("room_updated"));
            redirect("module=rooms", 'refresh');
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


            $data['room'] = $this->rooms_model->getRoomByName($name);

            $meta['page_title'] = $this->lang->line("edit_room");
            $data['name'] = $name;
            $data['page_title'] = $this->lang->line("edit_room");
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
        if ( $this->rooms_model->deleteLevel($name) )
        { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("room_deleted"));
            redirect("module=rooms", 'refresh');
        }

    }




}

/* End of file level.php */
/* Location: ./sma/modules/level/controllers/level.php */