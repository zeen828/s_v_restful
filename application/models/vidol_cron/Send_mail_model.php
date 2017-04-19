<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Send_mail_model extends CI_Model {
	private $table_name = 'Send_mail_tbl';
	private $fields_pk = 'sm_pk';
	private $fields_status = 'sm_status';
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'vidol_cron_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_cron_write', TRUE );
	}
	public function __destruct() {
		$this->r_db->close ();
		unset ( $this->r_db );
		$this->w_db->close ();
		unset ( $this->w_db );
		// parent::__destruct();
	}
	public function insert_for_data($data) {
		$this->w_db->insert ( $this->table_name, $data );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	public function update_for_data_by_pk($pk, $data) {
		$this->w_db->where ( $this->fields_pk, $pk );
		$this->w_db->update ( $this->table_name, $data );
		$result = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $result;
	}
	public function get_row_by_pk($select, $pk) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		$this->r_db->where ( $this->fields_pk, $pk );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		if ($query->num_rows () > 0) {
			return $query->row ();
		}
		return false;
	}
	public function get_rows_by_status($select, $status, $limit = 50) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		$this->r_db->where ( $this->fields_status, $status );
		$this->r_db->limit ( $limit );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		return $query;
	}
	// 新增登入不重複數(每日)
	public function insert_send_mail($source, $user_accounts_no, $from, $to, $reply_to, $cc, $bcc, $title, $content) {
		$this->w_db->set ( 'sm_source', $source );
		$this->w_db->set ( 'sm_user_accounts_no', $user_accounts_no );
		// $this->w_db->set('sm_user_no', $user_no);
		// $this->w_db->set('sm_mongo_id', $mongo_id);
		// $this->w_db->set('sm_member_id', $member_id);
		$this->w_db->set ( 'sm_from', $from );
		$this->w_db->set ( 'sm_to', $to );
		$this->w_db->set ( 'sm_reply_to', $reply_to );
		$this->w_db->set ( 'sm_cc', $cc );
		$this->w_db->set ( 'sm_bcc', $bcc );
		$this->w_db->set ( 'sm_title', $title );
		$this->w_db->set ( 'sm_content', $content );
		$this->w_db->set ( 'sm_type', 'html' );
		$this->w_db->set ( 'sm_status', '0' );
		$this->w_db->insert ( 'Send_mail_tbl' );
		$id = $this->w_db->insert_id ();
		// echo $this->w_db->last_query();
		return $id;
	}
	// 取得不重登入數(每日)
	public function get_send_mail_by_status($limit = 50) {
		$this->r_db->where ( 'sm_status', '0' );
		$this->r_db->limit ( $limit );
		$query = $this->r_db->get ( 'Send_mail_tbl' );
		// echo $this->r_db->last_query();
		return $query;
	}
	public function update_send_mail_status_by_pk($pk, $status = 1) {
		$this->w_db->set ( 'sm_status', $status );
		$this->w_db->where ( 'sm_pk', $pk );
		$query = $this->w_db->update ( 'Send_mail_tbl' );
		$count = $this->w_db->affected_rows ();
		// echo $this->w_db->last_query();
		return $count;
	}
}
