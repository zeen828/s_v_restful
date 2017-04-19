<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Vote_model extends CI_Model
{
	
    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('vidol_old_read', TRUE);
        $this->w_db = $this->load->database('vidol_old_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function get_vote_sum ()
    {
    	$this->r_db->select('category_no,video_id_no,SUM(ticket_add) as tickets');
        $this->r_db->group_by('category_no');
        $query = $this->r_db->get('vote_tbl');
        //echo $this->r_db->last_query();
        return $query;
    }
    
    public function get_vote ()
    {
    	$this->r_db->select('category_no,video_id_no,ticket,ticket_add');
        $this->r_db->group_by(array('category_no', 'video_id_no'));
        $this->r_db->order_by('category_no', 'ASC');
        $this->r_db->order_by('video_id_no', 'ASC');
        $query = $this->r_db->get('vote_tbl');
        //echo $this->r_db->last_query();
        return $query;
    }
}
