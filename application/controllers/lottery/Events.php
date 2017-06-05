<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
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
		$this->output->enable_profiler ( TRUE );
	}
	public function index() {
		show_404();
	}
	public function iphone8($date = '') {
		// 引用
		$this->load->library('mongo_db');
		// 變數
		$data_date = array ();
		// 開始時間
		$data_date ['start_time'] = strtotime ( $date . "-1 hour" );
		$data_date ['start'] = date ( "Y-m-d 00:00:00", $data_date ['start_time'] );
		$data_date ['start_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['start'] . "-8 hour" ) );
		$data_date ['start_mongo'] = new MongoDate(strtotime($data_date ['start_utc']));
		// 七天後
		$data_date ['end_time'] = strtotime ( $data_date ['start'] . "+7 day" );
		$data_date ['end'] = date ( "Y-m-d 00:00:00", $data_date ['end_time'] );
		$data_date ['end_utc'] = date ( "Y-m-d H:i:s", strtotime ( $data_date ['end'] . "-8 hour" ) );
		$data_date ['end_mongo'] = new MongoDate(strtotime($data_date ['end_utc']));
		//var_dump($data_date);
		//取得mongo會員
		$this->mongo_db->limit(200);
		$this->mongo_db->offset(1);
		if(!empty($date)){
			//
			$this->mongo_db->where_between('_created_at', $data_date ['start_mongo'], $data_date ['end_mongo']);
		}
		$user = $this->mongo_db->select(array('_id', 'member_id'))->get('_User');
		//var_dump($user);
		$this->data_view['start'] = $data_date ['start'];
		$this->data_view['end'] = $data_date ['end'];
		$this->data_view['lottery'] = $user;
		// 輸出view
		$this->load->view ( 'lottery/iphone8', $this->data_view );
	}
}
