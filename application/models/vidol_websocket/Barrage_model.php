<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Barrage_model extends CI_Model {
	private $table_name = 'Barrage_tbl';
	private $fields_pk = 'b_no';
	private $fields_status = 'b_status';
	public function __construct() {
		parent::__construct ();
		$this->r_db = $this->load->database ( 'vidol_websocket_read', TRUE );
		$this->w_db = $this->load->database ( 'vidol_websocket_write', TRUE );
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
	public function get_count_by_status_where($status, $where = null) {
		$this->r_db->select ( $this->fields_pk );
		if (is_array ( $where )) {
			$this->r_db->where ( $where );
		}
		$this->r_db->where ( $this->fields_status, $status );
		$count = $this->r_db->count_all_results ( $this->table_name );
		// echo $this->r_db->last_query();
		return $count;
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
	public function get_rows_sort_by_status_where($select, $sort = 'DESC', $status = '1', $where = null, $start_no = 0, $limit = 20) {
		if (! empty ( $select )) {
			$this->r_db->select ( $select );
		}
		if (is_array ( $where )) {
			$this->r_db->where ( $where );
		}
		// 哪一筆之後因為不是流水號
		if ($start_no) {
			$this->r_db->where ( $this->fields_pk . ' <', $start_no );
		}
		$this->r_db->where ( $this->fields_status, $status );
		$this->r_db->order_by ( 'b_creat_unix', $sort );
		// $this->r_db->limit ( $limit, $start_no );
		$this->r_db->limit ( $limit );
		$query = $this->r_db->get ( $this->table_name );
		// echo $this->r_db->last_query();
		return $query;
	}
}
