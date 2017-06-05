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
	public function event_2017_1($vip = 0) {
		//
		$this->load->library('mongo_db');
		//取得mongo會員
		$this->mongo_db->limit(100);
		$this->mongo_db->offset(1);
		//$this->mongo_db->order_by(array('_created_at'=>'asc'));
		$user = $this->mongo_db->select(array('_id', 'member_id'))->get('_User');
		//var_dump($user);
		$this->data_view['vip'] = $vip;
		$this->data_view['lottery'] = $user;
		// 輸出view
		$this->load->view ( 'lottery/event_2017_1', $this->data_view );
	}
}
