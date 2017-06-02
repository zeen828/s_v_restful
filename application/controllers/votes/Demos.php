<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demos extends CI_Controller
{
	// 投票資料
	private $votes = array(
			'title' => '投票範例',//投票標題
			'name' => 'demos',//投票名稱
			'table' => 'vote_mrplay_tbl',//投票資料庫使用表單
			'table_list' => 'vote_mrplay_list_tbl',//投票統計資料庫使用表單
	);
	// 回傳資料
	private $data_view;

	function __construct ()
	{
		parent::__construct();
		// 效能檢查
		$this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		echo '投票首頁';
		var_dump($this->votes);
	}
}
