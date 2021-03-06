<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Devices extends REST_Controller
{

    private $data_result;

    public function __construct ()
    {
        parent::__construct();
        // 引用
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function tvbox_post ()
    {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'coupon' => false,
					'cash' => false
			);
			// 接收變數
			$data_input ['Authorization'] = $this->post ( 'Authorization' );
			$data_input ['user_no'] = $this->post ( 'user_no' );
			$data_input ['mongo_id'] = $this->post ( 'mongo_id' );
			$data_input ['member_id'] = $this->post ( 'member_id' );
			$data_input ['dealer'] = $this->post ( 'dealer' );
			$data_input ['key_word'] = $this->post ( 'key_word' );
			// 沒有資料
			if ( false ) {
				// 必填錯誤
				$this->response ( $this->data_result, 416 );
				return;
			}
			
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
    }
}
