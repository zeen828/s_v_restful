<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
header ( 'Access-Control-Allow-Origin: *' );
header ( 'Access-Control-Allow-Headers: X-Requested-With' );
header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
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
			// 引入
			$this->load->model ( 'vidol_user/phone_sms_check_model' );
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			// 變數
			$data_input = array ();
			// 接收變數
			$data_input ['phone'] = $this->get ( 'phone' );
			$data_input ['code'] = $this->get ( 'code' );
			// 查詢
			$check = $this->phone_sms_check_model->get_row_by_phone_code ( '*', $data_input ['phone'], $data_input ['code'] );
			if ($check == false) {
				// 必填錯誤
				$this->data_result ['status'] = false;
				$this->response ( $this->data_result, 404 );
				return;
			} else {
				$this->data_result ['status'] = true;
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
