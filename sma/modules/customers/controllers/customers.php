<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends MX_Controller
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
    | MODULE: 			Customers
    | -----------------------------------------------------
    | This is customers module controller file.
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
        $this->load->model('customers_model');


    }

    function index()
    {

        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("vendor");
        $data['page_title'] = $this->lang->line("vendor");
        $this->load->view('commons/header', $meta);
        $this->load->view('content', $data);
        $this->load->view('commons/footer');
    }

    function getdatatableajax()
    {

        $userHasAuthority = $this->ion_auth->in_group(array('admin', 'owner'));
        $this->load->library('datatables');
        if ($userHasAuthority) {
            $this->datatables
                ->select("c.id as id, c.code,c.name,c.email,c.phone, c.address, c.city,c.state,c.date_of_join,count(b.id) as apt")
                ->from("customers c")
                ->join("building_allocation b", "c.id = b.vendor_id", "left")
                ->add_column("Actions",
                    "<center>			<a class=\"tip\" title='" . $this->lang->line("edit_vendor") . "' href='index.php?module=customers&amp;view=edit&amp;id=$1'><i class=\"icon-edit\"></i></a>
							    <a class=\"tip\" title='" . $this->lang->line("delete_vendor") . "' href='index.php?module=customers&amp;view=delete&amp;id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_vendor') . "')\"><i class=\"icon-remove\"></i></a></center>", "id")
                ->unset_column('id');

        } else {
            $this->datatables
                ->select("c.id, c.code,c.name,c.email,c.phone, c.address, c.city,c.state,c.country,c.date_of_join,count(b.id) as apt")
                ->from("customers c")
                ->join("building_allocation b", "c.id = b.vendor_id", "left")
                ->add_column("Actions",
                    "")
                ->unset_column('id');

        }
        echo $this->datatables->generate();

    }

    function add()
    {
        $groups = array('owner', 'admin', 'salesman');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("code"), 'xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean|min_length[15]|max_length[255]');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'xss_clean');
        $this->form_validation->set_rules('join_date', $this->lang->line("join_date"), 'xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'xss_clean|min_length[9]|max_length[16]');


        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'date_of_join' => date('Y-m-d'),
                'phone' => $this->input->post('phone'),
                'date_of_join' =>$this->ion_auth->fsd($this->input->post('join_date')),
                'created_by' => USER_NAME,
                'created_date' => date('Y-m-d H:i:s')
            );
        }

        if ($this->form_validation->run() == true && $this->customers_model->addCustomer($data)) { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("vendor_added"));
            redirect("module=customers", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );

            $meta['page_title'] = $this->lang->line("new_vendor");
            $data['page_title'] = $this->lang->line("new_vendor");
            $data['rnumber'] = $this->customers_model->getRQNextAI();
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');

        }
    }

    function edit($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }


        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("code"), 'xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean|min_length[15]|max_length[255]');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'xss_clean');
        $this->form_validation->set_rules('join_date', $this->lang->line("join_date"), 'xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'xss_clean|min_length[9]|max_length[16]');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'phone' => $this->input->post('phone'),
                'date_of_join' =>$this->ion_auth->fsd($this->input->post('join_date')),
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }


        if ($this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("vendor_updated"));
            redirect("module=customers", 'refresh');
        } else {
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['customer'] = $this->customers_model->getCustomerByID($id);

            $meta['page_title'] = $this->lang->line("edit_vendor");
            $data['id'] = $id;
            $data['page_title'] = $this->lang->line("edit_vendor");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit', $data);
            $this->load->view('commons/footer');

        }
    }


    function add_by_csv()
    {

        $groups = array('owner', 'admin');
        if (!$this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=products', 'refresh');
        }

        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
                redirect('module=home', 'refresh');
            }

            if (isset($_FILES["userfile"])) /*if($_FILES['userfile']['size'] > 0)*/ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '200';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=suppliers&view=add_by_csv", 'refresh');
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('name', 'email', 'phone', 'company', 'address', 'city', 'state', 'postal_code', 'country');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->customers_model->getCustomerByEmail($csv['email'])) {
                        $this->session->set_flashdata('message', $this->lang->line("check_customer_email") . " (" . $csv['email'] . "). " . $this->lang->line("customer_already_exist") . " " . $this->lang->line("line_no") . " " . $rw);
                        redirect("module=customers&view=add_by_csv", 'refresh');
                    }
                    $rw++;
                }
            }

            $final = $this->mres($final);
            //$data['final'] = $final;
        }

        if ($this->form_validation->run() == true && $this->customers_model->add_customers($final)) {
            $this->session->set_flashdata('success_message', $this->lang->line("customers_added"));
            redirect('module=customers', 'refresh');
        } else {

            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $meta['page_title'] = $this->lang->line("add_customers_by_csv");
            $data['page_title'] = $this->lang->line("add_customers_by_csv");
            $this->load->view('commons/header', $meta);
            $this->load->view('add_by_csv', $data);
            $this->load->view('commons/footer');

        }

    }

    function delete($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
            redirect('module=home', 'refresh');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        if ($this->customers_model->deleteCustomer($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("vendor_deleted"));
            redirect("module=customers", 'refresh');
        }

    }

    function mres($q)
    {
        if (is_array($q))
            foreach ($q as $k => $v)
                $q[$k] = $this->mres($v); //recursive
        elseif (is_string($q))
            $q = mysql_real_escape_string($q);
        return $q;
    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);

        if (strlen($term) < 2) break;
        $rows = $this->customers_model->getCustomerNames($term);

        $json_array = array();
        foreach ($rows as $row)
            $json_array[$row->id] = $row->name;
        //array_push($json_array, $row->name);

        echo json_encode($json_array);
    }


}