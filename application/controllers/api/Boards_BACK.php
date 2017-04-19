<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( 'display_errors', 'On' ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Boards extends MY_REST_Controller
{
	private $data_result;
	private $status_code;
	
	// 建立留言需要寫入彈幕的類型
	public $copy_boards_data_arr = array (
			'episode',
			'live'
	);
	// 建立留言需要呼叫websocket的類型
	public $call_websocket_arr = array (
			'channel',
			'episode',//白鷺鷥用
			'live',
			'event' 
	);
	public function __construct() {
		header ( 'Access-Control-Allow-Origin: *' );
		header ( 'Access-Control-Allow-Headers: X-Requested-With' );
		header ( 'Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS' );
		parent::__construct ();
		$this->_my_logs_type = 'board';
		// 引用
		$this->load->model ( 'websocket/board_model' );
		$this->load->helper ( 'formats' );
		$this->load->helper ( 'token' );
		// 初始化
		$this->data_result = format_helper_return_data ();
		$this->status_code = 400;
		// 效能檢查
		//$this->output->enable_profiler ( TRUE );
	}
	public function index_get() {
		$this->message_get ();
	}
	
	// http://xxx.xxx.xxx/api/boards/message?programme=3
	// http://xxx.xxx.xxx/api/boards/message?video_type=episode&video_id=205
	public function message_get() {
		try {
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			$this->data_result ['api'] = 'Boards message_get';
			// 引入
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			// 變數
			$data_input = array ();
			// input
			$data_input ['programme'] = $this->get ( 'programme' );
			$data_input ['video_type'] = $this->get ( 'video_type' );
			$data_input ['video_id'] = $this->get ( 'video_id' );
			$data_input ['sort'] = ($this->get ( 'sort' ) == '1') ? 'ASC' : 'DESC';
			$data_input ['page'] = $this->get ( 'page' );
			if (empty ( $data_input ['page'] ) || $data_input ['page'] <= 0) {
				$data_input ['page'] = 1;
			}
			$data_input ['pagesize'] = $this->get ( 'pagesize' );
			if (empty ( $data_input ['pagesize'] ) || $data_input ['pagesize'] <= 0) {
				$data_input ['pagesize'] = 10;
			} elseif ($data_input ['pagesize'] > 50) {
				$data_input ['pagesize'] = 50;
			}
			$this->data_result ['input'] = $data_input;
			$this->data_result ['info'] = array (
					'count' => 0,
					'page' => $data_input ['page'],
					'page_max' => 0,
					'page_size' => $data_input ['pagesize'] 
			);
			//
			if (! empty ( $data_input ['programme'] ) || (! empty ( $data_input ['video_type'] ) && ! empty ( $data_input ['video_id'] ))) {
				$this->data_result ['status'] = true;
				$this->status_code = 204; // 查無內容
				if (empty ( $data_input ['video_id'] )) {
					// 節目留言
					// 節目可能包含episode或channel所以不設type
					$board_count = $this->board_model->get_programme_count ( $data_input ['programme'] );
					$tmp_board_data = $this->board_model->get_programme_board ( $data_input ['programme'], $data_input ['sort'], $data_input ['page'] - 1, $data_input ['pagesize'] );
				} else {
					// 直播/影片留言
					$board_count = $this->board_model->get_video_count ( $data_input ['video_type'], $data_input ['video_id'] );
					$tmp_board_data = $this->board_model->get_video_board ( $data_input ['video_type'], $data_input ['video_id'], $data_input ['sort'], $data_input ['page'] - 1, $data_input ['pagesize'] );
				}
				// 資料整理
				if($board_count > 0){
					$board = array();
					$page_max = ceil ( $board_count / $data_input ['pagesize'] );
					if (count ( $tmp_board_data ) > 0) {
						
						foreach ( $tmp_board_data as $board_row ) {
							$tmp_board = format_helper_board ( $board_row );
							$board [] = $tmp_board;
						}
						
					}
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $page_max;
					$this->data_result ['data'] = $board;
				}else{
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $board_count;
				}
			} else {
				$this->status_code = 400;
			}
			$this->benchmark->mark ( 'code_end' );
			$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->data_result = array_merge ( $this->data_result, array (
					'time' => $code_time 
			) );
			$this->response ( $this->data_result, $this->status_code );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function message_post() {
		try{
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			$this->data_result ['api'] = 'Boards message_post';
			// 引入
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			// 變數
			$data_cache_name = array();
			$data_input = array ();
			// input
			$data_input ['token'] = $this->post ( 'token' );
			$data_input ['programme'] = $this->post ( 'programme' );
			$data_input ['video_type'] = $this->post ( 'video_type' );
			$data_input ['video_id'] = $this->post ( 'video_id' );
			$data_input ['reoly'] = $this->post ( 'reoly' );
			$data_input ['user'] = $this->post ( 'user' );
			$data_input ['mongo_id'] = $this->post ( 'user_id' );
			$data_input ['member_id'] = $this->post ( 'member_id' );
			$data_input ['nick_name'] = (empty($this->post ( 'nick_name' )))? 'Guest' : $this->post ( 'nick_name' );
			$data_input ['propic'] = (empty($this->post ( 'propic' )))? 'http://images.vidol.tv/vidol_assets/50x50.jpg' : $this->post ( 'propic' );
			$data_input ['msg'] = $this->post ( 'msg' );
			//$data_input ['msg'] = json_encode($this->post ( 'msg' ));
			$data_input ['barrage'] = $this->post ( 'barrage' );
			$data_input ['color'] = $this->post ( 'color' );
			$data_input ['size'] = $this->post ( 'size' );
			$data_input ['video_time'] = (empty($this->post ( 'video_time' )))? '0' : $this->post ( 'video_time' );
			$data_input ['position'] = $this->post ( 'position' );
			//需要同一台server計算時間
			$data_input ['time_utc'] = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . "-8 hour"));
			$data_input ['time_unix'] = time();
			// FB取大圖強制換小圖
			$data_input ['propic'] = str_replace ('?type=large', '?type=small', $data_input ['propic']);
			//if (token_postgre_check ( $data_input ['token'] )) {
			if (true) {
				if (! empty ( $data_input ['video_type'] ) && ! empty ( $data_input ['video_id'] ) && ! empty ( $data_input ['member_id'] ) && ! empty ( $data_input ['msg'] )) {
					//建立留言紀錄
					$insert_no = $this->board_model->insert_board ( $data_input ['programme'], $data_input ['video_type'], $data_input ['video_id'], $data_input ['reoly'], $data_input ['user'], $data_input ['mongo_id'], $data_input ['member_id'], $data_input ['nick_name'], $data_input ['propic'], $data_input ['msg'], $data_input ['barrage'], $data_input ['color'], $data_input ['size'], $data_input ['video_time'], $data_input ['position'], $data_input ['time_utc'], $data_input ['time_unix'] );
					// cache name key
					$data_cache_name['programme_board_count'] = sprintf ( '%s_get_programme_board_count_%s', ENVIRONMENT, $data_input ['programme'] );
					$data_cache_name['programme_board_data'] = sprintf ( '%s_get_programme_board_data_%s', ENVIRONMENT, $data_input ['programme'] );
					$data_cache_name['video_board_count'] = sprintf ( '%s_get_video_board_count_%s_%s', ENVIRONMENT, $data_input ['video_type'], $data_input ['video_id'] );
					$data_cache_name['video_board_data'] = sprintf ( '%s_get_video_board_data_%s_%s', ENVIRONMENT, $data_input ['video_type'], $data_input ['video_id'] );
					$this->cache->memcached->delete ( $data_cache_name['programme_board_count'] );
					$this->cache->memcached->delete ( $data_cache_name['programme_board_data'] );
					$this->cache->memcached->delete ( $data_cache_name['video_board_count'] );
					$this->cache->memcached->delete ( $data_cache_name['video_board_data'] );
					// 結果
					if ($insert_no) {
						$this->data_result ['status'] = true;
						$this->status_code = 201;
					} else {
						$this->status_code = 405;
					}
					//彈幕處理
					//讀取資料庫資料(多拖時間取消)
					$this->data_result ['data'] = format_helper_board ( $this->board_model->get_messages ( $insert_no ) );
					if ($data_input ['barrage'] == 'Y') {
						// 寫入彈幕資料庫
						if (in_array ( $data_input ['video_type'], $this->copy_boards_data_arr )) {
							$this->load->model ( 'websocket/barrage_model' );
							$this->barrage_model->copy_board_data ( $insert_no );
						}
						// 呼叫websocket
						if (in_array ( $data_input ['video_type'], $this->call_websocket_arr )) {
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
											'messages' => $data_input ['msg'],
											'time_unix' => $data_input ['time_unix']
									)
							) );
							$ws_data = str_replace ("'", "`", $ws_data);
							// 呼叫websocket
							$websocket_com = sprintf("python /home/socket_server/websocket/client.py '%s'", $ws_data);
							$this->data_result['websocket_com'] = $websocket_com;
							//
							//$this->data_result['debug'] = array(
									//'ws_data' => $ws_data,
									//'websocket_com' => $websocket_com
							//);
							//exec('python /home/socket_server/websocket/client.py \'' . $ws_data . '\'' );
							exec($websocket_com);
							//如果卡住要測試一下指令是否卡住
						}
					}
				}else{
					$this->status_code = 400;
				}
			}else{
				$this->status_code = 401;
			}
			//記憶體清除
			unset($data_input);
			$this->benchmark->mark ( 'code_end' );
			$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->data_result = array_merge ( $this->data_result, array (
					'time' => $code_time
			) );
			$this->response ( $this->data_result, $this->status_code );
		}catch(Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	public function message_put() {
		try {
			echo 'put';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function message_delete() {
		try {
			echo 'delete';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function message_app_get() {
		$ip = $this->input->ip_address();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if($ip != '61.216.83.7' && ( eregi('Windows', $user_agent) || eregi('Macintosh', $user_agent) )){
			$this->response ( null, 404 );
		}
		$this->message_web_get();
	}
	
	public function message_web_bug_get() {
		try {
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			$this->data_result ['api'] = 'Boards message_web_bug_get';
			// 引入
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			// 變數
			$data_cache_name = array ();
			$data_cache = array ();
			$data_input = array ();
			// input
			$data_input ['programme'] = (int)$this->get ( 'programme' );
			$data_input ['video_type'] = $this->get ( 'video_type' );
			$data_input ['video_id'] = (int)$this->get ( 'video_id' );
			$data_input ['sort'] = ($this->get ( 'sort' ) == '1') ? 'ASC' : 'DESC';
			$data_input ['start_no'] = (int)$this->get ( 'start_no' );
			$data_input ['pagesize'] = (int)$this->get ( 'pagesize' );
			if (empty ( $data_input ['pagesize'] ) || $data_input ['pagesize'] <= 0) {
				$data_input ['pagesize'] = 10;
			} elseif ($data_input ['pagesize'] > 50) {
				$data_input ['pagesize'] = 50;
			}
			$this->data_result ['input'] = $data_input;
			
			
			$this->data_result ['info'] = array (
					'count' => 0,
					'start_no' => $data_input ['start_no'],
					'page_max' => 0,
					'page_size' => $data_input ['pagesize']
			);
			// cache name key
			$data_cache_name['programme_board_count'] = sprintf ( '%s_get_programme_board_count_%s', ENVIRONMENT, $data_input ['programme'] );
			$data_cache_name['programme_board_data'] = sprintf ( '%s_get_programme_board_data_%s_%s_%s_%s', ENVIRONMENT, $data_input ['programme'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
			$data_cache_name['video_board_count'] = sprintf ( '%s_get_video_board_count_%s_%s', ENVIRONMENT, $data_input ['video_type'], $data_input ['video_id'] );
			$data_cache_name['video_board_data'] = sprintf ( '%s_get_video_board_data_%s_%s_%s_%s_%s', ENVIRONMENT, $data_input ['video_type'], $data_input ['video_id'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
			$this->data_result ['cache_name'] = $data_cache_name;
			
			if (! empty ( $data_input ['programme'] ) || (! empty ( $data_input ['video_type'] ) && ! empty ( $data_input ['video_id'] ))) {
				$this->data_result ['status'] = true;
				$this->status_code = 204; // 查無內容
				if (empty ( $data_input ['video_id'] )) {
					// 節目留言
					// 節目可能包含episode或channel所以不設type
					$board_count = $this->board_model->get_programme_count ( $data_input ['programme'] );
					$tmp_board_data = $this->board_model->get_programme_board_for_app ( $data_input ['programme'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
				} else {
					// 直播/影片留言
					$board_count = $this->board_model->get_video_count ( $data_input ['video_type'], $data_input ['video_id'] );
					$tmp_board_data = $this->board_model->get_video_board_for_app ( $data_input ['video_type'], $data_input ['video_id'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
				}
				// 資料整理
				if($board_count > 0){
					$board = array();
					$page_max = ceil ( $board_count / $data_input ['pagesize'] );
					if (count ( $tmp_board_data ) > 0) {
						if($this->get ( 'sort' )){
							//krsort($board);
							krsort($tmp_board_data);
						}
						foreach ( $tmp_board_data as $board_row ) {
							$tmp_board = format_helper_board ( $board_row );
							$board [] = $tmp_board;
						}
					}
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $page_max;
					$this->data_result ['data'] = $board;
				}else{
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $board_count;
				}
			} else {
				$this->status_code = 400;
			}
			$this->benchmark->mark ( 'code_end' );
			$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->data_result = array_merge ( $this->data_result, array (
					'time' => $code_time
			) );
			$this->response ( $this->data_result, $this->status_code );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	public function message_web_get() {
		try {
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			$this->data_result ['api'] = 'Boards message_app_get';
			// 引入
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy'
			) );
			// 變數
			$data_input = array ();
			// input
			$data_input ['programme'] = $this->get ( 'programme' );
			$data_input ['video_type'] = $this->get ( 'video_type' );
			$data_input ['video_id'] = $this->get ( 'video_id' );
			$data_input ['sort'] = ($this->get ( 'sort' ) == '1') ? 'ASC' : 'DESC';
			$data_input ['start_no'] = $this->get ( 'start_no' );
			$data_input ['pagesize'] = $this->get ( 'pagesize' );
			if (empty ( $data_input ['pagesize'] ) || $data_input ['pagesize'] <= 0) {
				$data_input ['pagesize'] = 10;
			} elseif ($data_input ['pagesize'] > 50) {
				$data_input ['pagesize'] = 50;
			}
			$this->data_result ['input'] = $data_input;
			$this->data_result ['info'] = array (
					'count' => 0,
					'start_no' => $data_input ['start_no'],
					'page_max' => 0,
					'page_size' => $data_input ['pagesize']
			);
			//
			if (! empty ( $data_input ['programme'] ) || (! empty ( $data_input ['video_type'] ) && ! empty ( $data_input ['video_id'] ))) {
				$this->data_result ['status'] = true;
				$this->status_code = 204; // 查無內容
				if (empty ( $data_input ['video_id'] )) {
					// 節目留言
					// 節目可能包含episode或channel所以不設type
					$board_count = $this->board_model->get_programme_count ( $data_input ['programme'] );
					$tmp_board_data = $this->board_model->get_programme_board_for_app ( $data_input ['programme'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
				} else {
					// 直播/影片留言
					$board_count = $this->board_model->get_video_count ( $data_input ['video_type'], $data_input ['video_id'] );
					$tmp_board_data = $this->board_model->get_video_board_for_app ( $data_input ['video_type'], $data_input ['video_id'], $data_input ['sort'], $data_input ['start_no'], $data_input ['pagesize'] );
				}
				// 資料整理
				if($board_count > 0){
					$board = array();
					$page_max = ceil ( $board_count / $data_input ['pagesize'] );
					if (count ( $tmp_board_data ) > 0) {
						if($this->get ( 'sort' )){
							//krsort($board);
							krsort($tmp_board_data);
						}
						foreach ( $tmp_board_data as $board_row ) {
							$tmp_board = format_helper_board ( $board_row );
							$board [] = $tmp_board;
						}
					}
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $page_max;
					$this->data_result ['data'] = $board;
				}else{
					$this->status_code = 200; // 正確
					$this->data_result ['info'] ['count'] = $board_count;
					$this->data_result ['info'] ['page_max'] = $board_count;
				}
			} else {
				$this->status_code = 400;
			}
			$this->benchmark->mark ( 'code_end' );
			$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->data_result = array_merge ( $this->data_result, array (
					'time' => $code_time
			) );
			$this->response ( $this->data_result, $this->status_code );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	// 留言包含回應
	// http://xxx.xxx.xxx/api/boards/message_reply?programme=3
	// http://xxx.xxx.xxx/api/boards/message_reply?video_type=episode&video_id=205
	public function message_reply_get() {
		try {
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$result = array ();
			$programme = $this->get ( 'programme' );
			$video_type = $this->get ( 'video_type' );
			$video_id = $this->get ( 'video_id' );
			$sort = ($this->get ( 'sort' ) == '1') ? 'ASC' : 'DESC';
			$page = $this->get ( 'page' );
			if (empty ( $page ) || $page <= 0) {
				$page = 1;
			}
			$pagesize = $this->get ( 'pagesize' );
			if (empty ( $pagesize ) || $pagesize <= 0) {
				$pagesize = 10;
			} elseif ($pagesize > 50) {
				$pagesize = 50;
			}
			// 驗證
			if (empty ( $programme ) && (empty ( $video_type ) || empty ( $video_id ))) {
				$this->response ( NULL, 400 );
			}
			if ($video_id) {
				// 直播/影片留言
				$board_count = $this->board_model->get_video_count ( $video_type, $video_id );
				$tmp_board_data = $this->board_model->get_video_board ( $video_type, $video_id, $sort, $page - 1, $pagesize );
			} else {
				// 節目留言
				// 節目可能包含episode或channel所以不設type
				$board_count = $this->board_model->get_programme_count ( $programme );
				$tmp_board_data = $this->board_model->get_programme_board ( $programme, $sort, $page - 1, $pagesize );
			}
			// 資料整理
			$page_max = ceil ( $board_count / $pagesize );
			if (count ( $tmp_board_data ) > 0) {
				foreach ( $tmp_board_data as $board_row ) {
					// 留言回應
					$reply_page = 1;
					$reply_pagesize = 10;
					$reply_count = $this->board_model->get_reply_count ( $board_row->b_no );
					$reply_page_max = ceil ( $reply_count / $reply_pagesize );
					$tmp_reply_data = $this->board_model->get_reply_board ( $board_row->b_no, $reply_page - 1, $reply_pagesize );
					if (count ( $tmp_reply_data ) > 0) {
						foreach ( $tmp_reply_data as $reply_row ) {
							$reply [] = format_helper_board_reply ( $reply_row );
						}
					} else {
						$reply = array ();
					}
					$tmp_board = format_helper_board ( $board_row );
					$tmp_board ['reply'] = array (
							'info' => array (
									'count' => $reply_count,
									'page' => $reply_page,
									'page_max' => $reply_page_max,
									'page_size' => $reply_pagesize 
							),
							'data' => $reply 
					);
					$board [] = $tmp_board;
				}
				$result = array (
						'info' => array (
								'count' => $board_count,
								'page' => $page,
								'page_max' => $page_max,
								'page_size' => $pagesize 
						),
						'data' => $board 
				);
				$this->benchmark->mark ( 'code_end' );
				$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
				$result = array_merge ( $result, array (
						'time' => $code_time 
				) );
				$this->response ( $result, 200 );
			} else {
				$result = array (
						'info' => array (
								'count' => 0,
								'page' => $page,
								'page_max' => 0,
								'page_size' => $pagesize 
						),
						'data' => array () 
				);
				$this->response ( $result, 204 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function message_reply_post() {
		try {
			echo 'post';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function message_reply_put() {
		try {
			echo 'put';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function message_reply_delete() {
		try {
			echo 'delete';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	
	// http://xxx.xxx.xxx/api/boards/reply?msg_no=1
	public function reply_get() {
		try {
			// $this->output->cache(1);
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$result = array ();
			$msg_no = $this->get ( 'msg_no' );
			$sort = $this->get ( 'sort' );
			$page = $this->get ( 'page' );
			if (empty ( $page ) || $page <= 0) {
				$page = 1;
			}
			$pagesize = $this->get ( 'pagesize' );
			if (empty ( $pagesize ) || $pagesize <= 0) {
				$pagesize = 10;
			} elseif ($pagesize > 50) {
				$pagesize = 50;
			}
			// 驗證
			if (empty ( $msg_no )) {
				$this->response ( NULL, 400 );
			}
			// 留言回應
			$reply_count = $this->board_model->get_reply_count ( $msg_no );
			$reply_page_max = ceil ( $reply_count / $pagesize );
			$tmp_reply_data = $this->board_model->get_reply_board ( $msg_no, $page - 1, $pagesize );
			if (count ( $tmp_reply_data ) > 0) {
				foreach ( $tmp_reply_data as $reply_row ) {
					$reply [] = format_helper_board_reply ( $reply_row );
				}
				$result = array (
						'info' => array (
								'count' => $reply_count,
								'page' => $page,
								'page_max' => $reply_page_max,
								'page_size' => $pagesize 
						),
						'data' => $reply 
				);
				$this->benchmark->mark ( 'code_end' );
				$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
				$result = array_merge ( $result, array (
						'time' => $code_time 
				) );
				$this->response ( $result, 200 );
			} else {
				$this->response ( NULL, 204 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function reply_post() {
		try {
			$this->benchmark->mark ( 'code_start' );
			// 變數
			$result = array ();
			// $programme = $this->post ( 'programme' );
			$programme = 0;
			$video_type = $this->post ( 'video_type' );
			// $video_id = $this->post ( 'video_id' );
			$video_id = 0;
			$reoly = $this->post ( 'reoly' );
			$user = $this->post ( 'user' );
			$member_id = $this->post ( 'member_id' );
			$nick_name = $this->post ( 'nick_name' );
			$propic = $this->post ( 'propic' );
			$msg = $this->post ( 'msg' );
			// $barrage = $this->post ( 'barrage' );
			$barrage = '';
			// $color = $this->post ( 'color' );
			$color = '';
			// $size = $this->post ( 'size' );
			$size = '';
			// $video_time = $this->post ( 'video_time' );
			$video_time = '';
			// $position = $this->post ( 'position' );
			$position = '';
			// 檢驗碼
			// $token = $this->input->server('HTTP_TOKEN');
			// $headers = apache_request_headers();
			// $token = $headers['authorization'];
			$token = null;
			if (token_postgre_check ( $token )) {
				// 驗證
				if (empty ( $reoly ) || empty ( $member_id ) || empty ( $msg )) {
					$this->response ( NULL, 400 );
				}
				$insert_no = $this->board_model->insert_reply ( $programme, $video_type, $video_id, $reoly, $user, $member_id, $nick_name, $propic, $msg, $barrage, $color, $size, $video_time, $position );
				// if ($barrage == 'Y') {
				// $this->load->model ( 'websocket/barrage_model' );
				// $this->barrage_model->copy_board_data ( $insert_no );
				// }
				$result ['data'] = format_helper_board_reply ( $this->board_model->get_reply ( $insert_no ) );
				// 結果
				if ($insert_no) {
					$this->benchmark->mark ( 'code_end' );
					$code_time = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
					$result = array_merge ( $result, array (
							'time' => $code_time 
					) );
					$this->response ( $result, 201 );
				} else {
					$this->response ( NULL, 405 );
				}
			} else {
				$this->response ( NULL, 401 );
			}
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function reply_put() {
		try {
			echo 'put';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function reply_delete() {
		try {
			echo 'delete';
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
