<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
header ( 'Access-Control-Allow-Origin: *' );
header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
header( 'Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept' );
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
			$this->load->model ( 'vidol_user/phone_sms_check_model' );
			$this->load->model ( 'vidol_cron/send_sms_model' );
			$this->config->load ( 'smexpress_sms' );
			$this->config->load ( 'restful_status_code' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			// 變數
			$data_input = array ();
			// 接收變數
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
				// $data_input ['msm'] = substr ( md5 ( rand () ), 0, 6 );
				$data_input ['msm'] = rand ( 100000, 999999 );
			}
			// 寄送簡訊資料
			$send = $this->send_sms_model->insert_data ( array (
					'ss_dealer' => 'smexpress',
					'ss_phone' => $data_input ['phone'],
					//'ss_msm' => sprintf ( '你的檢查碼是[%s]請在10分鐘內註冊,註冊成功半小時內會開通認證', $data_input ['msm'] )
					'ss_msm' => sprintf ( '您的【Vidol影音】簡訊驗證碼為%s。此驗證碼15分鐘內有效。提醒您，請勿將此驗證碼提供給其他人以保障您的使用安全。若您未提出申請，請直接忽略此封簡訊，謝謝。', $data_input ['msm'] )
			) );
			// 註冊檢查資料
			$check = $this->phone_sms_check_model->insert_data ( array (
					'phone' => $data_input ['phone'],
					'code' => $data_input ['msm'],
					'expires_time_at' => strtotime ( "+15 minute" ),
					'expires_at' => date ( "Y-m-d H:00:00", strtotime ( "+15 minute" ) ),
					'status' => '1' 
			) );
			// 結果
			if (empty ( $send ) || empty ( $check )) {
				$this->data_result ['status'] = false;
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
