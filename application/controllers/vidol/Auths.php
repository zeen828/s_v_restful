<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auths extends CI_Controller
{

    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 引用
        $this->config->load('facebook');
        $this->load->helper('formats');
        // 效能檢查
        $this->output->enable_profiler(TRUE);
    }

    public function login ()
    {
        $this->load->model('facebook_model');
        $fb_login_url = $this->facebook_model->get_login_url();
        echo '<a href="' . $fb_login_url . '">Log in with Facebook!</a>';
    }
    
    public function facebook ()
    {
        $this->load->model('facebook_model');
        $this->facebook_model->get_access_token();
        $user = $this->facebook_model->get_user();
        print_r($user);
    }

    public function logout ()
    {
        $this->load->view('vidol/include/html5');
    }
}
