<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demos extends CI_Controller
{
	// 投票資料
	private $votes = array(
			'title' => '投票範例',//投票標題
			'name' => 'demos',//投票名稱
			'table' => 'vote_%s_tbl',//投票資料庫使用表單
			'table_list' => 'vote_%s_list_tbl',//投票統計資料庫使用表單
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
	
	public function install_db ()
	{
		$this->load->database ( 'vidol_old_write', TRUE );
		$table_name = sprintf($this->votes['table'], $this->votes['name']);
		if ($this->db->table_exists($table_name))
		{
			echo $table_name, "-O", "<br/>\n";
			$this->db->add_field(array(
					'id' => array(
							'type' => 'int',
							'constraint' => 11,
							'unsigned' => TRUE,
							'auto_increment' => TRUE,
							'comment' => '',
					),
					'member_id' => array(
							'type' => 'varchar',
							'constraint' => '10',
							'comment' => '會員編號',
					),
					'member_email' => array(
							'type' => 'varchar',
							'constraint' => '255',
							'comment' => '會員信箱',
					),
					'member_birthday' => array(
							'type' => 'date',
							'comment' => '會員生日',
					),
					'member_gender' => array(
							'type' => 'tinyint',
							'constraint' => 1,
							'comment' => '會員性別',
					),
					'member_created_at' => array(
							'type' => 'timestamp',
							'comment' => '會員建立日期',
					),
					'no' => array(
							'type' => 'int',
							'constraint' => '10',
							'comment' => '獎項',
					),
					'code' => array(
							'type' => 'int',
							'constraint' => '10',
							'comment' => '投票項目',
					),
					'ticket' => array(
							'type' => 'int',
							'constraint' => '10',
							'comment' => '投票數',
					),
					'year_at' => array(
							'type' => 'tinyint',
							'constraint' => '4',
							'comment' => '投票年',
					),
					'month_at' => array(
							'type' => 'tinyint',
							'constraint' => '2',
							'comment' => '投票月',
					),
					'day_at' => array(
							'type' => 'tinyint',
							'constraint' => '2',
							'comment' => '頭跳天',
					),
					'hour_at' => array(
							'type' => 'tinyint',
							'constraint' => '2',
							'comment' => '投票時',
					),
					'minute_at' => array(
							'type' => 'tinyint',
							'constraint' => '2',
							'comment' => '投票分',
					),
					'created_at' => array(
							'type' => 'TIMESTAMP',
							'comment' => '投票建立時間',
					),
			));
			$this->db->add_key('id', TRUE);
			$this->db->create_table($table_name);
		}else{
			echo $table_name, "-X", "<br/>\n";
		}
		unset($table_name);
		$table_list_name = sprintf($this->votes['table_list'], $this->votes['name']);
		if ($this->db->table_exists($table_list_name))
		{
			echo $table_list_name, "-O", "<br/>\n";
			$this->db->add_field(array(
					'blog_id' => array(
							'type' => 'INT',
							'constraint' => 5,
							'unsigned' => TRUE,
							'auto_increment' => TRUE
					),
					'blog_title' => array(
							'type' => 'VARCHAR',
							'constraint' => '100',
					),
					'blog_description' => array(
							'type' => 'TEXT',
							'null' => TRUE,
					),
			));
			$this->db->add_key('blog_id', TRUE);
			$this->db->create_table($table_list_name);
		}else{
			echo $table_list_name, "-X", "<br/>\n";
		}
		unset($table_list_name);
	}
	
}
