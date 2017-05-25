<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
/**
 * crontab 指令
 * crontab -l 查詢任務
 * crontab -e 編輯任務
 * /etc/init.d/cron restart 重啟
 */
class Votes extends CI_Controller {
	private $data_debug;
	private $data_result;
	function __construct() {
		parent::__construct ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function index() {
		show_404 ();
	}

	/**
	 * 統計投票數
	 */
	public function mrplay() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$data_input = array ();
			$data_cache = array ();
			// 接收變數
			$data_input ['cache'] = $this->input->get ( 'cache' );
			$data_input ['debug'] = $this->input->get ( 'debug' );
			// memcached
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			$cache_name = sprintf ( '%s_%s_mrplay_result', ENVIRONMENT, 'vote' );
			// 防止array組合型態錯誤警告
			$data_cache [$cache_name] = array ();
			$this->load->model ( 'vidol_old/vote_model' );
			// 1.統計投票總數(一個投票項目建一個數字)
			$sum = array (
					'1' => 0
			);
			$query = $this->vote_model->get_vote_mrplay_sum ( 'category_no,title,SUM(ticket_add) as ticket_sum' );
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// print_r($row );
					$sum [$row->category_no] = $row->ticket_sum - 1;
					unset ( $row );
				}
			}
			unset ( $query );
			// 2.投票資料
			$query = $this->vote_model->get_vote_mrplay ( 'v_pk as no,category_no,code,title,ticket,ticket_add' );
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// print_r($row );
					$data_cache [$cache_name] [] = array (
							'code' => $row->code,
							'title' => $row->title,
							'ticket' => ($row->ticket_add <= 1 || $sum [$row->category_no] <= 0) ? sprintf ( '%2.2f', 0 ) : sprintf ( '%2.2f', ($row->ticket_add / $sum [$row->category_no] * 100) )
					);
					unset ( $row );
				}
			}
			unset ( $query );
			$this->cache->memcached->save ( $cache_name, $data_cache [$cache_name], 86400 );// 24H
			$this->data_result ['result'] = $data_cache [$cache_name];
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['ENVIRONMENT'] = ENVIRONMENT;
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['cache_name'] = $cache_name;
				$this->data_result ['debug'] ['cache_time'] = date ( 'Y-m-d h:i:s' );
				$this->data_result ['debug'] ['sum'] = $sum;
			}
			unset ( $data_cache [$cache_name] );
			unset ( $cache_name );
			unset ( $data_cache );
			unset ( $data_input );
			unset ( $sum );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			//
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $this->data_result ) );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
