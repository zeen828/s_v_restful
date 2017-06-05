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
	public function event_2017_1_get() {
		$this->event_2017_1_post ();
	}
	public function event_2017_1_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			//
			$this->load->database('vidol_old_read', TRUE);
			// 變數
			$data_input = array ();
			$lottery = array();
			// 接收變數
			$data_input ['tag'] = $this->post ( 'tag' ); //
			$data_input ['debug'] = $this->post ( 'debug' );
			//
			if($data_input ['tag'] == 1){
				//mysql
				$query = $this->db->get('lottery_iphone_tbl');
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						$lottery[] = array(
								'_id'=>$row->mongo_id ,
								'member_id'=>$row->member_id ,
						);
					}
				}
			}
			$this->data_result ['lottery'] = $lottery;
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
