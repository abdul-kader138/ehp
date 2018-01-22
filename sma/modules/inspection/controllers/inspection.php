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

        $meta['page_title'] = $this->lang->line("list_inspection");
        $data['page_title'] = $this->lang->line("list_inspection");
        $this->load->view('commons/header', $meta);
        $this->load->view('inspection', $data);
        $this->load->view('commons/footer');

    }


    function add_inspection()
    {

        $groups = array('purchaser', 'viewer');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line("access_denied"));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

//        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //validate form input
        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required|xss_clean');
        $this->form_validation->set_rules('building_code', $this->lang->line("building_code"), 'required|xss_clean');
        $this->form_validation->set_rules('reference_no', $this->lang->line("inspection_code"), 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');
//
        $apt = "apt_";
        $category = "category_";
        $concern = "concern_";
        $weight = "weight_";
        $comments = "comments_";

        if ($this->form_validation->run() == true) {
            $date = $this->ion_auth->fsd(trim($this->input->post('date')));
            $building_code = $this->input->post('building_code');
            $reference_no = $this->input->post('reference_no');
            $customer_id = $this->input->post('customer');
            $note = $this->ion_auth->clear_tags($this->input->post('note'));
//
            $count = 0;
            $weight_val = 0;
            for ($i = 0; $i <= 200; $i++) {
                if ($this->input->post($apt . $i) && $this->input->post($category . $i)) {
                    $count++;
                    $apt_id[] = $this->input->post($apt . $i);
                    $category_id[] = $this->input->post($category . $i);
                    $concern_id[] = $this->input->post($concern . $i);
                    $details_id[] = $this->input->post($i);
                    $weight_id[] = $this->input->post($weight . $i);
                    $comments_id[] = $this->input->post($comments . $i);
                    $inspection_code[] = $reference_no;
                    $building_code_id[] = $building_code;
                    $vendor_code_id[] = $customer_id;
                    $date_id[] = $date;
                    $create_user_id[] = USER_NAME;
                    $create_date_id[] = date('Y-m-d H:i:s');
                }
            }
        }
        $weight_val = array_sum($weight_id);
        $inspection = array(
            'date' => $date,
            'building_code`' => $building_code,
            'vendor_code' => $customer_id,
            'inspection_code' => $reference_no,
            'note' => $note,
            'total_weight' => $weight_val,
            'total_deficiency' => $count,
            'created_by' => USER_NAME,
            'created_date' => date('Y-m-d H:i:s'));


        $keys = array("inspection_code", "building_code", "vendor_code", "apartment_code", "category_id", "concern_id", "details_id", "weight", "comments_id", 'date', 'created_by', 'created_date');
//
        $items = array();
        foreach (array_map(null, $inspection_code, $building_code_id, $vendor_code_id, $apt_id, $category_id, $concern_id, $details_id, $weight_id, $comments_id, $date_id, $create_user_id, $date_id) as $key => $value) {
            $items[] = array_combine($keys, $value);
        }
        if ($this->form_validation->run() == true && $this->inspection_model->addInspection($inspection, $items)) { //check to see if we are creating the customer
//            redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("inspection_added"));
            redirect("module=inspection", 'refresh');
        } else {
            $data['customers'] = $this->inspection_model->getAllCustomers();
            $data['concerns'] = $this->inspection_model->getAllConcern();
            $data['categories'] = $this->inspection_model->getAllCategory();
            $data['ref'] = $this->inspection_model->getRQNextAIInspection();
            $meta['page_title'] = 'Add Inspection Details';
            $meta['page_title'] = 'Add Inspection Details';
//            $data['page_title'] = 'Add Sales';;
            $this->load->view('commons/header', $meta);
            $this->load->view('add', $data);
            $this->load->view('commons/footer');
        }

    }

    function getDataTableAjaxForInspection()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("i.date,i.inspection_code as code,c.name,i.building_code,i.total_deficiency,i.total_weight")
            ->from("inspection i")
            ->join("customers c", 'i.vendor_code = c.id', 'left')
            ->group_by('i.inspection_code')
            ->add_column("Actions",
                "<center><a href='#' onClick=\"MyWindow=window.open('index.php?module=inspection&view=view_details&id=$1', 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\" title='" . $this->lang->line("view_details") . "' class='tip'><i class='icon-fullscreen'></i></a>
                 <a href='index.php?module=inspection&view=details_pdf&id=$1' title='Print Details' class='tip'><i class='icon-download'></i></a>
                 <a href='index.php?module=inspection&view=add_image&id=$1' title='add Image' class='tip'><i class='icon-download'></i></a>
                 <a href='index.php?module=inspection&amp;view=edit_inspection&amp;name=$1' class='tip' title='" . $this->lang->line("edit_inspection") . "'><i class=\"icon-edit\"></i></a>
                <a href='index.php?module=inspection&amp;view=delete_inspection&amp;name=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_inspection') . "')\" class='tip' title='" . $this->lang->line("delete_inspection") . "'><i class=\"icon-remove\"></i></a></center>", "code");
        echo $this->datatables->generate();

    }

    function view_details($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $data['rows'] = $this->inspection_model->getAllInspectionDetails($id);
        $data['inspection'] = $this->inspection_model->getAllInspection($id);
        $data['inspection_apt'] = $this->inspection_model->getAllInspectionApt($id);
        $data['concern_weights'] = $this->inspection_model->getAllConcernAndWeight($id);
        $vendor_id = $data['rows'][0]->vendor_code;
        $data['customer'] = $this->inspection_model->getCustomerByID($vendor_id);
        $data['page_title'] = "Inspection Details";
        $this->load->view('view_details', $data);

    }


    function add_image($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $data['name'] = $id;
        $data['page_title'] = "Add Sales";
        $meta['page_title'] = 'Add Sales';
        $meta['page_title'] = 'Add Sales';
        $this->load->view('commons/header', $meta);
        $this->load->view('add_image', $data);
        $this->load->view('commons/footer');

    }

    function upload_image($id = NULL)
    {

        $this->form_validation->set_rules('inspection_image', 'Inspection Image', 'xss_clean');


//        https://gist.github.com/zitoloco/1558423
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

            $this->load->library('upload_photo');
        if ($this->form_validation->run() == true) {
            $num = 0;
            $number_of_files_uploaded = count($_FILES['inspection_image']['name']);
            // Faking upload calls to $_FILE
            for ($i = 0; $i < $number_of_files_uploaded; $i++) {
//                var_dump($config);
                $_FILES['userfile']['name'] = $_FILES['inspection_image']['name'][$i];
                $_FILES['userfile']['type'] = $_FILES['inspection_image']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['inspection_image']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $_FILES['inspection_image']['error'][$i];
                $_FILES['userfile']['size'] = $_FILES['inspection_image']['size'][$i];
//                if (!is_dir('assets/img/inspection/'.$id)) {
//                    mkdir('assets/img/inspection/' . $id, 0777, TRUE);
//
//                }
                $config = array(
                    'file_name' => 'test' . $num++,
                    'allowed_types' => 'jpg|jpeg|png|gif',
                    'max_size' => 3000,
                    'overwrite' => FALSE,

                    /* real path to upload folder ALWAYS */
                    'upload_path'
                    => 'assets/img/'
                );
                $this->upload_photo->initialize($config);

                $data['id'] = $id;
                if (!$this->upload_photo->do_upload()) {
                    $error = array('error' => $this->upload_photo->display_errors());
                    $this->load->view('add_image', $data);
                } else {
                    $final_files_data[] = $this->upload_photo->data();
                    redirect("module=inspection&view=inspection", 'refresh');
                    // Continue processing the uploaded data
                }
            }
        }


//            if(DEMO) {
//                $this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
//                redirect("module=home", 'refresh');
//            }
//            var_dump($_FILES['inspection_image']);

//            if($_FILES['inspection_image']['size'] > 0){
//
//                $this->load->library('upload_photo');
//
//                $config['upload_path'] = 'assets/img/';
//                $config['allowed_types'] = 'gif|jpg|png|pdf';
//                $config['max_size'] = '3000';
//                $config['max_width'] = '2000';
//                $config['max_height'] = '400';
//                $config['overwrite'] = FALSE;
//
//                $this->upload_photo->initialize($config);
//
//                if( ! $this->upload_photo->do_upload()){
//
//                    $error = $this->upload_photo->display_errors();
//                    $this->session->set_flashdata('message', $error);
//                    redirect("module=inspection&view=view_details", 'refresh');
//                }
//
//                $photo = $this->upload_photo->file_name;
//
//            } else {
//                $this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
//                redirect("module=inspection&view=inspection", 'refresh');
//            }
//

//        }

//        if ($this->form_validation->run() == true) {
//            $this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
////            redirect("module=settings&view=change_logo", 'refresh');
//        }
        else {

            if ($this->input->get('id')) {
                $id = $this->input->get('id');
            }
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $data['id'] = $id;
            $data['page_title'] = "Add Sales";
            $meta['page_title'] = 'Add Sales';
            $meta['page_title'] = 'Add Sales';
            $this->load->view('commons/header', $meta);
            $this->load->view('add_image', $data);
            $this->load->view('commons/footer');

        }
    }

    function details_pdf()
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $data['rows'] = $this->inspection_model->getAllInspectionDetails($id);
        $data['inspection'] = $this->inspection_model->getAllInspection($id);
        $data['inspection_apt'] = $this->inspection_model->getAllInspectionApt($id);
        $data['concern_weights'] = $this->inspection_model->getAllConcernAndWeight($id);
        $vendor_id = $data['rows'][0]->vendor_code;
        $data['customer'] = $this->inspection_model->getCustomerByID($vendor_id);
        $html = $this->load->view('view_details', $data, TRUE);


        $this->load->library('MPDF/mpdf');

        $mpdf = new mPDF('utf-8', 'A4', '12', '', 10, 10, 10, 10, 9, 9);
        $mpdf->useOnlyCoreFonts = true;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle(SITE_NAME);
        $mpdf->SetAuthor(SITE_NAME);
        $mpdf->SetCreator(SITE_NAME);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetAutoFont();
        $stylesheet = file_get_contents('assets/css/bootstrap-' . THEME . '.css');
        $mpdf->WriteHTML($stylesheet, 1);

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>");
        $html = str_replace($search, $replace, $html);


        $name = $this->lang->line("inspection_details") . "-" . $data['rows'][0]->inspection_code . ".pdf";

        $mpdf->WriteHTML($html);

        $mpdf->Output($name, 'D');

        exit;

    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $code = $this->input->get('building_code', TRUE);

        if (strlen($term) < 2) {
            die();
        }

        $rows = $this->inspection_model->getRoomsNames($term,$code);

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
            $price = 0;
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
            ->join("deficiency_category", 'deficiency_details.category_code = deficiency_category.category_code', 'left')
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
        $details_id = $this->input->get('details_id', TRUE);

        if ($rows = $this->inspection_model->getDetailsByID($category_code)) {
            $ct[""] = '';
            foreach ($rows as $detail) {
                $ct[$detail->details_code] = $detail->details_name;
            }
            $data = form_dropdown('detail_' + $details_id, $ct, '', 'class="span12 select_search" id="detail_' + $details_id + '" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("apartment_code") . '"');
        } else {
            $data = "";
        }
        echo $data;
    }


    function getBuildings()
    {
        $vendor_id = $this->input->get('vendor_id', TRUE);

        if ($rows = $this->inspection_model->getBuildingByVendorID($vendor_id)) {
            $ct[""] = '';
            foreach ($rows as $building) {
                $ct[$building->building_code] = $building->building_code;
            }
            $data = form_dropdown('building_code', $ct, '', 'class="span4" id="building_code" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("building_name") . '"');
        } else {
            $data = "";
        }
        echo $data;
    }



}