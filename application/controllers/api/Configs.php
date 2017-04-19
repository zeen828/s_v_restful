<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Configs extends REST_Controller
{

    private $data_result;

    public function __construct ()
    {
        parent::__construct();
        // 引用
        $this->load->helper('formats');
        // 初始化
        $this->data_result = format_helper_return_data();
        // 效能檢查
        // $this->output->enable_profiler(TRUE);
    }

    public function slide_get ()
    {
    	try {
	        $this->output->cache(2880);
	        $this->data_result = array(
	                'Title' => '最新資訊',
	                'Day1' => array(),
	                'Day2' => array(),
	                'Day3' => array(),
	                'Day4' => array(),
	                'Day5' => array(),
	                'Day6' => array(),
	                'Day0' => array()
	        );
	        $query = $this->db->query("SELECT * FROM `Program_tbl` WHERE `p_type` = '最新資訊' ORDER BY `p_week` ASC, `p_time_start` ASC;");
	        while ($row = $query->unbuffered_row()) {
	            if ($row->p_time_end != '00:00:00') {
	                $time = sprintf("%s ~ %s", $row->p_time_start, $row->p_time_end);
	            } else {
	                $time = $row->p_time_start;
	            }
	            $this->data_result[$row->p_week][] = array(
	                    'title' => $row->p_title,
	                    'time' => $time,
	                    'item_type' => $row->p_item_type,
	                    'id' => $row->p_video_id,
	                    'tag' => $row->p_tag
	            );
	        }
	        $this->response($this->data_result, 200);
        } catch ( Exception $e ) {
        	show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
        }
    }
    
    public function ios_get ()
    {
    	try {
	    	//$this->output->cache(2880);
	    	$build_id = $this->get('build_id');
	    	$this->data_result = array(
	    			'0.0.0' => array(
	    					'air' => 'true'
	    			),
	    			'1.0.52' => array(
	    					'air' => 'false'
	    			),
	    			'1.1.0' => array(
	    					'air' => 'false'
	    			),
	    			'1.2.0' => array(
	    					'air' => 'false'
	    			)
	    	);
	    	if (! array_key_exists($build_id, $this->data_result)) {
	    		$build_id = '0.0.0';
	    	}
	    	$this->response($this->data_result[$build_id], 200);
    	} catch ( Exception $e ) {
    		show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
    	}
    }
}
