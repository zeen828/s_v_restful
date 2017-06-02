<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demos extends CI_Controller
{
	private $data_view;

	function __construct ()
	{
		parent::__construct();
		// 效能檢查
		$this->output->enable_profiler(TRUE);
	}

	public function index ()
	{
		echo 'index';
	}
}
