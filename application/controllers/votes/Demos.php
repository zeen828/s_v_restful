<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demos extends CI_Controller
{
	// 投票標題
	private $votes_title = '投票範例';
	// 投票資料庫使用表單
	private $votes_table = 'vote_mrplay_tbl';
	// 投票統計資料庫使用表單
	private $votes_list_table = 'vote_mrplay_list_tbl';
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
		echo $this->votes_title;
		echo $this->votes_table;
		echo $this->votes_list_table;
		echo $this->data_view;
	}
}
