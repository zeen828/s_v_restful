<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Lottery_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'vidol_old_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_old_write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	public function get_lottery() {
		$this->r_db->where ( 'status', '1' );
		$query = $this->r_db->get ( 'lottery_iphone_tbl' );
		// echo $this->r_db->last_query();
		return $query;
	}
	public function add_lottery_list($mongo_id, $member_id) {
		$this->w_db->set ( 'mongo_id', $mongo_id );
		$this->w_db->set ( 'member_id', $member_id );
		$this->w_db->insert ( 'lottery_iphone_list_tbl' );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
}
