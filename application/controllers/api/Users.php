<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller
{

    private $data_result;

    public function __construct ()
    {
        parent::__construct();
        // 引用
        $this->load->helper('token');
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler ( TRUE );
    }

    public function index_get ()
    {
        $this->response(NULL, 404);
    }

    public function send_mail_verify_post ()
    {
    	try {
    		// 開始時間標記
    		$this->benchmark->mark ( 'code_start' );
    		// 引入
    		$this->config->load ( 'vidol' );
    		$this->config->load ( 'restful_status_code' );
    		$this->lang->load ( 'vidol', 'traditional-chinese' );
    		$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
    		$this->load->library ( 'mongo_db' );
    		$this->load->model ( 'vidol_cron/send_mail_model' );
    		$this->load->helper ( 'string' );
    		// 變數
    		$data_input = array ();
    		$this->data_result = array (
    				'result' => array (),
    				'code' => $this->config->item ( 'system_default' ),
    				'message' => '',
    				'time' => 0
    		);
    		// 接收變數
    		$data_input ['email'] = $this->post ( 'email' );
    		$data_input ['debug'] = $this->post ( 'debug' );
    		// 必填檢查
    		if (empty ( $data_input ['email'] )) {
    			// 必填錯誤
    			$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
    			$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
    			$this->response ( $this->data_result, 416 );
    			return;
    		}
    		// 取得會員資料
    		$data_mongo = $this->mongo_db->where ( array (
    				'email' => $data_input ['email']
    		) )->get ( '_User' );
    		$data_mongo = array_shift ( $data_mongo );
    		//print_r($data_mongo);
    		//exit();
    		if (empty ( $data_mongo ) || empty ( $data_mongo ['_email_verify_token'] ) || empty ( $data_mongo ['member_id'] )) {
    			// 資料格式錯誤
    			$this->data_result ['message'] = $this->lang->line ( 'database_format_error' );
    			$this->data_result ['code'] = $this->config->item ( 'database_format_error' );
    			$this->response ( $this->data_result, 400 );
    			return;
    		}
    		// 標題
    		$email_title = $this->lang->line ( 'email_verify_title' );
    		// 信件內容
    		$email_content = $this->lang->line ( 'email_verify_content' );
    		// 取變數
    		$email_user_verify_vidol_uri = $this->config->item ( 'email_user_verify_vidol_uri' );
    		$email_user_verify_uri = $this->config->item ( 'email_user_verify_uri' );
    		$email_user_verify_doman = $this->config->item ( 'email_user_verify_doman' );
    		$email_user_verify_id = $this->config->item ( 'email_user_verify_id' );
    		$email_uri = sprintf ( $email_user_verify_uri, $email_user_verify_doman, $email_user_verify_id, $data_mongo ['_email_verify_token'], $data_mongo ['email'], $data_input ['member_id'] );
    		$email_url = sprintf ( $email_user_verify_vidol_uri, urlencode ( $email_uri ) );
    		// chrome
    		// 主要網址不能urlencode會變搜尋,傳遞資料要urlencode否則會被當特殊字完處理
    		$content = sprintf ( $email_content, $email_url, $email_data );
    		// 紀錄
    		$data_insert = array (
    				'sm_source' => 'android_verify',
    				'sm_user_accounts_no' => 0,
    				'sm_from' => $this->config->item ( 'email_from' ),
    				'sm_to' => $data_mongo ['email'],
    				'sm_reply_to' => '',
    				'sm_cc' => '',
    				'sm_bcc' => json_encode ( $this->config->item ( 'email_bcc' ) ),
    				'sm_title' => $email_title,
    				'sm_content' => $content
    		);
    		$insert_id = $this->send_mail_model->insert_for_data ( $data_insert );
    		// 成功
    		$this->data_result ['result'] = $insert_id;
    		$this->data_result ['message'] = $this->lang->line ( 'system_success' );
    		$this->data_result ['code'] = $this->config->item ( 'system_success' );
    		// DEBUG印出
    		if ($data_input ['debug'] == 'debug') {
    			$this->data_result ['debug'] ['data_input'] = $data_input;
    			$this->data_result ['debug'] ['data_mongo'] = $data_mongo;
    			$this->data_result ['debug'] ['email_title'] = $email_title;
    			$this->data_result ['debug'] ['email_content'] = $email_content;
    			$this->data_result ['debug'] ['email_user_verify_vidol_uri'] = $email_user_verify_vidol_uri;
    			$this->data_result ['debug'] ['email_user_verify_uri'] = $email_user_verify_uri;
    			$this->data_result ['debug'] ['email_user_verify_doman'] = $email_user_verify_doman;
    			$this->data_result ['debug'] ['email_user_verify_id'] = $email_user_verify_id;
    			$this->data_result ['debug'] ['email_uri'] = $email_uri;
    			$this->data_result ['debug'] ['email_url'] = $email_url;
    			$this->data_result ['debug'] ['content'] = $content;
    			$this->data_result ['debug'] ['data_insert'] = $data_insert;
    		}
    		unset ( $data_insert );
    		unset ( $content );
    		unset ( $email_url );
    		unset ( $email_uri );
    		unset ( $email_user_verify_id );
    		unset ( $email_user_verify_doman );
    		unset ( $email_user_verify_uri );
    		unset ( $email_user_verify_vidol_uri );
    		unset ( $email_content );
    		unset ( $email_title );
    		unset ( $data_mongo );
    		unset ( $data_input );
    		// 結束時間標記
    		$this->benchmark->mark ( 'code_end' );
    		// 標記時間計算
    		$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
    		$this->response ( $this->data_result, 200 );
    	} catch ( Exception $e ) {
    		show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
    	}
    }
}
