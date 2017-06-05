<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require APPPATH . '/libraries/REST_Controller.php';
class Lottery extends REST_Controller {
	private $data_debug;
	private $data_result;
	public function __construct() {
		$this->data_debug = true;
		parent::__construct ();
	}
	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}
	public function index_get() {
		$this->response ( NULL, 404 );
	}
	/**
	 * 取得中獎名單
	 */
	public function iphone8_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引用
			$this->load->model ( 'vidol_old/lottery_model' );
			// 變數
			$data_input = array ();
			$lottery = array ();
			// 接收變數
			$data_input ['debug'] = $this->get ( 'debug' );
			// mysql
			$query = $this->lottery_model->get_lottery ();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					$lottery [] = array (
							'_id' => $row->mongo_id,
							'member_id' => $row->member_id 
					);
					unset ( $row );
				}
			}
			unset ( $query );
			$this->data_result ['lottery'] = $lottery;
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			unset ( $lottery );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	/**
	 * 紀錄中獎名單
	 */
	public function iphone8_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引用
			$this->load->model ( 'vidol_old/lottery_model' );
			// 變數
			$data_input = array ();
			// 接收變數
			$data_input ['mongo_id '] = $this->post ( 'mongo_id ' );
			$data_input ['member_id '] = $this->post ( 'member_id ' );
			$data_input ['debug'] = $this->post ( 'debug' );
			// mysql
			$query = $this->lottery_model->add_lottery_list ( $data_input ['mongo_id '], $data_input ['member_id '] );
			unset ( $query );
			$this->data_result ['lottery'] = $lottery;
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
