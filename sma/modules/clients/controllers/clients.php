<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends MX_Controller
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
    | This is client module controller file.
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
        $this->load->model('clients_model');


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

        $meta['page_title'] = $this->lang->line("clients");
        $data['page_title'] = $this->lang->line("clients");
        $this->load->view('commons/header', $meta);
        $this->load->view('clients', $data);
        $this->load->view('commons/footer');

    }

    function types()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_type");
        $data['page_title'] = $this->lang->line("list_type");
        $this->load->view('commons/header', $meta);
        $this->load->view('types', $data);
        $this->load->view('commons/footer');
    }

    function intake_list()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_intake");
        $data['page_title'] = $this->lang->line("list_intake");
        $this->load->view('commons/header', $meta);
        $this->load->view('intake_list', $data);
        $this->load->view('commons/footer');
    }


    function getDataTableClientAjax()
    {

        $url = base_url("/assets/uploads/client_docs/");
        $this->load->library('datatables');
        $this->datatables
            ->select("c.code as code,c.doc as doc,c.first_name,c.last_name,c.phone,c.ssn,ct.type_name,c.date_of_birth")
            ->from("clients c")
            ->join('client_type ct', 'c.client_type = ct.type_code', 'left')
            ->add_column("Actions",
                "<center><a href='index.php?module=clients&amp;view=client_details&amp;name=$1' class='tip' title='Client Intake Details'><i class=\"icon-fullscreen\"></i></a><a href='" . $url . "$2' download class='tip' title='Doc. Download'><i class=\"icon-download-alt\"></i></a><a href='index.php?module=clients&amp;view=edit&amp;name=$1' class='tip' title='" . $this->lang->line("edit_client") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=clients&amp;view=delete&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_client') . "')\" class='tip' title='" . $this->lang->line("delete_client") . "'><i class=\"icon-remove\"></i></a></center>", "code,doc")
            ->unset_column('doc');
        echo $this->datatables->generate();

    }

    function delete_intake($name = NULL)
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
        $data = $this->clients_model->getClientsByCode($name);
        //redirect them back to the admin page
        if ($this->clients_model->delete_intake($name)) { //check to see if we are deleting the customer
            unlink(realpath('assets/uploads/client_discharge_docs/' . $data->doc));
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("client_intake_deleted"));
            redirect("module=clients&view=intake_list", 'refresh');
        }
    }


    function getDataTableIntakeAjax()
    {
        $url = base_url("/assets/uploads/client_discharge_docs/");
        $url_transfer = base_url("/assets/uploads/client_transfer_docs/");
        $this->load->library('datatables');
        $this->datatables
            ->select("c.code as code,c.client_code, c.doc as doc,c.transfer_doc as t_doc,, CONCAT(cl.first_name,' ',cl.last_name) AS s_name,ct.type_name,cu.name,c.building_code,c.apartment_code,c.status,c.move_in_date,c.move_out_date, case WHEN c.move_out_date is  NULL then DATEDIFF(CURDATE(), c.move_in_date ) else DATEDIFF( c.move_out_date, c.move_in_date ) end as days", FALSE)
            ->from("client_intake c")
            ->join('clients cl', 'c.client_code = cl.code', 'left')
            ->join('client_type ct', 'c.client_type = ct.type_code', 'left')
            ->join('customers cu', 'c.vendor_code = cu.code', 'left')
            ->add_column("Actions",
                "<center><a href='" . $url . "$2' download class='tip' title='Discharge Doc. Download'><i class=\"icon-download-alt\"></i></a><a href='" . $url_transfer . "$3' download class='tip' title='Transfer Doc. Download'><i class=\"icon-download-alt\"></i></a><a href='index.php?module=clients&amp;view=client_discharge&amp;name=$1' class='tip' title='" . $this->lang->line("client_discharge") . "'><i class=\"icon-adjust\"></i></a><a href='index.php?module=clients&amp;view=client_transfer&amp;name=$1' class='tip' title='" . $this->lang->line("client_transfer") . "'><i class=\"icon-screenshot\"></i></a><a href='index.php?module=clients&amp;view=delete_intake&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_intake') . "')\" class='tip' title='" . $this->lang->line("delete_intake") . "'><i class=\"icon-remove\"></i></a></center>", "code,doc,t_doc")
            ->unset_column('code,doc,t_doc');
        echo $this->datatables->generate();

    }

    function client_intake()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('client_code', $this->lang->line("client_code"), 'required|xss_clean');
        $this->form_validation->set_rules('vendor_code', $this->lang->line("vendor_code"), 'required|xss_clean');
        $this->form_validation->set_rules('building_code', $this->lang->line("building_code"), 'required|xss_clean');
        $this->form_validation->set_rules('apartment_code', $this->lang->line("apartment_code"), 'required|xss_clean');
        $this->form_validation->set_rules('move_in_date', $this->lang->line("move_in_date"), 'required|xss_clean');
//        $this->form_validation->set_rules('move_out_date  ', $this->lang->line("move_out_date"), 'required|xss_clean');
        $this->form_validation->set_rules('code', $this->lang->line("code"), 'required|xss_clean');

        if ($this->form_validation->run() == true) {

            $mid = $this->ion_auth->fsd(trim($this->input->post('move_in_date')));
//            $client_details = $this->clients_model->getClientsByCode($this->input->post('client_code'));
            // $client_type_details = $this->clients_model->getClientTypeByCode($client_details->client_type);
            $data = array(
                'client_code' => $this->input->post('client_code'),
                'vendor_code' => $this->input->post('vendor_code'),
                'building_code' => $this->input->post('building_code'),
                'apartment_code' => $this->input->post('apartment_code'),
                'code' => $this->input->post('code'),
                'status' => 'Admitted',
                'client_type' => CLIENT_TYPE,
                'move_in_date' => $mid,
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s')
            );
        }


        if ($this->form_validation->run() == true) {
//
//

            $dateTimestamp3 = strtotime(date('Y-m-d'));
            $dateTimestamp4 = strtotime($mid);
            if ($dateTimestamp4 > $dateTimestamp3) {
                $this->session->set_flashdata('message', "Move in Date can't greater than current date.");
                redirect("module=clients&view=intake_list", 'refresh');
            }


            $isExists = $this->clients_model->getClientById(trim($this->lang->line("client_code")));

            if ($isExists) {
                $this->session->set_flashdata('message', "Client already intake");
                redirect("module=clients", 'refresh');
            }


            $isDateConflict = $this->clients_model->getAptTaggedAtDate(trim($this->input->post("apartment_code")), $mid);
            $dateTimestamp1 = strtotime($isDateConflict->move_out_date);
            $dateTimestamp2 = strtotime($mid);

            if ($dateTimestamp2 < $dateTimestamp1) {
                $this->session->set_flashdata('message', "Building already occupied for this date.");
                redirect("module=clients&view=intake_list", 'refresh');

            }

            if ($this->form_validation->run() == true && $this->clients_model->addClientIntake($data)) { //check to see if we are creating the customer
                //redirect them back to the admin page
                $this->session->set_flashdata('success_message', $this->lang->line("client_intake_added"));
                redirect("module=clients&view=intake_list", 'refresh');
            }

        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $meta['page_title'] = $this->lang->line("intake");
            $data['page_title'] = $this->lang->line("intake");
            $data['clients'] = $this->clients_model->getAllUnTaggedClients();
//            $data['types'] = $this->clients_model->getTypes();
            $data['vendors'] = $this->clients_model->getAllVendors();
            $data['rnumber'] = $this->clients_model->getRQNextAIClientIntake();
            $this->load->view('commons/header', $meta);
            $this->load->view('client_intake', $data);
            $this->load->view('commons/footer');

        }

    }


    function add()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("code"), 'xss_clean');
        $this->form_validation->set_rules('first_name', $this->lang->line("first_name"), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line("last_name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
//        $this->form_validation->set_rules('address', $this->lang->line("address"), 'xss_clean|min_length[15]|max_length[255]');
        $this->form_validation->set_rules('medicaid_no', $this->lang->line("medicaid_no"), 'xss_clean|max_length[50]');
        $this->form_validation->set_rules('ssn', $this->lang->line("ssn"), 'xss_clean|max_length[16]');
        $this->form_validation->set_rules('date_of_birth', $this->lang->line("date_of_birth"), 'required|xss_clean');
        $this->form_validation->set_rules('types', $this->lang->line("client_type"), 'xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'xss_clean|min_length[9]|max_length[16]');
        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
        $this->form_validation->set_rules('comments', $this->lang->line("comment"), 'max_length[200]|xss_clean');


        if ($_FILES['userfile']['size'] > 0) {

            $this->load->library('upload_photo');

            $config['upload_path'] = 'assets/uploads/client_docs/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|txt';
            $config['max_size'] = '2500';
            $new_name = $this->input->post('code') . $_FILES["userfiles"]['name'];
            $config['file_name'] = $new_name;
//                $config['max_width'] = '800';
//                $config['max_height'] = '600';
            $config['overwrite'] = FALSE;

            $this->upload_photo->initialize($config);

            if (!$this->upload_photo->do_upload()) {

                $error = $this->upload_photo->display_errors();
                $this->session->set_flashdata('message', $error);
                redirect("module=clients", 'refresh');
            }

            $photo = $this->upload_photo->file_name;

        }

        if ($this->form_validation->run() == true) {

            $dob = $this->ion_auth->fsd(trim($this->input->post('date_of_birth')));
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'isTaggedWithVendor' => 'No',
                'code' => $this->input->post('code'),
                'ssn' => $this->input->post('ssn'),
                'email' => $this->input->post('email'),
                'comment' => $this->input->post('comments'),
                'medicaid_no' => $this->input->post('medicaid_no'),
                'client_type' => $this->input->post('types'),
                'date_of_birth' => $dob,
                'phone' => $this->input->post('phone'),
                'doc' => $photo,
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s')
            );
        }


        if ($this->form_validation->run() == true) {
//
//
            $isExists = $this->clients_model->getClientByNameAndSSN(trim($this->lang->line("first_name")),
                trim($this->lang->line("last_name")), trim($this->lang->line("ssn")));

            if ($isExists) {
                $this->session->set_flashdata('message', "Client (" . $this->lang->line("first_name") . $this->lang->line("last_name") . ") is already exists");
                redirect("module=clients", 'refresh');
            }

            if ($this->form_validation->run() == true && $this->clients_model->addClient($data)) { //check to see if we are creating the customer
                //redirect them back to the admin page
                $this->session->set_flashdata('success_message', $this->lang->line("client_added"));
                redirect("module=clients", 'refresh');
            }

        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $meta['page_title'] = $this->lang->line("new_client");
            $data['page_title'] = $this->lang->line("new_client");
            $data['types'] = $this->clients_model->getTypes();
            $data['rnumber'] = $this->clients_model->getRQNextAIClient();
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

        $this->form_validation->set_rules('code', $this->lang->line("code"), 'xss_clean');
        $this->form_validation->set_rules('first_name', $this->lang->line("first_name"), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line("last_name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
//        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean|min_length[15]|max_length[255]');
        $this->form_validation->set_rules('ssn', $this->lang->line("ssn"), 'xss_clean|max_length[16]');
        $this->form_validation->set_rules('date_of_birth', $this->lang->line("date_of_birth"), 'required|xss_clean');
        $this->form_validation->set_rules('types', $this->lang->line("client_type"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'xss_clean|min_length[9]|max_length[16]');
        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
        $this->form_validation->set_rules('medicaid_no', $this->lang->line("medicaid_no"), 'xss_clean|max_length[50]');
        $this->form_validation->set_rules('comments', $this->lang->line("comment"), 'max_length[200]|xss_clean');


        $pre_data = $this->clients_model->getClientsByCode($name);
        $photo = $pre_data->doc;
        if ($_FILES['userfile']['size'] > 0) {
            unlink(realpath('assets/uploads/client_docs/' . $pre_data->doc));
            $this->load->library('upload_photo');
            $config['upload_path'] = 'assets/uploads/client_docs/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|txt';
            $config['max_size'] = '2500';
            $new_name = $this->input->post('code') . $_FILES["userfiles"]['name'];
            $config['file_name'] = $new_name;
            $config['overwrite'] = FALSE;

            $this->upload_photo->initialize($config);

            if (!$this->upload_photo->do_upload()) {

                $error = $this->upload_photo->display_errors();
                $this->session->set_flashdata('message', $error);
                redirect("module=clients", 'refresh');
            }

            $photo = $this->upload_photo->file_name;

        }

        if ($this->form_validation->run() == true) {

            $dob = $this->ion_auth->fsd(trim($this->input->post('date_of_birth')));
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'code' => $this->input->post('code'),
                'email' => $this->input->post('email'),
                'ssn' => $this->input->post('ssn'),
                'client_type' => $this->input->post('types'),
                'doc' => $photo,
                'comment' => $this->input->post('comments'),
//                'address' => $this->input->post('address'),
                'medicaid_no' => $this->input->post('medicaid_no'),
                'date_of_birth' => $dob,
                'phone' => $this->input->post('phone'),
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }


        if ($this->form_validation->run() == true && $this->clients_model->updateClient($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("client_updated"));
            redirect("module=clients", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $meta['page_title'] = $this->lang->line("edit_client");
            $data['page_title'] = $this->lang->line("edit_client");
            $data['types'] = $this->clients_model->getTypes();
            $data['name'] = $name;
            $data['client'] = $this->clients_model->getClientsByCode($name);
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

        $data = $this->clients_model->getClientsByCode($name);
        if ($this->clients_model->delete($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            unlink(realpath('assets/uploads/client_docs/' . $data->doc));
            $this->session->set_flashdata('success_message', $this->lang->line("client_deleted"));
            redirect("module=clients", 'refresh');
        }
    }

    function add_type()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        //validate form input
        $this->form_validation->set_rules('type_code', $this->lang->line("type_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type_name', $this->lang->line("type_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {
            $code = $this->input->post('type_code');
            $name = $this->input->post('type_name');

        }

//        if($this->form_validation->run() == true && $this->level_model->getLevelByName(trim($name))){
        if ($this->form_validation->run() == true) {


            $isExists = $this->clients_model->getClientTypeByCode(trim($name));
            if ($isExists) {
                $this->session->set_flashdata('message', "Client Type(" . $name . ") is already exists");
                redirect("module=home", 'refresh');
            } else {
                $data = array('type_code' => $this->input->post('type_code'),
                    'type_name' => $this->input->post('type_name'),
                    'created_by' => USER_NAME,
                    'created_date' => date('Y-m-d H:i:s')
                );
            }
        }
        if ($this->form_validation->run() == true && $this->clients_model->addType($data)) { //check to see if we are creating the customer
//            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("type_added"));
            redirect("module=clients&view=types", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $meta['page_title'] = $this->lang->line("new_type");
            $data['page_title'] = $this->lang->line("new_type");
            $data['rnumber'] = $this->clients_model->getRQNextAI();
            $this->load->view('commons/header', $meta);
            $this->load->view('add_type', $data);
            $this->load->view('commons/footer');

        }

    }

    function getdatatableajax()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("type_code,type_name")
            ->from("client_type")
            ->add_column("Actions",
//                "<center><a href='index.php?module=level&amp;view=edit&amp;name=$2' class='tip' title='" . $this->lang->line("edit_level") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=level&amp;view=delete&amp;name=$2' onClick=\"return confirm('" . $this->lang->line('alert_x_level') . "')\" class='tip' title='" . $this->lang->line("delete_level") . "'><i class=\"icon-remove\"></i></a></center>", "level_code,level_name");
                "<center><center><a href='index.php?module=clients&amp;view=edit_type&amp;name=$1' class='tip' title='" . $this->lang->line("edit_room") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=clients&amp;view=delete_type&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_type') . "')\" class='tip' title='" . $this->lang->line("delete_type") . "'><i class=\"icon-remove\"></i></a></center>", "type_code,type_name");

        echo $this->datatables->generate();

    }


    function edit_type($name = NULL)

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
        $this->form_validation->set_rules('type_code', $this->lang->line("type_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type_name', $this->lang->line("type_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {

            $data = array('type_code' => $this->input->post('type_code'),
                'type_name' => $this->input->post('type_name'),
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }

        if ($this->form_validation->run() == true && $this->clients_model->updateType($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("type_updated"));
            redirect("module=clients&view=types", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $data['types'] = $this->clients_model->getTypesByCode($name);

            $meta['page_title'] = $this->lang->line("edit_type");
            $data['name'] = $name;
            $data['page_title'] = $this->lang->line("edit_type");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit_type', $data);
            $this->load->view('commons/footer');

        }
    }


    function delete_type($name = NULL)
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
        if ($this->clients_model->deleteType($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("type_deleted"));
            redirect("module=clients&view=types", 'refresh');
        }

    }


    function getBuildings()
    {
        $vendor_id = $this->input->get('vendor_id', TRUE);

        if ($rows = $this->clients_model->getBuildingByVendorID($vendor_id)) {
            $ct[""] = '';
            foreach ($rows as $building) {
                $ct[$building->building_code] = $building->building_code;
            }
            $data = form_dropdown('building_code', $ct, '', 'class="span4 select_search" id="building_code" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("subcategory") . '"');
        } else {
            $data = "";
        }
        echo $data;
    }

    function getApartments()
    {
        $vendor_id = $this->input->get('vendor_id', TRUE);
        $building_id = $this->input->get('building_code', TRUE);

        if ($rows = $this->clients_model->getApartmentByVendorID($vendor_id, $building_id)) {
            $ct[""] = '';
            foreach ($rows as $apartment) {
                $ct[$apartment->room_code] = $apartment->room_code;
            }
            $data = form_dropdown('apartment_code', $ct, '', 'class="span4 select_search" id="apartment_code" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("apartment_code") . '"');
        } else {
            $data = "";
        }
        echo $data;
    }


    function client_discharge($name = null)
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        $isExists = $this->clients_model->getClientByIntakeCode($name);
        if ($isExists) {
            $this->session->set_flashdata('message', "Client already discharged");
            redirect("module=clients&view=intake_list", 'refresh');
        }


        //validate form input
        $this->form_validation->set_rules('move_out_date', $this->lang->line("move_out_date"), 'xss_clean');
        $this->form_validation->set_rules('types', $this->lang->line("client_type"), 'xss_clean');
        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');
        $this->form_validation->set_rules('comments', $this->lang->line("comment"), 'max_length[200]|xss_clean');


        if ($_FILES['userfile']['size'] > 0) {

            $this->load->library('upload_photo');

            $config['upload_path'] = 'assets/uploads/client_discharge_docs/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|txt';
            $config['max_size'] = '2500';
            $new_name = $this->input->post('client_code') . '-' . $this->input->post('move_in_date') . $_FILES["userfiles"]['name'];
            $config['file_name'] = $new_name;
            $config['overwrite'] = FALSE;

            $this->upload_photo->initialize($config);

            if (!$this->upload_photo->do_upload()) {

                $error = $this->upload_photo->display_errors();
                $this->session->set_flashdata('message', $error);
                redirect("module=clients&view=intake_list", 'refresh');
            }

            $doc = $this->upload_photo->file_name;

        }


        if ($this->form_validation->run() == true) {

            $mod = $this->ion_auth->fsd(trim($this->input->post('move_out_date')));
            $mid = $this->ion_auth->fsd(trim($this->input->post('move_in_date')));
            $data = array(
                'status' => 'Discharged',
                'move_out_date' => $mod,
                'comment' => $this->input->post("comments"),
                'doc' => $doc,
                'client_type' => $this->input->post("types"),
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }

        $dateTimestamp1 = strtotime($mod);
        $dateTimestamp2 = strtotime($mid);

        if ($dateTimestamp1 < $dateTimestamp2) {
            $this->session->set_flashdata('message', "Move in date is greater than Move out date.");
            redirect("module=clients&view=intake_list", 'refresh');

        }


//


        if ($this->form_validation->run() == true && $this->clients_model->dischargeClient($data, $name, $mod)) { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("client_intake_added"));
            redirect("module=clients&view=intake_list", 'refresh');

        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $meta['page_title'] = $this->lang->line("client_discharge");
            $data['page_title'] = $this->lang->line("client_discharge");
            $data['name'] = $name;
            $data['client'] = $this->clients_model->getIntakeByCode($name);
            $data['types'] = $this->clients_model->getTypes();
            $this->load->view('commons/header', $meta);
            $this->load->view('client_discharge', $data);
            $this->load->view('commons/footer');

        }

    }


    function client_transfer($name = null)
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        $isDischarged = $this->clients_model->getClientByIntakeCode($name);
        $isExists = $this->clients_model->getIntakeByCode($name);
        if ($isDischarged) {
            $this->session->set_flashdata('message', "Client already discharged.");
            redirect("module=clients&view=intake_list", 'refresh');
        }


        //validate form input
        $this->form_validation->set_rules('apartment_code', $this->lang->line("apartment_code"), 'required|xss_clean');
        $this->form_validation->set_rules('comments', $this->lang->line("comments"), 'required|xss_clean');
        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');


        if ($_FILES['userfile']['size'] > 0) {

            $this->load->library('upload_photo');

            $config['upload_path'] = 'assets/uploads/client_transfer_docs/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|txt';
            $config['max_size'] = '2500';
            $new_name = $this->input->post('client_code') . '-' .date('Y-m-d') . $_FILES["userfiles"]['name'];
            $config['file_name'] = $new_name;
            $config['overwrite'] = FALSE;

            $this->upload_photo->initialize($config);

            if (!$this->upload_photo->do_upload()) {

                $error = $this->upload_photo->display_errors();
                $this->session->set_flashdata('message', $error);
                redirect("module=clients&view=intake_list", 'refresh');
            }

            $doc = $this->upload_photo->file_name;

        }


        if ($this->form_validation->run() == true) {
//

            $isDateConflict = $this->clients_model->getAptTaggedAtDate(trim($this->input->post("apartment_code")), date('Y-m-d'));
            $dateTimestamp1 = strtotime($isDateConflict->move_out_date);
            $dateTimestamp2 = strtotime(date('Y-m-d'));

            if ($dateTimestamp2 < $dateTimestamp1) {
                $this->session->set_flashdata('message', "Building already occupied for this date.");
                redirect("module=clients&view=intake_list", 'refresh');

            }

            $old_data = array(
                'status' => 'Transferred',
                'move_out_date' => date('Y-m-d'),
                'comment' => $this->input->post("comments"),
                'transfer_doc' =>$doc,
                'client_type' => $isExists->client_type,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );

            $new_data = array(
                'client_code' => $isExists->client_code,
                'vendor_code' => $isExists->vendor_code,
                'building_code' => $isExists->building_code,
                'apartment_code' => $this->input->post('apartment_code'),
                'code' => $this->clients_model->getRQNextAIClientIntake(),
                'status' => 'Admitted',
                'client_type' => $isExists->client_type,
                'move_in_date' => date('Y-m-d'),
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s')
            );

        }


//        var_dump($isExists);
//        var_dump($new_data);
        if ($this->form_validation->run() == true && $this->clients_model->transferClient($old_data,$new_data, $name)) { //check to see if we are creating the customer
//        if ($this->form_validation->run() == true) { //check to see if we are creating the customer
            $this->session->set_flashdata('success_message', $this->lang->line("client_transfer"));
            redirect("module=clients&view=intake_list", 'refresh');
//
        } else {


            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $meta['page_title'] = $this->lang->line("client_transfer");
            $data['page_title'] = $this->lang->line("client_transfer");
            $data['name'] = $name;
            $intake_details = $this->clients_model->getIntakeByCode($name);
            $data['client'] = $intake_details;
            $data['types'] = $this->clients_model->getApartmentByVendorID("",$intake_details->building_code);
            $this->load->view('commons/header', $meta);
            $this->load->view('client_transfer', $data);
            $this->load->view('commons/footer');

        }

    }



    function client_details($id = null)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("client_details");
        $data['page_title'] = $this->lang->line("client_details");
        $this->load->view('commons/header', $meta);
        $data['id'] = $id;
        $this->load->view('client_details', $data);
        $this->load->view('commons/footer');
    }


    function getDataForClientDetails($code = null)
    {
        if ($this->input->get('id')) {
            $code = $this->input->get('id');
        }

        $this->load->library('datatables');
        $this->datatables
            ->select("c.code as code,c.client_code, CONCAT(cl.first_name,' ',cl.last_name) AS s_name,c.client_type,cu.name,c.building_code,c.apartment_code,c.status,c.move_in_date,c.move_out_date, case WHEN c.move_out_date is  null then DATEDIFF(  CURDATE(), c.move_in_date ) else DATEDIFF( c.move_out_date, c.move_in_date ) end as days", FALSE)
            ->from("client_intake c")
            ->join('clients cl', 'c.client_code = cl.code', 'left')
            ->join('customers cu', 'c.vendor_code = cu.code', 'left')
            ->where('clients.code', $code)
            ->add_column("Actions",
                "<center><a href='index.php?module=clients&amp;view=client_discharge&amp;name=$1' class='tip' title='" . $this->lang->line("client_discharge") . "'><i class=\"icon-adjust\"></i></a> <a href='index.php?module=clients&amp;view=delete_intake&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_intake') . "')\" class='tip' title='" . $this->lang->line("delete_intake") . "'><i class=\"icon-remove\"></i></a></center>", "code")
            ->unset_column('code');
        echo $this->datatables->generate();
    }


}