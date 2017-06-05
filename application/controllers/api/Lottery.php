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
	public function event_2017_1_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$data_input = array ();
			$data_cache = array ();
			// 接收變數
			$data_input ['vip'] = $this->post ( 'vip' );//
			$data_input ['start'] = $this->post ( 'start' );//開始時間
			$data_input ['end'] = $this->post ( 'end' );//結束時間
			$data_input ['cache'] = $this->post ( 'cache' );//cache暫存
			$data_input ['debug'] = $this->post ( 'debug' );
			//
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
