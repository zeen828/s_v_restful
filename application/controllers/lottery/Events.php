<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
class Events extends CI_Controller {
	// 投票資料
	private $lottery = array (
			'title' => 'Iphone 8 抽獎', // 投票標題
			'name' => 'iphone8', // 投票名稱
			'table' => 'lottery_%s_tbl',
			'table_list' => 'lottery_%s_list_tbl' 
	);
	// 回傳資料
	private $data_view;
	function __construct() {
		parent::__construct ();
		// 效能檢查
		// $this->output->enable_profiler ( TRUE );
	}
	public function index() {
		show_404 ();
	}
	public function mongo() {
		$this->load->library ( 'mongo_db' );
		
		$this->mongo_db->where ( 'member_id', 'A3X1wl' )->where ( 'contact_number', '$exists:true' );
		// $this->mongo_db->where_ne('contact_number', 'null');
		$user = $this->mongo_db->select ( array (
				'_id',
				'member_id',
				'contact_number' 
		) )->get ( '_User' );
		
		var_dump ( $user );
	}
	public function iphone8_mongo($date = '') {
		// 引用
		$this->load->driver ( 'cache', array (
				'adapter' => 'memcached',
				'backup' => 'dummy' 
		) );
		// 變數
		$data_input = array ();
		$data_cache = array ();
		$data_date = array ();
		// 接收變數
		$data_input ['date'] = $date;
		$data_input ['debug'] = $this->input->get('debug');
		$data_input ['IP'] = $this->input->ip_address ();
		if ($data_input ['IP'] != '61.216.83.7') {
			show_404 ();
			exit ();
		}
		// 開始時間
		$data_date ['start_time'] = strtotime ( $date . "-1 hour" );
		$data_date ['start'] = date ( "Y-m-d 00:00:00", $data_date ['start_time'] );
		$data_date ['start_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['start'] . "-8 hour" ) );
		$data_date ['start_mongo'] = new MongoDate ( strtotime ( $data_date ['start_utc'] ) );
		// 七天後
		$data_date ['end_time'] = strtotime ( $data_date ['start'] . "+7 day" );
		$data_date ['end'] = date ( "Y-m-d 00:00:00", $data_date ['end_time'] );
		$data_date ['end_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['end'] . "-8 hour" ) );
		$data_date ['end_mongo'] = new MongoDate ( strtotime ( $data_date ['end_utc'] ) );
		// cache name key
		$cache_name = sprintf ( '%s_get_mongo_user_%s', ENVIRONMENT, $data_input ['date'] );
		// $this->cache->memcached->delete ( $cache_name );
		$data_cache [$cache_name] = $this->cache->memcached->get ( $cache_name );
		if ($data_cache [$cache_name] == false) {
			$this->load->library ( 'mongo_db' );
			// 取得mongo會員
			$this->mongo_db->limit ( 1000 );
			$this->mongo_db->offset ( 1 );
			if (! empty ( $date )) {
				//
				$this->mongo_db->where_between ( '_created_at', $data_date ['start_mongo'], $data_date ['end_mongo'] );
			}
			$data_cache [$cache_name] = $this->mongo_db->select ( array (
					'_id',
					'member_id' 
			) )->get ( '_User' );
			$this->cache->memcached->save ( $cache_name, $data_cache [$cache_name], 3000 );
		}
		// var_dump($user);
		$this->data_view ['start'] = $data_date ['start'];
		$this->data_view ['end'] = $data_date ['end'];
		$this->data_view ['lottery'] = $data_cache [$cache_name];
		// DEBUG印出
		if ($data_input ['debug'] == 'debug') {
			var_dump($data_input);
			var_dump($data_cache);
			var_dump($data_date);
			var_dump($cache_name);
		}
		// 輸出view
		$this->load->view ( 'lottery/iphone8', $this->data_view );
	}
	
	public function iphone8($start_date = '', $end_date = '') {
		// 引用
		$this->load->driver ( 'cache', array (
				'adapter' => 'memcached',
				'backup' => 'dummy'
		) );
		// 變數
		$data_input = array ();
		$data_cache = array ();
		$data_date = array ();
		// 接收變數
		$data_input ['date'] = $date;
		$data_input ['debug'] = $this->input->get('debug');
		$data_input ['IP'] = $this->input->ip_address ();
		if ($data_input ['IP'] != '61.216.83.7') {
			show_404 ();
			exit ();
		}
		// 開始時間
		$data_date ['start_time'] = strtotime ( $start_date . "-1 hour" );
		$data_date ['start'] = date ( "Y-m-d 00:00:00", $data_date ['start_time'] );
		$data_date ['start_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['start'] . "-8 hour" ) );
		// 限制時間
		$data_date ['end_time'] = strtotime ( $end_date . "-1 hour" );
		$data_date ['end'] = date ( "Y-m-d 00:00:00", $data_date ['end_time'] );
		$data_date ['end_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['end'] . "-8 hour" ) );
		// cache name key
		$cache_name = sprintf ( '%s_get_mongo_user_%s', ENVIRONMENT, $data_input ['date'] );
		// $this->cache->memcached->delete ( $cache_name );
		$data_cache [$cache_name] = $this->cache->memcached->get ( $cache_name );
		if ($data_cache [$cache_name] == false) {
			$data_cache [$cache_name] = array();
			$this->r_pdb = $this->load->database('postgre_production_read', TRUE);
			$this->r_pdb->select('member_id');
			if (! empty ( $start_date )) {
				$this->r_pdb->where('created_at <=', $data_date ['start_utc']);
			}
			if (! empty ( $end_date )) {
				$this->r_pdb->where('created_at >', $data_date ['end_utc']);
			}
			$query = $this->r_pdb->get('ob_iphones');
			//echo $this->r_pdb->last_query();
			if ($query->num_rows () > 0) {
				foreach ( $query->result () as $row ) {
					// print_r($row);
					$data_cache [$cache_name][] = array(
							// '_id' => $row->member_id,
							'member_id' => $row->member_id
					);
				}
			}
			$this->cache->memcached->save ( $cache_name, $data_cache [$cache_name], 3000 );
		}
		// var_dump($user);
		$this->data_view ['start'] = $data_date ['start'];
		$this->data_view ['end'] = $data_date ['end'];
		$this->data_view ['lottery'] = $data_cache [$cache_name];
		// DEBUG印出
		if ($data_input ['debug'] == 'debug') {
			var_dump($data_input);
			var_dump($data_cache);
			var_dump($data_date);
			var_dump($cache_name);
		}
		// 輸出view
		$this->load->view ( 'lottery/iphone8', $this->data_view );
	}
}
