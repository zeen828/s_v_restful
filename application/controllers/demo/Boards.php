<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Boards extends CI_Controller
{

    public function index ()
    {
        
    }
    
    // http://plugin-background.vidol.tv/demo/boards/user/R3Aoz8?video_id=205
    public function user ($user_id = '')
    {
        if (! empty($user_id)) {
            $data = array(
                    'programme' => '3',
                    'video_type' => 'event',//'episode',
                    'video_id' => $this->input->get('video_id'),
                    'user' => '',
                    'member_id' => '',
                    'nick_name' => 'Guest',
                    'propic' => ''
            );
            $this->db->where('u_member_id', $user_id); // 節目
            $this->db->where('u_start', 1);
            $query = $this->db->get('vidol_old.User_tbl');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $data['user'] = $row->u_id;
                $data['member_id'] = $row->u_member_id;
                $data['nick_name'] = $row->u_nick_name;
                $data['propic'] = $row->u_profile;
            }
            $this->load->view('demo/boards', $data);
        }
    }
    
    public function movie ($user_id = '')
    {
        if (! empty($user_id)) {
            $data = array(
                    'programme' => '3',
                    'video_type' => 'episode',
                    'video_id' => $this->input->get('video_id'),
                    'user' => '',
                    'member_id' => '',
                    'nick_name' => 'Guest',
                    'propic' => ''
            );
            $this->db->where('u_member_id', $user_id); // 節目
            $this->db->where('u_start', 1);
            $query = $this->db->get('vidol_old.User_tbl');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $data['user'] = $row->u_id;
                $data['member_id'] = $row->u_member_id;
                $data['nick_name'] = $row->u_nick_name;
                $data['propic'] = $row->u_profile;
            }
            $this->load->view('demo/movie', $data);
        }
    }
}
