<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require APPPATH . '/libraries/REST_Controller.php';
class Votes extends REST_Controller {
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
	//玩很大進校園投票
	public function mrplay_get() {
		// mysql
		try {
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			// 實體cache#jsonp會死掉
			// $this->output->cache(10080);//7天
			// if ($this->cache->is_supported ( 'memcached' )) {
			$cache_name = sprintf ( '%s_%s_mrplay_result', ENVIRONMENT, 'vote' );
			$this->cache->memcached->delete ( $cache_name );
			$this->data_result = $this->cache->memcached->get ( $cache_name );
			if (empty ( $this->data_result )) {
				$this->load->model ( 'vidol_old/vote_model' );
				// 統計投票總數
				$sum = array (
						'1' => 0.00,
				);
				$query = $this->vote_model->get_vote_mrplay_sum ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$sum [$row->no] = $row->ticket_sum;
					}
				}
				// 投票資料
				$query = $this->vote_model->get_vote_mrplay ();
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$this->data_result [$row->no] = ($row->ticket_add <= 0 || $sum [$row->no] <= 0) ? sprintf ( '%2.2f', 0 ) : sprintf ( '%2.2f', ($row->ticket_add / $sum [$row->no] * 100) );
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
}
