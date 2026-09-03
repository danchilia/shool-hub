<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 2.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Accounting.php
 * @copyright : Reserved RamomCoders Team
 */

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect(base_url(), 'refresh');
    }

    /* global settings controller */
    public function universal()
    {
        if (!get_permission('global_settings', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('global_settings', 'is_edit')) {
                access_denied();
            }
        }

        $config = array();
        if ($this->input->post('submit') == 'setting') {
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
            }
            if (empty($config['reg_prefix'])) {
                $config['reg_prefix'] = false;
            }
            $this->db->where('id', 1);
            $this->db->update('global_settings', $config);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(current_url());
        }

        if ($this->input->post('submit') == 'theme') {
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
            }
            $this->db->where('id', 1);
            $this->db->update('theme_settings', $config);
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 2);
            redirect(current_url());
        }

        if ($this->input->post('submit') == 'company_smtp') {
            if (!get_permission('global_settings', 'is_edit')) {
                access_denied();
            }
            $config = array();
            foreach ($this->input->post() as $input => $value) {
                if ($input == 'submit') continue;
                $config[$input] = $value;
            }
            $this->db->where('id', 1)->update('global_settings', $config);
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 4);
            redirect(current_url());
        }

        if ($this->input->post('submit') == 'logo') {
            $logo_slots = array(
                'logo_file'   => array('dest' => FCPATH . 'uploads/app_image/logo.png',           'ext' => array('png')),
                'text_logo'   => array('dest' => FCPATH . 'uploads/app_image/logo-small.png',     'ext' => array('png')),
                'print_file'  => array('dest' => FCPATH . 'uploads/app_image/printing-logo.png',  'ext' => array('png')),
                'report_card' => array('dest' => FCPATH . 'uploads/app_image/report-card-logo.png', 'ext' => array('png')),
                'slider_1'    => array('dest' => FCPATH . 'uploads/login_image/slider_1.jpg',     'ext' => array('jpg', 'jpeg', 'png')),
                'slider_2'    => array('dest' => FCPATH . 'uploads/login_image/slider_2.jpg',     'ext' => array('jpg', 'jpeg', 'png')),
                'slider_3'    => array('dest' => FCPATH . 'uploads/login_image/slider_3.jpg',     'ext' => array('jpg', 'jpeg', 'png')),
            );
            foreach ($logo_slots as $field => $slot) {
                if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
                    continue;
                }
                $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $slot['ext'], true)) {
                    set_alert('error', 'Invalid file type for ' . $field . '. Allowed: ' . implode(', ', $slot['ext']));
                    $this->session->set_flashdata('active', 3);
                    redirect(current_url());
                    return;
                }
                move_uploaded_file($_FILES[$field]['tmp_name'], $slot['dest']);
            }
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->set_flashdata('active', 3);
            redirect(current_url());
        }

        $this->data['title'] = translate('global_settings');
        $this->data['sub_page'] = 'settings/universal';
        $this->data['main_menu'] = 'settings';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    /* school settings controller */
    public function school()
    {
        if (!get_permission('school_settings', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('school_settings', 'is_edit')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('branch_name', translate('branch_name'), 'trim|required|callback_unique_branchname');
            $this->form_validation->set_rules('school_name', translate('school_name'), 'trim|required');
            $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('currency', translate('currency'), 'trim|required');
            if ($this->form_validation->run() == true) {
                $this->branchUpdate($this->input->post());
                $message = translate('the_configuration_has_been_updated');
                $array = array('status' => 'success', 'message' => $message);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['title'] = translate('school_settings');
        $this->data['sub_page'] = 'settings/school';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function unique_branchname($name)
    {
        $this->db->where_not_in('id', get_loggedin_branch_id());
        $this->db->where('name', $name);
        $name = $this->db->get('branch')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_branchname", translate('already_taken'));
            return false;
        }
    }

    public function payment()
    {
        if (!get_permission('payment_settings', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;
        $this->data['config'] = $this->get_payment_config();
        $this->data['sub_page'] = 'settings/payment_gateway';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('payment_control');
        $this->load->view('layout/index', $this->data);
    }

    public function paypal_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('paypal_username', 'Paypal Username', 'trim|required');
        $this->form_validation->set_rules('paypal_password', 'Paypal Password', 'trim|required');
        $this->form_validation->set_rules('paypal_signature', 'Paypal Signature', 'trim|required');
        $this->form_validation->set_rules('paypal_email', 'Paypal Email', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $paypal_sandbox = isset($_POST['paypal_sandbox']) ? 1 : 2;
            $arrayPaypal = array(
                'paypal_username' => $this->input->post('paypal_username'),
                'paypal_password' => $this->input->post('paypal_password'),
                'paypal_signature' => $this->input->post('paypal_signature'),
                'paypal_email' => $this->input->post('paypal_email'),
                'paypal_sandbox' => $paypal_sandbox,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaypal['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaypal);
            } else {
                $this->db->where('id', $q->row()->id);
                $this->db->update('payment_config', $arrayPaypal);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function stripe_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('stripe_public_key', 'Stripe Publishable Key', 'trim|required');
        $this->form_validation->set_rules('stripe_secret', 'Stripe Secret Key', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $stripe_demo = isset($_POST['stripe_demo']) ? 1 : 2;
            $arrayPaypal = array(
                'stripe_public_key' => $this->input->post('stripe_public_key'),
                'stripe_secret' => $this->input->post('stripe_secret'),
                'stripe_demo' => $stripe_demo,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPaypal['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaypal);
            } else {
                $this->db->where('id', $q->row()->id);
                $this->db->update('payment_config', $arrayPaypal);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function payumoney_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('payumoney_key', 'Payumoney Key', 'trim|required');
        $this->form_validation->set_rules('payumoney_salt', 'Payumoney Salt', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $payumoney_demo = isset($_POST['payumoney_demo']) ? 1 : 2;
            $arrayPayumoney = array(
                'payumoney_key' => $this->input->post('payumoney_key'),
                'payumoney_salt' => $this->input->post('payumoney_salt'),
                'payumoney_demo' => $payumoney_demo,
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayPayumoney['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPayumoney);
            } else {
                $this->db->where('id', $q->row()->id);
                $this->db->update('payment_config', $arrayPayumoney);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function paystack_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
            ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('paystack_secret_key', 'Paystack API Key', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $arrayPaystack = array(
                'paystack_secret_key' => $this->input->post('paystack_secret_key'),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayMollie['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayPaystack);
            } else {
                $this->db->where('id', $q->row()->id);
                $this->db->update('payment_config', $arrayPaystack);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function razorpay_save()
    {
        if (!get_permission('payment_settings', 'is_add')) {
           ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->form_validation->set_rules('razorpay_key_id', 'Key Id', 'trim|required');
        $this->form_validation->set_rules('razorpay_key_secret', 'Key Secret', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $razorpay_demo = isset($_POST['razorpay_demo']) ? 1 : 2;
            $arrayRazorpay = array(
                'razorpay_key_id' => $this->input->post('razorpay_key_id'),
                'razorpay_key_secret' => $this->input->post('razorpay_key_secret'),
            );
            $this->db->where('branch_id', $branchID);
            $q = $this->db->get('payment_config');
            if ($q->num_rows() == 0) {
                $arrayRazorpay['branch_id'] = $branchID;
                $this->db->insert('payment_config', $arrayRazorpay);
            } else {
                $this->db->where('id', $q->row()->id);
                $this->db->update('payment_config', $arrayRazorpay);
            }
            $message = translate('the_configuration_has_been_updated');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }


    public function payment_active()
    {
        if (!get_permission('payment_settings', 'is_add')) {
           ajax_access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $paypal_status = isset($_POST['paypal_status']) ? 1 : 2;
        $stripe_status = isset($_POST['stripe_status']) ? 1 : 2;
        $payumoney_status = isset($_POST['payumoney_status']) ? 1 : 2;
        $paystack_status = isset($_POST['paystack_status']) ? 1 : 2;
        $razorpay_status = isset($_POST['razorpay_status']) ? 1 : 2;
        $arrayData = array(
            'paypal_status' => $paypal_status,
            'stripe_status' => $stripe_status,
            'payumoney_status' => $payumoney_status,
            'paystack_status' => $paystack_status,
            'razorpay_status' => $razorpay_status,
        );
        
        $this->db->where('branch_id', $branchID);
        $q = $this->db->get('payment_config');
        if ($q->num_rows() == 0) {
            $arrayData['branch_id'] = $branchID;
            $this->db->insert('payment_config', $arrayData);
        } else {
            $this->db->where('id', $q->row()->id);
            $this->db->update('payment_config', $arrayData);
        }
        $message = translate('the_configuration_has_been_updated');
        $array = array('status' => 'success', 'message' => $message);
        echo json_encode($array);
    }

    public function mpesa()
    {
        if (!is_superadmin_loggedin()) access_denied();

        if ($this->input->post('save_mpesa')) {
            $fields = array(
                'paybill_number'       => $this->input->post('paybill_number',       TRUE),
                'account_info'         => $this->input->post('account_info',         TRUE),
                'subscription_notice'  => $this->input->post('subscription_notice',  TRUE),
                'mpesa_shortcode'      => $this->input->post('mpesa_shortcode',      TRUE),
                'mpesa_consumer_key'   => $this->input->post('mpesa_consumer_key',   TRUE),
                'mpesa_consumer_secret'=> $this->input->post('mpesa_consumer_secret',TRUE),
                'mpesa_passkey'        => $this->input->post('mpesa_passkey',        TRUE),
                'mpesa_sandbox'        => $this->input->post('mpesa_sandbox') ? 1 : 0,
            );
            $this->db->where('id', 1)->update('global_settings', $fields);
            set_alert('success', 'M-Pesa settings saved successfully.');
            redirect(current_url());
        }

        $this->data['mpesa'] = $this->db->get_where('global_settings', array('id' => 1))->row_array();
        $this->data['title']     = 'M-Pesa / Paybill Settings';
        $this->data['sub_page']  = 'settings/mpesa';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function test_company_smtp()
    {
        if (!is_superadmin_loggedin()) {
            echo json_encode(array('success' => false, 'message' => 'Access denied.'));
            return;
        }

        $cfg = $this->db->select('company_email, company_smtp_host, company_smtp_port, company_smtp_user, company_smtp_pass, company_smtp_encryption')
                        ->get('global_settings')->row_array();

        if (empty($cfg['company_smtp_host']) || empty($cfg['company_smtp_user'])) {
            echo json_encode(array('success' => false, 'message' => 'No SMTP settings saved yet. Fill in and save first.'));
            return;
        }

        $config = array(
            'protocol'     => 'smtp',
            'smtp_host'    => trim($cfg['company_smtp_host']),
            'smtp_port'    => trim($cfg['company_smtp_port']),
            'smtp_user'    => trim($cfg['company_smtp_user']),
            'smtp_pass'    => trim($cfg['company_smtp_pass']),
            'smtp_crypto'  => $cfg['company_smtp_encryption'],
            'useragent'    => 'CodeIgniter',
            'mailtype'     => 'html',
            'newline'      => "\r\n",
            'charset'      => 'utf-8',
            'wordwrap'     => true,
            'smtp_timeout' => 15,
        );

        $this->load->library('email', $config);
        $this->email->from($cfg['company_email'], get_global_setting('institute_name'));
        $this->email->to($cfg['company_smtp_user']);
        $this->email->subject('CST SchoolHub — SMTP Test Email');
        $this->email->message("
            <div style='font-family:Arial,sans-serif;max-width:500px;margin:0 auto;padding:20px;'>
                <div style='background:#1a5276;padding:15px;text-align:center;border-radius:6px 6px 0 0;'>
                    <h2 style='color:#fff;margin:0;'>CST SchoolHub</h2>
                </div>
                <div style='background:#fff;border:1px solid #ddd;padding:25px;border-radius:0 0 6px 6px;'>
                    <h3 style='color:#27ae60;'>&#10003; SMTP Test Successful</h3>
                    <p>Your company email is configured correctly.</p>
                    <p><strong>From:</strong> {$cfg['company_email']}<br>
                    <strong>Host:</strong> {$cfg['company_smtp_host']}:{$cfg['company_smtp_port']}<br>
                    <strong>Encryption:</strong> {$cfg['company_smtp_encryption']}</p>
                </div>
            </div>
        ");

        if ($this->email->send(true)) {
            echo json_encode(array('success' => true, 'message' => 'Test email sent to ' . $cfg['company_smtp_user'] . '. Check your inbox.'));
        } else {
            $debug = $this->email->print_debugger(array('headers'));
            echo json_encode(array('success' => false, 'message' => 'Failed to send. Check SMTP details. Error: ' . strip_tags($debug)));
        }
    }

    public function branchUpdate($data)
    {
        $arrayBranch = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => $data['currency'],
            'symbol' => $data['currency_symbol'],
            'city' => $data['city'],
            'state' => $data['state'],
            'address' => $data['address'],
        );
        $this->db->where('id', get_loggedin_branch_id());
        $this->db->update('branch', $arrayBranch);
    }
}
