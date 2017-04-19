<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Board_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('vidol_websocket_read', TRUE);
        $this->w_db = $this->load->database('vidol_websocket_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function insert_board ($programme, $videotype, $video, $reoly, $user, $mongo_id, $member_id, $nick_name, $propic, $message, $barrage, $color, $size, $video_time, $position, $time_utc, $time_unix)
    {
        if (is_numeric($programme)) {
            $this->w_db->set('b_programme_no', $programme);
        }
        $b_type_enum = array(
                'episode',
                'channel',
                'live',
                'event'
        );
        if (in_array($videotype, $b_type_enum)) {
            $this->w_db->set('b_type', $videotype);
        }
        if (is_numeric($video)) {
            $this->w_db->set('b_type_no', $video);
        }
        if (is_numeric($reoly)) {
            $this->w_db->set('b_reply_no', $reoly);
        }
        if (! empty($user)) {
            $this->w_db->set('b_user_no', $user);
        }
        if (! empty($mongo_id)) {
        	$this->w_db->set('b_mongo_id', $mongo_id);
        }
        if (! empty($member_id)) {
            $this->w_db->set('b_member_id', $member_id);
        }
        if (! empty($nick_name)) {
            $this->w_db->set('b_nick_name', $nick_name);
        }
        if (! empty($propic)) {
            $this->w_db->set('b_propic', $propic);
        }
        if (! empty($message)) {
            $this->w_db->set('b_message', $message);
        }
        $b_barrage_enum = array(
                'Y',
                'N'
        );
        if (in_array($barrage, $b_barrage_enum)) {
            $this->w_db->set('b_barrage', $barrage);
        }
        if (! empty($color)) {
            $this->w_db->set('b_color', $color);
        }
        if (! empty($size)) {
            $this->w_db->set('b_size', $size);
        }
        if (is_numeric($video_time)) {
            $this->w_db->set('b_video_time', $video_time);
        }
        if (! empty($position)) {
            $this->w_db->set('b_position', $position);
        }
        if (! empty($time_utc)) {
        	$this->w_db->set('b_creat_utc', $time_utc);
        }else{
        	$this->w_db->set('b_creat_utc', 'UTC_TIMESTAMP()', FALSE);
        }
        if (! empty($time_unix)) {
        	$this->w_db->set('b_creat_unix', $time_unix);
        }else{
        	$this->w_db->set('b_creat_unix', 'UNIX_TIMESTAMP()', FALSE);
        }
        $ip = $this->input->ip_address();
        $this->w_db->set('b_ip', $ip);
        $this->w_db->insert('Board_tbl');
        $id = $this->w_db->insert_id();
        return $id;
    }
    
    // 取得節目留言總數
    public function get_programme_count ($programme)
    {
        $this->r_db->where('b_programme_no', $programme); // 節目
        $this->r_db->where('b_status', 1);
        $count = $this->r_db->count_all_results('Board_tbl');
        // echo $this->r_db->last_query();
        return $count;
    }
    
    // 取得節目留言板
    public function get_programme_board ($programme, $sort='DESC', $page = 0, $limit = 10)
    {
    	$start = $page * $limit; // 查詢起始筆數
        $this->r_db->where('b_programme_no', $programme); // 節目
        $this->r_db->where('b_status', 1);
        $this->r_db->order_by('b_no', $sort);
        $this->r_db->limit($limit, $start);
        $query = $this->r_db->get('Board_tbl');
        // echo $this->r_db->last_query();
        return $query->result();
    }
    
    // 取得節目留言板
    // app瀑布流
    public function get_programme_board_for_app ($programme, $sort='DESC', $start_no = 0, $limit = 10)
    {
    	if($start_no){
    		$this->r_db->where('b_no <', $start_no);
    	}
    	$this->r_db->where('b_programme_no', $programme); // 節目
    	$this->r_db->where('b_status', 1);
    	$this->r_db->order_by('b_no', $sort);
    	$this->r_db->limit($limit);
    	$query = $this->r_db->get('Board_tbl');
    	//echo $this->r_db->last_query();
    	return $query->result();
    }
    
    // 取得影片留言總數
    public function get_video_count ($videotype, $video)
    {
        $this->r_db->where('b_type', $videotype);
        $this->r_db->where('b_type_no', $video); // 影片
        $this->r_db->where('b_status', 1);
        $count = $this->r_db->count_all_results('Board_tbl');
        return $count;
    }
    
    // 取得影片留言板
    public function get_video_board ($videotype, $video, $sort='DESC', $page = 0, $limit = 10)
    {
   		$start = $page * $limit; // 查詢起始筆數
   		$this->r_db->where('b_type', $videotype);
   		$this->r_db->where('b_type_no', $video); // 影片
   		$this->r_db->where('b_status', 1);
   		$this->r_db->order_by('b_no', $sort);
   		$this->r_db->limit($limit, $start);
        $query = $this->r_db->get('Board_tbl');
		//echo $this->r_db->last_query();
        return $query->result();
    }
    
    // 取得影片留言板
    // app瀑布流
    public function get_video_board_for_app ($videotype, $video, $sort='DESC', $start_no = 0, $limit = 10)
    {
    	if($start_no){
    		$this->r_db->where('b_no <', $start_no);
    	}
    	$this->r_db->where('b_type', $videotype);
    	$this->r_db->where('b_type_no', $video); // 影片
    	$this->r_db->where('b_status', 1);
    	$this->r_db->order_by('b_no', 'desc');
    	$this->r_db->limit($limit);
    	$query = $this->r_db->get('Board_tbl');
		//echo $this->r_db->last_query();
    	return $query->result();
    }
    
    // 取得留言
    public function get_messages ($message)
    {
        $this->r_db->where('b_no', $message);
        $this->r_db->where('b_status', 1);
        $this->r_db->limit(1);
        $query = $this->r_db->get('Board_tbl');
        return $query->row();
    }

    public function insert_reply ($programme, $videotype, $video, $reoly, $user, $member_id, $nick_name, $propic, $message, $barrage, $color, $size, $video_time, $position)
    {
        if (is_numeric($programme)) {
            $this->w_db->set('b_programme_no', $programme);
        }
        $b_type_enum = array(
                'episode',
                'channel',
                'live',
                'event'
        );
        if (in_array($videotype, $b_type_enum)) {
            $this->w_db->set('b_type', $videotype);
        }
        if (is_numeric($video)) {
            $this->w_db->set('b_type_no', $video);
        }
        if (is_numeric($reoly)) {
            $this->w_db->set('b_reply_no', $reoly);
        }
        if (! empty($user)) {
            $this->w_db->set('b_user_no', $user);
        }
        if (! empty($member_id)) {
            $this->w_db->set('b_member_id', $member_id);
        }
        if (! empty($nick_name)) {
            $this->w_db->set('b_nick_name', $nick_name);
        }
        if (! empty($propic)) {
            $this->w_db->set('b_propic', $propic);
        }
        if (! empty($message)) {
            $this->w_db->set('b_message', $message);
        }
        $b_barrage_enum = array(
                'Y',
                'N'
        );
        if (in_array($barrage, $b_barrage_enum)) {
            $this->w_db->set('b_barrage', $barrage);
        }
        if (! empty($color)) {
            $this->w_db->set('b_color', $color);
        }
        if (! empty($size)) {
            $this->w_db->set('b_size', $size);
        }
        if (is_numeric($video_time)) {
            $this->w_db->set('b_video_time', $video_time);
        }
        if (! empty($position)) {
            $this->w_db->set('b_position', $position);
        }
        $this->w_db->set('b_creat_utc', 'UTC_TIMESTAMP()', FALSE);
        $this->w_db->set('b_creat_unix', 'UNIX_TIMESTAMP()', FALSE);
        $ip = $this->input->ip_address();
        $this->w_db->set('b_ip', $ip);
        $this->w_db->insert('Board_tbl');
        $id = $this->w_db->insert_id();
        //echo $this->r_db->last_query();
        return $id;
    }
    // 取得回應總數
    public function get_reply_count ($messages)
    {
        $this->r_db->where('b_reply_no', $messages);
        $this->r_db->where('b_status', 1);
        $count = $this->r_db->count_all_results('Board_tbl');
        return $count;
    }
    
    // 取得回應板
    public function get_reply_board ($messages, $page = 0, $limit = 5)
    {
        $start = $page * $limit; // 查詢起始筆數
        $this->r_db->where('b_reply_no', $messages);
        $this->r_db->where('b_status', 1);
        $this->r_db->order_by('b_no', 'DESC');
        $this->r_db->limit($limit, $start);
        $query = $this->r_db->get('Board_tbl');
        return $query->result();
    }
    
    // 取得回應
    public function get_reply ($reply)
    {
        $this->r_db->where('b_no', $reply);
        $this->r_db->where('b_status', 1);
        $this->r_db->limit(1);
        $query = $this->r_db->get('Board_tbl');
        return $query->row();
    }
}
