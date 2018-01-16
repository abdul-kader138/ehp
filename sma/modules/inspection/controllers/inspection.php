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


    function add_inspection(){

        $groups = array('purchaser', 'viewer');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=sales', 'refresh');
        }

//        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //validate form input
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required|xss_clean');
        $this->form_validation->set_rules('building_code', $this->lang->line("building_code"), 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');
//
        $apt = "apt_";
        $category = "category_";
        $concern = "concern_";
        $details = "detail_";
        $weight = "weight_";
        $comments = "comments_";

//        if ($this->form_validation->run() == true) {
            $date = $this->ion_auth->fsd(trim($this->input->post('date')));
            $building_code = $this->input->post('building_code');
            $customer_id = $this->input->post('customer');
            $note = $this->ion_auth->clear_tags($this->input->post('note'));
//
            for ($i = 0; $i <= 500; $i++) {
                if ($this->input->post($apt . $i) && $this->input->post($category . $i)) {
//
//                    $inv_quantity[] = $this->input->post($quantity . $i);
//                    //$inv_product_code[] = $this->input->post($product.$i);
                    $apt_id[] = $this->input->post($apt . $i);
                    $category_id[] = $this->input->post($category . $i);
                    $concern_id[] = $this->input->post($concern . $i);
                    $details_id[] = $this->input->post($details . $i);
                    $weight_id[] = $this->input->post($weight . $i);
                    $comments_id[] = $this->input->post($comments . $i);
//
                }
            }
//
//

            $keys = array("apt_id", "category_id", "concern_id", "details_id", "weight_id", "comments_id");
//
            $items = array();
            foreach (array_map(null, $apt_id, $category_id, $concern_id, $details_id, $weight_id, $comments_id) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }

            var_dump($items);
//
//            if (DISCOUNT_METHOD == 1 && DISCOUNT_OPTION == 1) {
//
//                $ds_dts = $this->sales_model->getDiscountByID($inv_discount);
//                $ds = $ds_dts->discount;
//                $dsTp = $ds_dts->type;
//
//                if ($dsTp == 1 && $ds != 0) {
//                    $val_discount = (($inv_total_no_tax + $total_tax) * $ds / 100);
//                } else {
//                    $val_discount = $ds;
//                }
//
//            }
//
//            if (TAX2) {
//                $tax_dts = $this->sales_model->getTaxRateByID($tax_rate2);
//                $taxRt = $tax_dts->rate;
//                $taxTp = $tax_dts->type;
//                $t_ds = ($total_ds != 0) ? $total_ds : $val_discount;
//                if ($taxTp == 1 && $taxRt != 0) {
//                    $val_tax2 = (($inv_total_no_tax + $total_tax - $t_ds) * $taxRt / 100);
//                } else {
//                    $val_tax2 = $taxRt;
//                }
//
//            } else {
//                $val_tax2 = 0;
//                $tax_rate2 = 0;
//            }
//
//            if (DISCOUNT_METHOD == 2 && DISCOUNT_OPTION == 1) {
//
//                $ds_dts = $this->sales_model->getDiscountByID($inv_discount);
//                $ds = $ds_dts->discount;
//                $dsTp = $ds_dts->type;
//
//                if ($dsTp == 1 && $ds != 0) {
//                    $val_discount = ((($inv_total_no_tax + $total_tax + $val_tax2) * $ds) / 100);
//                } else {
//                    $val_discount = $ds;
//                }
//
//            } elseif (DISCOUNT_OPTION != 1) {
//                $val_discount = $total_ds;
//                $inv_discount = 0;
//            }
//
//            $gTotal = $inv_total_no_tax + $total_tax + $val_tax2 - $val_discount;
//
//            $saleDetails = array('reference_no' => $reference_no,
//                'date' => $date,
//                'biller_id' => $biller_id,
//                'biller_name' => $biller_name,
//                'customer_id' => $customer_id,
//                'customer_name' => $customer_name,
//                'note' => $note,
//                'internal_note' => $internal_note,
//                'inv_total' => $inv_total_no_tax,
//                'total_tax' => $total_tax,
//                'total' => $gTotal,
//                'total_tax2' => $val_tax2,
//                'tax_rate2_id' => $tax_rate2,
//                'inv_discount' => $val_discount,
//                'discount_id' => $inv_discount,
//                'user' => USER_NAME,
//                'shipping' => $shipping,
//                'paid' => $paid
//            );
//
//        }else{
            $data['customers'] = $this->inspection_model->getAllCustomers();
            $data['concerns'] = $this->inspection_model->getAllConcern();
            $data['categories'] = $this->inspection_model->getAllCategory();
            $data['buildingList'] = $this->inspection_model->getAllBuildings();
            $meta['page_title'] = 'Add Inspection Details';
            $data['page_title'] = 'Add Inspection Details';;
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');
//        }
//

    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);

        if (strlen($term) < 2) {
            die();
        }

        $rows = $this->inspection_model->getRoomsNames($term);

        $json_array = array();
        foreach ($rows as $row)
            array_push($json_array, $row->room_name);

        echo json_encode($json_array);
    }


    function add_room()
    {
        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        if ($item = $this->inspection_model->getRoomByName($name)) {

            $code = $item->room_code;
            $price =0;
            $product_tax = 0;

//            $tax_rate = $this->sales_model->getTaxRateByID($product_tax);
            $tax_rate = 0;

            $product = array('code' => $code, 'price' => $price, 'tax_rate' => $tax_rate);

        }

        echo json_encode($product);

    }
//

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
        if ($this->inspection_model->getDeficiencyCategoryByName(trim($name))) {
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



    function deficiency_concern()
    {

        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $data['success_message'] = $this->session->flashdata('success_message');

        $meta['page_title'] = $this->lang->line("list_concern");
        $data['page_title'] = $this->lang->line("list_concern");
        $this->load->view('commons/header', $meta);
        $this->load->view('deficiency_concern', $data);
        $this->load->view('commons/footer');
    }

    function getDataTableAjaxForConcernDetails()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("concern_code,concern_name")
            ->from("deficiency_concern")
            ->add_column("Actions",
                "<center><a href='index.php?module=inspection&amp;view=edit_deficiency_concern&amp;name=$1' class='tip' title='" . $this->lang->line("edit_deficiency_concern") . "'><i class=\"icon-edit\"></i></a> <a href='index.php?module=inspection&amp;view=delete_deficiency_concern&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_concern') . "')\" class='tip' title='" . $this->lang->line("delete_deficiency_concern") . "'><i class=\"icon-remove\"></i></a></center>", "concern_code");
        echo $this->datatables->generate();


    }


    function add_deficiency_concern()
    {


        if (!$this->ion_auth->in_group('owner')) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }
        //validate form input
        $this->form_validation->set_rules('code', $this->lang->line("concern_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line("concern_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');

        }
        if ($this->inspection_model->getDeficiencyConcernByName(trim($name))) {
            $this->session->set_flashdata('message', $this->lang->line("concern_name_exist"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=inspection', 'refresh');
        }

        if ($this->form_validation->run() == true) {
            $inspection_concern_data = array();
            if ($this->form_validation->run() == true) {
                $inspection_concern_data = array('concern_code' => $code,
                    'concern_name' => $name,
                    'created_by' => USER_NAME,
                    'created_date' => date('Y-m-d H:i:s')
                );

            }
        }


//
        if ($this->form_validation->run() == true && $this->inspection_model->addDeficiencyConcern($inspection_concern_data)) { //check to see if we are creating the customer
//            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_concern_added"));
            redirect("module=inspection&view=deficiency_concern", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $meta['page_title'] = $this->lang->line("add_deficiency_concern");
            $data['page_title'] = $this->lang->line("add_deficiency_concern");
            $data['rnumber'] = $this->inspection_model->getRQNextAIForConcern();
            $this->load->view('commons/header', $meta);
            $this->load->view('add_deficiency_concern', $data);
            $this->load->view('commons/footer');

        }
    }


    function edit_deficiency_concern($name = NULL)

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
        $this->form_validation->set_rules('concern_code', $this->lang->line("concern_code"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('concern_name', $this->lang->line("concern_name"), 'required|min_length[3]|xss_clean');

        if ($this->form_validation->run() == true) {
            $concern_code = $this->input->post('concern_code');
            $concern_name = $this->input->post('concern_name');

        }

        if ($this->form_validation->run() == true) {

            $data = array('concern_code' => $concern_code,
                'concern_name' => $concern_name,
                'updated_by' => USER_NAME,
                'updated_date' => date('Y-m-d H:i:s')
            );
        }

        if ($this->form_validation->run() == true && $this->inspection_model->updateDeficiencyConcern($name, $data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_concern_updated"));
            redirect("module=inspection&view=deficiency_concern", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));


            $data['deficiency'] = $this->inspection_model->getDeficiencyConcernByCode($name);
            $data['name'] = $name;
            $meta['page_title'] = $this->lang->line("edit_deficiency_concern");
            $data['page_title'] = $this->lang->line("edit_deficiency_concern");
            $this->load->view('commons/header', $meta);
            $this->load->view('edit_deficiency_concern', $data);
            $this->load->view('commons/footer');

        }
    }


    function delete_deficiency_concern($name = NULL)
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
        if ($this->inspection_model->deleteDeficiencyConcern($name)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("deficiency_concern_deleted"));
            redirect("module=inspection&view=deficiency_concern", 'refresh');
        }

    }


    function getDetails()
    {
        $category_code = $this->input->get('category_code', TRUE);

        if ($rows = $this->inspection_model->getDetailsByID($category_code)) {
            $ct[""] = '';
            foreach ($rows as $detail) {
                $ct[$detail->details_code] = $detail->details_name;
            }
            $data = form_dropdown('detail_code', $ct, '', 'class="span12 select_search" id="details_code" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("apartment_code") . '"');
        } else {
            $data = "";
        }
        echo $data;
    }


}