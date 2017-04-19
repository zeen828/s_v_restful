<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Homes extends CI_Controller
{
    private $data_view;
    
    function __construct ()
    {
        parent::__construct();
        // 資料庫
        $this->load->database();
        // 引用
        $this->load->helper('formats');
        // 初始化
        $this->data_view = format_helper_vidol_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function index ()
    {
        $this->data_view['content']['view_path'] = 'homes/homes';
        $this->load->view('vidol/include/html5', $this->data_view);
    }
}
