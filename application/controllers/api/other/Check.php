<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require_once APPPATH . '/libraries/REST_Controller.php';
class Check extends REST_Controller {
	private $data_result;
	public function __construct() {
		parent::__construct ();
		// 引用
		$this->load->helper ( 'formats' );
		// 初始化
		$this->data_result = format_helper_return_data ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function sms_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$data_input = array ();
			// 接收變數
			$data_input ['Authorization'] = $this->post ( 'Authorization' );
			$data_input ['phone'] = $this->post ( 'phone' );
			$data_input ['code'] = $this->post ( 'code' );
			
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
