<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Level extends MX_Controller
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
        $this->load->model('level_model');
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
            ->select("level_code,level_name,room_code")
            ->from("level")
            ->add_column("Actions",
//                "<center><a href='index.php?module=level&amp;view=edit&amp;name=$2' class='tip' title='" . $this->lang->line("edit_level") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=level&amp;view=delete&amp;name=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_level') . "')\" class='tip' title='" . $this->lang->line("delete_level") . "'><i class=\"icon-remove\"></i></a></center>", "level_code,level_name");
                "<center><a href='index.php?module=level&amp;view=delete&amp;name=$2&amp;room_name=$3' onClick=\"return confirm('" . $this->lang->line('alert_x_level') . "')\" class='tip' title='" . $this->lang->line("delete_level") . "'><i class=\"icon-remove\"></i></a></center>", "level_name,level_code,room_code");

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
        $this->form_validation->set_rules('code', $this->lang->line("level_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("level_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('room_names[]', $this->lang->line("room_name"), 'required');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
            $room_names = $this->input->post('room_names');

        }

//        if($this->form_validation->run() == true && $this->level_model->getLevelByName(trim($name))){
        $data = array();
        if ($this->form_validation->run() == true) {
            $isExists = false;
            foreach ($room_names as $room_name) {


                $isExists = $this->level_model->getLevelByName(trim($name), trim($room_name));
                if ($isExists) {
                    $this->session->set_flashdata('message', "Level and Apartment(". $room_name.") is already exists");
                    redirect("module=level", 'refresh');
                } else {
                    $data[] = array('level_code' => $code,
                        'level_name' => $name,
                        'room_code' => $room_name,
                        'created_by' => USER_NAME,
                        'created_date' => date('Y-m-d H:i:s')
                    );
                }
            }

        }


//
        if ($this->form_validation->run() == true && $this->level_model->addLevel($data)) { //check to see if we are creating the customer
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


            $meta['page_title'] = $this->lang->line("new_level");
            $data['page_title'] = $this->lang->line("new_level");
            $data['rnumber'] = $this->level_model->getRQNextAI();
            $data['rooms'] = $this->level_model->getAllRooms();
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

        //validate form input
        $this->form_validation->set_rules('level_code', $this->lang->line("level_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('level_code', $this->lang->line("level_name"), 'required|min_length[3]|xss_clean');
        $this->form_validation->set_rules('room_name', $this->lang->line("room_name"), 'required|min_length[3]|xss_clean');


        if ($this->form_validation->run() == true) {

            $data = array('code' => $this->input->post('level_code'),
                'name' => $this->input->post('level_name'),
                'room_code' => $this->input->post('room_name')
            );
        }

        if ($this->form_validation->run() == true && $this->level_model->updateLevel($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_updated"));
            redirect("module=level", 'refresh');
        } else { //display the update form
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
            $data['rooms'] = $this->level_model->getAllRooms();
            $data['name'] = $name;
            $data['page_title'] = $this->lang->line("edit_level");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');

        }
    }

    function delete($name = NULL,$room_name=NULL)
    {

        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }
        if ($this->input->get('room_name')) {
            $room_name = $this->input->get('room_name');
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
        if ($this->level_model->deleteLevel($name,$room_name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("level_deleted"));
            redirect("module=level", 'refresh');
        }

    }


}

/* End of file level.php */
/* Location: ./sma/modules/level/controllers/level.php */