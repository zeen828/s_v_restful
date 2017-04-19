<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Barrage_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        // $this->r_db = $this->load->database('vidol_websocket_read', TRUE);
        $this->w_db = $this->load->database('vidol_websocket_write', TRUE);
    }
	
    public function __destruct() {
    	//$this->r_db->close();
    	//unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function copy_board_data ($board_no)
    {
        $this->w_db->select("`b_type`,`b_type_no`,`b_user_no`,`b_member_id`,`b_nick_name`,`b_propic`,`b_message`,`b_color`,`b_size`,`b_video_time`,`b_position`,`b_status`,`b_creat_utc`,`b_creat_unix`,");
        $this->w_db->where("b_no", $board_no);
        $query = $this->w_db->get("Board_tbl");
        $this->w_db->insert("Barrage_tbl", $query->row_array());
        $id = $this->w_db->insert_id();
        return $id;
    }
}
