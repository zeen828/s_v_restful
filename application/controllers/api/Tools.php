<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require APPPATH . '/libraries/REST_Controller.php';
class Tools extends REST_Controller {
	private $data_result;
	public function __construct() {
		header ( 'Access-Control-Allow-Origin: *' );
		header ( 'Access-Control-Allow-Headers: X-Requested-With' );
		header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
		parent::__construct ();
	}
	public function vote_404_get() {
		$this->response ( NULL, 404 );
	}
	//華劇大賞投票
	public function vote_get() {
		// mysql
		try {
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 實體cache#jsonp會死掉
			// $this->output->cache(10080);//7天
			// if ($this->cache->is_supported ( 'memcached' )) {
			$cache_name = sprintf ( '%s_%s_data_result', ENVIRONMENT, 'vote' );
			//$this->cache->memcached->delete ( $cache_name );
			$this->data_result = $this->cache->memcached->get ( $cache_name );
			if (empty ( $this->data_result )) {
				$this->load->model ( 'vidol_old/vote_model' );
				// sum
				$sum = array (
						'1' => 0.00,
						'2' => 0.00,
						'3' => 0.00,
						'4' => 0.00,
						'5' => 0.00,
						'6' => 0.00,
						'7' => 0.00,
						'8' => 0.00,
						'9' => 0.00 
				);
				$query = $this->vote_model->get_vote_sum ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$sum [$row->category_no] = $row->tickets;
					}
				}
				// sum
				$query = $this->vote_model->get_vote ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$this->data_result [$row->category_no] [$row->video_id_no] = ($row->ticket_add <= 0 || $sum [$row->category_no] <= 0) ? sprintf ( '%2.2f', 0 ) : sprintf ( '%2.2f', ($row->ticket_add / $sum [$row->category_no] * 100) );
					}
				}
				$this->data_result ['cache_name'] = $cache_name;
				$this->data_result ['cache_time'] = date ( 'Y-m-d h:i:s' );
				$this->cache->memcached->save ( $cache_name, $this->data_result, 86400 ); // 24H
			}
			// }
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	//華劇大賞投票-明星下午茶
	public function afternoon_get() {
		// mysql
		try {
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 實體cache#jsonp會死掉
			// $this->output->cache(10080);//7天
			// if ($this->cache->is_supported ( 'memcached' )) {
			$cache_name = sprintf ( '%s_%s_data_result', ENVIRONMENT, 'afternoon' );
			//$this->cache->memcached->delete ( $cache_name );
			$this->data_result = $this->cache->memcached->get ( $cache_name );
			if (empty ( $this->data_result )) {
				$this->load->model ( 'vidol_old/afternoon_model' );
				// sum
				$sum = array (
						'1' => 0.00,
						'2' => 0.00 
				);
				$query = $this->afternoon_model->get_afternoon_sum ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$sum [$row->category_no] = $row->tickets;
					}
				}
				// sum
				$query = $this->afternoon_model->afternoon_vote ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$this->data_result [$row->category_no] [$row->video_id_no] = ($row->ticket_add <= 0 || $sum [$row->category_no] <= 0) ? sprintf ( '%2.2f', 0 ) : sprintf ( '%2.2f', ($row->ticket_add / $sum [$row->category_no] * 100) );
					}
				}
				$this->data_result ['cache_name'] = $cache_name;
				$this->data_result ['cache_time'] = date ( 'Y-m-d h:i:s' );
				$this->cache->memcached->save ( $cache_name, $this->data_result, 300 );
			}
			// }
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
