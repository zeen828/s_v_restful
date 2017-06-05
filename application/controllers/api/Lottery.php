<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require APPPATH . '/libraries/REST_Controller.php';
class Votes extends REST_Controller {
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
	public function mrplay_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$data_input = array ();
			$data_cache = array ();
			// 接收變數
			$data_input ['cache'] = $this->get ( 'cache' );
			$data_input ['debug'] = $this->get ( 'debug' );
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
