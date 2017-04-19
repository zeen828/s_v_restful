<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
header ( 'Access-Control-Allow-Origin: *' );
header ( 'Access-Control-Allow-Headers: X-Requested-With' );
header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Boards extends MY_REST_Controller {
	private $data_debug;
	private $data_result;
	// 建立留言需要寫入彈幕的類型
	public $copy_boards_data_arr = array (
			'episode',
			'live' 
	);
	// 建立留言需要呼叫websocket的類型
	public $call_websocket_arr = array (
			'channel',
			'episode', // 白鷺鷥用
			'live',
			'event' 
	);
	public function __construct() {
		parent::__construct ();
		$this->_my_logs_start = true;
		$this->_my_logs_type = 'boards';
		$this->data_debug = true;
		// 資料庫
		// $this->load->database ( 'vidol_billing_write' );
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}
	public function message_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_websocket/board_model' );
			$this->load->model ( 'vidol_websocket/barrage_model' );
			$this->load->helper ( 'formats' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 變數
			$data_input = array ();
			$data_board = array ();
			$data_barrage = array ();
			$data_ws = array ();
			$data_cache_name = array ();
			$this->data_result = array (
					'api' => 'message_post',
					'status' => false,
					'info' => array (),
					'data' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['token'] = $this->post ( 'token' );
			$data_input ['programme'] = $this->post ( 'programme' );
			$data_input ['video_type'] = $this->post ( 'video_type' );
			$data_input ['video_id'] = $this->post ( 'video_id' );
			$data_input ['reoly'] = $this->post ( 'reoly' );
			$data_input ['user'] = $this->post ( 'user' );
			$data_input ['mongo_id'] = $this->post ( 'user_id' );
			$data_input ['member_id'] = $this->post ( 'member_id' );
			$data_input ['nick_name'] = (empty ( $this->post ( 'nick_name' ) )) ? 'Guest' : $this->post ( 'nick_name' );
			$data_input ['propic'] = (empty ( $this->post ( 'propic' ) )) ? 'http://images.vidol.tv/vidol_assets/50x50.jpg' : $this->post ( 'propic' );
			$data_input ['message'] = $this->post ( 'msg' );
			$data_input ['barrage'] = $this->post ( 'barrage' );
			$data_input ['color'] = $this->post ( 'color' );
			$data_input ['size'] = $this->post ( 'size' );
			$data_input ['video_time'] = (empty ( $this->post ( 'video_time' ) )) ? '0' : $this->post ( 'video_time' );
			$data_input ['position'] = $this->post ( 'position' );
			$data_input ['debug'] = $this->post ( 'debug' );
			// 需要同一台server計算時間
			$data_input ['time_utc'] = date ( 'Y-m-d h:i:s', strtotime ( date ( 'Y-m-d h:i:s' ) . "-8 hour" ) );
			$data_input ['time_unix'] = time ();
			$data_input ['ip'] = $this->input->ip_address ();
			// FB取大圖強制換小圖
			$data_input ['propic'] = str_replace ( '?type=large', '?type=small', $data_input ['propic'] );
			$this->data_result ['input'] = $data_input;
			// 必填檢查
			if (empty ( $data_input ['video_type'] ) || empty ( $data_input ['video_id'] ) || empty ( $data_input ['member_id'] || empty ( $data_input ['message'] ) )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 資料整理
			if (is_numeric ( $data_input ['programme'] )) {
				$data_board ['b_programme_no'] = $data_input ['programme'];
			}
			$enum_type = array (
					'episode',
					'channel',
					'live',
					'event' 
			);
			if (in_array ( $data_input ['video_type'], $enum_type )) {
				$data_board ['b_type'] = $data_input ['video_type'];
				$data_barrage ['b_type'] = $data_input ['video_type'];
			}
			if (is_numeric ( $data_input ['video_id'] )) {
				$data_board ['b_type_no'] = $data_input ['video_id'];
				$data_barrage ['b_type_no'] = $data_input ['video_id'];
			}
			if (is_numeric ( $data_input ['reoly'] )) {
				$data_board ['b_reply_no'] = $data_input ['reoly'];
			}
			if (! empty ( $data_input ['user'] )) {
				$data_board ['b_user_no'] = $data_input ['user'];
				$data_barrage ['b_user_no'] = $data_input ['user'];
			}
			if (! empty ( $data_input ['mongo_id'] )) {
				$data_board ['b_mongo_id'] = $data_input ['mongo_id'];
			}
			if (! empty ( $data_input ['member_id'] )) {
				$data_board ['b_member_id'] = $data_input ['member_id'];
				$data_barrage ['b_member_id'] = $data_input ['member_id'];
			}
			if (! empty ( $data_input ['nick_name'] )) {
				$data_board ['b_nick_name'] = $data_input ['nick_name'];
				$data_barrage ['b_nick_name'] = $data_input ['nick_name'];
			}
			if (! empty ( $data_input ['propic'] )) {
				$data_board ['b_propic'] = $data_input ['propic'];
				$data_barrage ['b_propic'] = $data_input ['propic'];
			}
			if (! empty ( $data_input ['message'] )) {
				$data_board ['b_message'] = $data_input ['message'];
				$data_barrage ['b_message'] = $data_input ['message'];
			}
			$enum_barrage = array (
					'Y',
					'N' 
			);
			if (in_array ( $data_input ['barrage'], $enum_barrage )) {
				$data_board ['b_barrage'] = $data_input ['barrage'];
			}
			if (! empty ( $data_input ['color'] )) {
				$data_board ['b_color'] = $data_input ['color'];
				$data_barrage ['b_color'] = $data_input ['color'];
			}
			if (! empty ( $data_input ['size'] )) {
				$data_board ['b_size'] = $data_input ['size'];
				$data_barrage ['b_size'] = $data_input ['size'];
			}
			if (! empty ( $data_input ['video_time'] )) {
				$data_board ['b_video_time'] = $data_input ['video_time'];
				$data_barrage ['b_video_time'] = $data_input ['video_time'];
			}
			if (! empty ( $data_input ['position'] )) {
				$data_board ['b_position'] = $data_input ['position'];
				$data_barrage ['b_position'] = $data_input ['position'];
			}
			if (! empty ( $data_input ['time_utc'] )) {
				$data_board ['b_creat_utc'] = $data_input ['time_utc'];
				$data_barrage ['b_creat_utc'] = $data_input ['time_utc'];
			}
			if (! empty ( $data_input ['time_unix'] )) {
				$data_board ['b_creat_unix'] = $data_input ['time_unix'];
				$data_barrage ['b_creat_unix'] = $data_input ['time_unix'];
			}
			if (! empty ( $data_input ['ip'] )) {
				$data_board ['b_ip'] = $data_input ['ip'];
			}
			// 建立留言
			$insert_pk = $this->board_model->insert_for_data ( $data_board );
			$board = $this->board_model->get_row_by_pk ( '*', $insert_pk );
			if ($data_input ['barrage'] == 'Y') {
				if (in_array ( $data_input ['video_type'], $this->copy_boards_data_arr )) {
					// 建立彈幕
					$insert_pk = $this->barrage_model->insert_for_data ( $data_barrage );
				}
				if (in_array ( $data_input ['video_type'], $this->call_websocket_arr )) {
					// 發送websocket
					$ws_data = json_encode ( array (
							'video_type' => $data_input ['video_type'],
							'video_id' => $data_input ['video_id'],
							'data' => array (
									'video_type' => $data_input ['video_type'],
									'video_id' => $data_input ['video_id'],
									'video_time' => $data_input ['video_time'],
									'member_id' => $data_input ['member_id'],
									'nick_name' => $data_input ['nick_name'],
									'propic' => $data_input ['propic'],
									'messages' => $data_input ['message'],
									'time_unix' => $data_input ['time_unix'] 
							) 
					) );
					$ws_data = str_replace ( "'", "`", $ws_data );
					// 呼叫websocket
					$websocket_com = sprintf ( "python /home/socket_server/websocket/client.py '%s'", $ws_data );
					$this->data_result ['websocket_com'] = $websocket_com;
					//
					// $this->data_result['debug'] = array(
					// 'ws_data' => $ws_data,
					// 'websocket_com' => $websocket_com
					// );
					// exec('python /home/socket_server/websocket/client.py \'' . $ws_data . '\'' );
					exec ( $websocket_com );
					// 如果卡住要測試一下指令是否卡住
				}
			}
			// 清除cache
			$data_input ['type'] = 'video';
			$data_input ['id'] = $data_input ['video_id'];
			// cache name key
			$data_cache_name ['board_count'] = sprintf ( '%s_get_%s_board_count_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$data_cache_name ['board_data'] = sprintf ( '%s_get_%s_board_data_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$this->cache->memcached->delete ( $data_cache_name ['board_count'] );
			$this->cache->memcached->delete ( $data_cache_name ['board_data'] );
			$data_input ['type'] = 'programme';
			$data_input ['id'] = $data_input ['programme'];
			// cache name key
			$data_cache_name ['board_count'] = sprintf ( '%s_get_%s_board_count_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$data_cache_name ['board_data'] = sprintf ( '%s_get_%s_board_data_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$this->cache->memcached->delete ( $data_cache_name ['board_count'] );
			$this->cache->memcached->delete ( $data_cache_name ['board_data'] );
			// 成功
			$this->data_result ['status'] = true;
			$this->data_result ['data'] = format_helper_board ( $board );
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache_name'] = $data_cache_name;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
			}
			unset ( $data_cache );
			unset ( $data_cache_name );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function message_app_get() {
		$ip = $this->input->ip_address ();
		$user_agent = $_SERVER ['HTTP_USER_AGENT'];
		if ($ip != '61.216.83.7' && (eregi ( 'Windows', $user_agent ) || eregi ( 'Macintosh', $user_agent ))) {
			$this->response ( null, 404 );
		}
		$this->message_web_get ();
	}
	public function message_web_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_websocket/board_model' );
			$this->load->model ( 'vidol_websocket/barrage_model' );
			$this->load->helper ( 'formats' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 變數
			$data_input = array ();
			$data_cache_name = array ();
			$data_cache = array ();
			$this->data_result = array (
					'api' => 'message_web_get',
					'status' => false,
					'info' => array (
							'count' => 0,
							'start_no' => 0,
							'page_max' => 0,
							'page_size' => 0 
					),
					'data' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['programme'] = ( int ) $this->get ( 'programme' );
			$data_input ['video_type'] = $this->get ( 'video_type' );
			$data_input ['video_id'] = ( int ) $this->get ( 'video_id' );
			$data_input ['sort'] = ($this->get ( 'sort' ) == '1') ? 'DESC' : 'ASC';
			$data_input ['start_no'] = ( int ) $this->get ( 'start_no' );
			$data_input ['pagesize'] = ( int ) $this->get ( 'pagesize' );
			if (empty ( $data_input ['pagesize'] ) || $data_input ['pagesize'] <= 0) {
				$data_input ['pagesize'] = 10;
			} elseif ($data_input ['pagesize'] > 50) {
				$data_input ['pagesize'] = 50;
			}
			$data_input ['debug'] = $this->get ( 'debug' );
			$this->data_result ['input'] = $data_input;
			// 必填檢查
			if (empty ( $data_input ['programme'] ) && (empty ( $data_input ['video_type'] ) || empty ( $data_input ['video_id'] ))) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			if (empty ( $data_input ['video_id'] )) {
				$data_input ['type'] = 'programme';
				$data_input ['id'] = $data_input ['programme'];
				$where = array (
						'b_programme_no' => $data_input ['programme'] 
				);
			} else {
				$data_input ['type'] = 'video';
				$data_input ['id'] = $data_input ['video_id'];
				$where = array (
						'b_type' => $data_input ['video_type'],
						'b_type_no' => $data_input ['video_id'] 
				);
			}
			// cache name key
			$data_cache_name ['board_count'] = sprintf ( '%s_get_%s_board_count_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$data_cache_name ['board_data'] = sprintf ( '%s_get_%s_board_data_%s', ENVIRONMENT, $data_input ['type'], $data_input ['id'] );
			$data_cache_name ['board_range'] = sprintf ( 'range_%s_%s', $data_input ['start_no'], $data_input ['pagesize'] );
			// 筆數
			$data_cache [$data_cache_name ['board_count']] = $this->cache->memcached->get ( $data_cache_name ['board_count'] );
			if ($data_cache [$data_cache_name ['board_count']] == false) {
				$data_cache [$data_cache_name ['board_count']] = $this->board_model->get_count_by_status_where ( 1, $where );
				$this->cache->memcached->save ( $data_cache_name ['board_count'], $data_cache [$data_cache_name ['board_count']], 3000 );
			}
			$board_count = $data_cache [$data_cache_name ['board_count']];
			// 資料
			$data_cache [$data_cache_name ['board_data']] = $this->cache->memcached->get ( $data_cache_name ['board_data'] );
			if ($data_cache [$data_cache_name ['board_data']] == false || ! isset ( $data_cache [$data_cache_name ['board_data']] [$data_cache_name ['data_range']] )) {
				$data_cache [$data_cache_name ['board_data']] [$data_cache_name ['board_range']] = array ();
				// API排序不是資料庫排序資料庫排序方式不需變動
				$query = $this->board_model->get_rows_sort_by_status_where ( '*', 'DESC', '1', $where, $data_input ['start_no'], $data_input ['pagesize'] );
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						$tmp_board = format_helper_board ( $row );
						array_push ( $data_cache [$data_cache_name ['board_data']] [$data_cache_name ['board_range']], $tmp_board );
						unset ( $row );
						unset ( $tmp_board );
					}
				}
				unset ( $query );
				$this->cache->memcached->save ( $data_cache_name ['board_data'], $data_cache [$data_cache_name ['board_data']], 3000 );
			}
			$tmp_board_data = $data_cache [$data_cache_name ['board_data']] [$data_cache_name ['board_range']];
			// 資料排列整理
			if ($board_count > 0) {
				$page_max = ceil ( $board_count / $data_input ['pagesize'] );
				if ($data_input ['sort'] == 'DESC') {
					krsort ( $tmp_board_data );
				}
				foreach ( $tmp_board_data as $board_row ) {
					$this->data_result ['data'] [] = $board_row;
					unset ( $board_row );
				}
			}
			unset ( $tmp_board_data );
			$this->data_result ['info'] ['count'] = $board_count;
			$this->data_result ['info'] ['start_no'] = $data_input ['start_no'];
			$this->data_result ['info'] ['page_max'] = $page_max;
			$this->data_result ['info'] ['page_size'] = $data_input ['pagesize'];
			// 成功
			$this->data_result ['status'] = true;
			// $this->data_result ['data'] = $board_data;
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache_name'] = $data_cache_name;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
			}
			unset ( $data_cache );
			unset ( $data_cache_name );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
