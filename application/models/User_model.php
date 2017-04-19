<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
    }
    
    // 取得名稱字串(沒有暱稱就取名稱)
    public function get_string_nick_name ($user_id)
    {
        $this->db->where('u_id', $user_id);
        $query = $this->db->get('User_tbl');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $name = ($row->u_nick_name) ? $row->u_nick_name : $row->u_username;
        } else {
            $name = 'not name';
        }
        return $name;
    }
}
