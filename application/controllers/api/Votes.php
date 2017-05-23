<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require APPPATH . '/libraries/REST_Controller.php';
class Votes extends REST_Controller {
	private $data_debug;
	private $data_result;
	public function __construct() {
		$this->data_debug = true;
		header ( 'Access-Control-Allow-Origin: *' );
		header ( 'Access-Control-Allow-Headers: X-Requested-With' );
		header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
		parent::__construct ();
	}

	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}

	public function vote_404_get() {
		$this->response ( NULL, 404 );
	}

	/**
	 * 玩很大進校園投票
	 */
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
			// 實體cache#jsonp會死掉
			// $this->output->cache(10080);//7天
			// if ($this->cache->is_supported ( 'memcached' )) {
			$cache_name = sprintf ( '%s_%s_mrplay_result', ENVIRONMENT, 'vote' );
			if ($data_input ['cache'] == 'delete') {
				$this->cache->memcached->delete ( $cache_name );
			}
			$data_cache [$cache_name] = $this->cache->memcached->get ( $cache_name );
			if ($data_cache [$cache_name] == false) {
				// 防止array組合型態錯誤警告
				$data_cache [$cache_name] = array ();
				$this->load->model ( 'vidol_old/vote_model' );
				// 1.統計投票總數(一個投票項目建一個數字)
				$sum = array (
						'1' => 0.00,
				);
				$query = $this->vote_model->get_vote_mrplay_sum ('category_no,title,SUM(ticket_add) as ticket_sum');
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$sum [$row->category_no] = $row->ticket_sum;
						unset($row);
					}
				}
				unset($query);
				// 2.投票資料
				$query = $this->vote_model->get_vote_mrplay ('v_pk as no,category_no,code,title,ticket,ticket_add');
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						// print_r($row );
						$data_cache [$cache_name] [] = array(
								'code' => $row->code,
								'title' => $row->title,
								'ticket' => ($row->ticket_add <= 0 || $sum [$row->category_no] <= 0) ? sprintf ( '%2.2f', 0 ) : sprintf ( '%2.2f', ($row->ticket_add / $sum [$row->category_no] * 100) )
						);
						unset($row);
					}
				}
				unset($query);
				unset($sum);
				$this->cache->memcached->save ( $cache_name, $data_cache [$cache_name], 86400 ); // 24H
			}
			$this->data_result ['result'] = $data_cache [$cache_name];
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['cache_name'] = $cache_name;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
			}
			unset ( $data_cache [$cache_name] );
			unset ( $cache_name );
			unset ( $data_cache );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// }
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
