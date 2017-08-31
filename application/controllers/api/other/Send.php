<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require_once APPPATH . '/libraries/REST_Controller.php';
class Send extends REST_Controller {
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
	public function sms_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->config->load ( 'smexpress_sms' );
			// 變數
			$data_input = array ();
			// 接收變數
			$data_input ['Authorization'] = $this->post ( 'Authorization' );
			$data_input ['phone'] = $this->post ( 'phone' );
			$data_input ['msm'] = $this->post ( 'msm' );
			// 必填&格式判斷
			if (empty ( $data_input ['phone'] ) || ! preg_match ( "/^09\d{2}-?\d{3}-?\d{3}$/", $data_input ['phone'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				// 必填錯誤標記
				$this->benchmark->mark ( 'error_required' );
				$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'error_required' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 整理資料
			if (empty ( $data_input ['msm'] )) {
				$data_input ['msm'] = substr ( md5 ( rand () ), 0, 6 );
			}
			$sms_array = array (
					'username' => $this->config->item ( 'sms_usermname' ),
					'password' => $this->config->item ( 'sms_password' ),
					'dstaddr' => $data_input ['phone'],
					'DestName' => 'vidol',
					'encoding' => 'UTF8',
					'smbody' => sprintf ( '你的檢查碼是[%s]', $data_input ['msm'] ) 
			);
			$url_query = http_build_query ( $sms_array );
			$this->data_result['sms_array'] = $sms_array;
			$this->data_result['url_query'] = $url_query;
			// 寫資料庫
			// 發送簡訊
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
