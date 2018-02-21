<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MX_Controller
{


    function __construct()
    {
        parent::__construct();

        // check if user logged in
        if (!$this->ion_auth->logged_in()) {
            redirect('module=auth&view=login');
        }

        $groups = array('admin', 'purchaser', 'salesman');
        if ($this->ion_auth->in_group($groups)) {
            $this->session->set_flashdata('message', $this->lang->line('access_denied'));
            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            redirect('module=home', 'refresh');
        }

        $this->load->library('form_validation');
//		$this->load->model(array('settings_model'));
        $this->load->model('settings_model');


    }

    function index()
    {
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $meta['page_title'] = $this->lang->line('setting');
        $data['page_title'] = $this->lang->line('setting');
        $this->load->view('commons/header', $meta);
        $this->load->view('content', $data);
        $this->load->view('commons/footer');

    }

    function system_setting()
    {


        //validate form input
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('site_name', $this->lang->line('site_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('language', $this->lang->line('language'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('currency_prefix', $this->lang->line('currency_code'), 'trim|required|max_length[3]|xss_clean');
        $this->form_validation->set_rules('types', $this->lang->line('types'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_format', $this->lang->line('date_format'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('rows_per_page', $this->lang->line('rows_per_page'), 'trim|required|greater_than[9]|less_than[501]|xss_clean');
        $this->form_validation->set_rules('total_rows', $this->lang->line('total_rows'), 'trim|required|greater_than[9]|less_than[100]|xss_clean');


        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
                redirect("module=home", 'refresh');
            }
            $language = $this->input->post('language');

            if ((file_exists('sma/language/' . $language . '/sma_lang.php') && is_dir('sma/language/' . $language)) || $language == 'en') {
                $lang = $language;
            } else {
                $this->session->set_flashdata('message', $this->lang->line('language_x_found'));
                redirect("module=settings&view=system_setting", 'refresh');
                $lang = 'en';
            }

            if ($this->input->post('tax_rate') != 0) {
                $tax1 = 1;
            } else {
                $tax1 = 0;
            }
            if ($this->input->post('tax_rate2') != 0) {
                $tax2 = 1;
            } else {
                $tax2 = 0;
            }

            $data = array('site_name' => $this->input->post('site_name'),
                'language' => $lang,
                'currency_prefix' => $this->input->post('currency_prefix'),
                'client_type' => $this->input->post('types'),
                'dateformat' => $this->input->post('date_format'),
                'rows_per_page' => $this->input->post('rows_per_page'),
                'total_rows' => $this->input->post('total_rows')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            $this->session->set_flashdata('success_message', $this->lang->line('setting_updated'));
            redirect("module=settings&view=system_setting", 'refresh');
        } else {

            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['success_message'] = $this->session->flashdata('success_message');

            $data['settings'] = $this->settings_model->getSettings();
            $data['date_formats'] = $this->settings_model->getDateFormats();
            $data['types'] = $this->settings_model->getTypes();
            $meta['page_title'] = $this->lang->line('system_setting');
            $data['page_title'] = $this->lang->line('system_setting');
            $this->load->view('commons/header', $meta);
            $this->load->view('setting', $data);
            $this->load->view('commons/footer');
        }
    }


    function change_logo()
    {

        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
                redirect("module=home", 'refresh');
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload_photo');

                $config['upload_path'] = 'assets/img/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '300';
                $config['max_width'] = '200';
                $config['max_height'] = '40';
                $config['overwrite'] = FALSE;

                $this->upload_photo->initialize($config);

                if (!$this->upload_photo->do_upload()) {

                    $error = $this->upload_photo->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=settings&view=change_logo", 'refresh');
                }

                $photo = $this->upload_photo->file_name;

            } else {
                $this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
                redirect("module=settings&view=change_logo", 'refresh');
            }


        }

        if ($this->form_validation->run() == true && $this->settings_model->updateLogo($photo)) {
            $this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
            redirect("module=settings&view=change_logo", 'refresh');
        } else {

            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['success_message'] = $this->session->flashdata('success_message');

            $meta['page_title'] = $this->lang->line('change_logo');
            $data['page_title'] = $this->lang->line('change_logo');
            $this->load->view('commons/header', $meta);
            $this->load->view('logo', $data);
            $this->load->view('commons/footer');

        }
    }

    function change_login_logo()
    {

        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
                redirect("module=home", 'refresh');
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload_photo');

                $config['upload_path'] = 'assets/img/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '300';
                $config['max_width'] = '300';
                $config['max_height'] = '80';
                $config['overwrite'] = FALSE;

                $this->upload_photo->initialize($config);

                if (!$this->upload_photo->do_upload()) {

                    $error = $this->upload_photo->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=settings&view=change_logo", 'refresh');
                }

                $photo = $this->upload_photo->file_name;

            } else {
                $this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
                redirect("module=settings&view=change_logo", 'refresh');
            }

        }

        if ($this->form_validation->run() == true && $this->settings_model->updateLoginLogo($photo)) {
            $this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
            redirect("module=settings&view=change_logo", 'refresh');
        } else {

            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['success_message'] = $this->session->flashdata('success_message');

            $meta['page_title'] = $this->lang->line('change_logo');
            $data['page_title'] = $this->lang->line('change_logo');
            $this->load->view('commons/header', $meta);
            $this->load->view('logo', $data);
            $this->load->view('commons/footer');

        }
    }

    function upload_biller_logo()
    {
        $this->form_validation->set_rules('userfile', 'Logo Image', 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line('disabled_in_demo'));
                redirect("module=home", 'refresh');
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload_photo');

                $config['upload_path'] = 'assets/uploads/logos/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '300';
                $config['max_width'] = '300';
                $config['max_height'] = '80';
                $config['overwrite'] = FALSE;

                $this->upload_photo->initialize($config);

                if (!$this->upload_photo->do_upload()) {

                    $error = $this->upload_photo->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("module=settings&view=upload_biller_logo", 'refresh');
                }

                $photo = $this->upload_photo->file_name;

            } else {
                $this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
                redirect("module=settings&view=upload_biller_logo", 'refresh');
            }

        }

        if (!empty($photo)) {
            $this->session->set_flashdata('success_message', $this->lang->line('biller_logo_uploaded'));
            redirect("module=settings&view=upload_biller_logo", 'refresh');
        } else {

            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $data['success_message'] = $this->session->flashdata('success_message');


            $meta['page_title'] = $this->lang->line('upload_biller_logo');
            $data['page_title'] = $this->lang->line('upload_biller_logo');
            $this->load->view('commons/header', $meta);
            $this->load->view('upload_biller_logo', $data);
            $this->load->view('commons/footer');

        }
    }

    function backup_database()
    {
        $this->load->dbutil();

        $prefs = array(
            'format' => 'zip',
            'filename' => 'sma_db_backup.sql'
        );


        $backup =& $this->dbutil->backup($prefs);
        $this->dbutil->optimize_database();

        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
        $save = 'assets/DB_BACKUPS/' . $db_name;

        $this->load->helper('file');
        write_file($save, $backup);


        $this->load->helper('download');
        force_download($db_name, $backup);
    }



}