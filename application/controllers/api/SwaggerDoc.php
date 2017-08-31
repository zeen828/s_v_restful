<?php
defined ( "BASEPATH" ) or exit ( "No direct script access allowed" );
class SwaggerDoc extends CI_Controller {
	private $data_view;
	function __construct() {
		parent::__construct ();
	}
	public function index() {
		$api_host = $_SERVER ['HTTP_HOST'];
		$doc_array = array (
				"swagger" => "2.0",
				"info" => array (
						"title" => "RESTful API Documentation",
						"description" => "RESTful api control panel of technical documents.",
						"termsOfService" => "#",
						"contact" => array (
								"email" => "zeren828@gmail.com" 
						),
						"license" => array (
								"name" => "Apache 2.0",
								"url" => "#" 
						),
						"version" => "V 1.0" 
				),
				"host" => $api_host,
				"basePath" => "/api",
				"tags" => array (
						array (
								"name" => "1.boards",
								"description" => "留言板" 
						),
						array (
								"name" => "2.users",
								"description" => "使用者" 
						),
						array (
								"name" => "3.devices",
								"description" => "設備" 
						),
						array (
								"name" => "9.other",
								"description" => "其他" 
						) 
				),
				"schemes" => array (
						"http" 
				),
				"paths" => array (
						"/boards/message" => array (
								"post" => array (
										"tags" => array (
												"1.boards" 
										),
										"summary" => "建立留言",
										"description" => "建立留言",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "token",
														"description" => "Middle Layer token[無法傳送header時備用]",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "programme",
														"description" => "節目編號",
														"in" => "formData",
														"type" => "integer" 
												),
												array (
														"name" => "video_type",
														"description" => "影音類型",
														"in" => "formData",
														"type" => "string",
														"required" => TRUE,
														"enum" => array (
																"episode",
																"channel",
																"live",
																"event" 
														) 
												),
												array (
														"name" => "video_id",
														"description" => "影音編號",
														"in" => "formData",
														"type" => "integer",
														"required" => TRUE 
												),
												array (
														"name" => "user",
														"description" => "會員編號",
														"in" => "formData",
														"type" => "integer" 
												),
												array (
														"name" => "user_id",
														"description" => "mongo id",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "member_id",
														"description" => "會員id",
														"in" => "formData",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "nick_name",
														"description" => "會員暱稱",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "propic",
														"description" => "會員圖像",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "msg",
														"description" => "留言訊息",
														"in" => "formData",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "barrage",
														"description" => "彈幕[Y,N(Y才會送websocket)]",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "color",
														"description" => "彈幕顏色",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "size",
														"description" => "彈幕尺寸",
														"in" => "formData",
														"type" => "integer" 
												),
												array (
														"name" => "video_time",
														"description" => "彈幕時間",
														"in" => "formData",
														"type" => "integer" 
												),
												array (
														"name" => "position",
														"description" => "彈幕位置",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "debug",
														"description" => "除錯",
														"in" => "formData",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"201" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"status" => array (
																				"type" => "boolean",
																				"description" => "狀態" 
																		),
																		"info" => $this->__get_responses_data ( "page info" ),
																		"data" => $this->__get_responses_data ( "boards data" ) 
																) 
														) 
												),
												"400" => array (
														"description" => "資料錯誤" 
												),
												"401" => array (
														"description" => "未授權" 
												),
												"404" => array (
														"description" => "失敗" 
												) 
										) 
								) 
						),
						"/boards/message_app" => array (
								"get" => array (
										"tags" => array (
												"1.boards" 
										),
										"summary" => "取得留言(瀑布流)",
										"description" => "取得留言(瀑布流)",
										"parameters" => array (
												array (
														"name" => "programme",
														"description" => "節目編號",
														"in" => "query",
														"type" => "string" 
												),
												array (
														"name" => "video_type",
														"description" => "影音類型",
														"in" => "query",
														"type" => "string",
														"required" => TRUE,
														"enum" => array (
																"episode",
																"channel",
																"live",
																"event" 
														) 
												),
												array (
														"name" => "video_id",
														"description" => "影音編號",
														"in" => "query",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "sort",
														"description" => "排序[1:舊到新,0:新到舊(查詢資料)]",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "start_no",
														"description" => "目前資料最少留言編號 (首次為0抓最新)",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "pagesize",
														"description" => "每頁資料數",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "debug",
														"description" => "除錯",
														"in" => "query",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"status" => array (
																				"type" => "boolean",
																				"description" => "狀態" 
																		),
																		"info" => $this->__get_responses_data ( "page info" ),
																		"data" => $this->__get_responses_data ( "boards data" ) 
																) 
														) 
												),
												"204" => array (
														"description" => "沒有資料" 
												),
												"400" => array (
														"description" => "資料錯誤" 
												) 
										) 
								) 
						),
						"/boards/message_web" => array (
								"get" => array (
										"tags" => array (
												"1.boards" 
										),
										"summary" => "取得留言(瀑布流)",
										"description" => "取得留言(瀑布流)",
										"parameters" => array (
												array (
														"name" => "programme",
														"description" => "節目編號",
														"in" => "query",
														"type" => "string" 
												),
												array (
														"name" => "video_type",
														"description" => "影音類型",
														"in" => "query",
														"type" => "string",
														"required" => TRUE,
														"enum" => array (
																"episode",
																"channel",
																"live",
																"event" 
														) 
												),
												array (
														"name" => "video_id",
														"description" => "影音編號",
														"in" => "query",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "sort",
														"description" => "排序[1:舊到新,0:新到舊(查詢資料)]",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "start_no",
														"description" => "目前資料最少留言編號 (首次為0抓最新)",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "pagesize",
														"description" => "每頁資料數",
														"in" => "query",
														"type" => "integer" 
												),
												array (
														"name" => "debug",
														"description" => "除錯",
														"in" => "query",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"status" => array (
																				"type" => "boolean",
																				"description" => "狀態" 
																		),
																		"info" => $this->__get_responses_data ( "page info" ),
																		"data" => $this->__get_responses_data ( "boards data" ) 
																) 
														) 
												),
												"204" => array (
														"description" => "沒有資料" 
												),
												"400" => array (
														"description" => "資料錯誤" 
												) 
										) 
								) 
						),
						"/users/send_mail_verify" => array (
								"post" => array (
										"tags" => array (
												"2.users" 
										),
										"summary" => "重發認證信",
										"description" => "重發認證信",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "email",
														"description" => "信箱",
														"in" => "formData",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功",
														"schema" => array (
																"title" => "result",
																"type" => "object",
																"description" => "api result data",
																"properties" => array (
																		"status" => array (
																				"type" => "boolean",
																				"description" => "狀態" 
																		) 
																) 
														) 
												),
												"400" => array (
														"description" => "資料錯誤" 
												),
												"401" => array (
														"description" => "未授權" 
												),
												"416" => array (
														"description" => "請求範圍不符合" 
												) 
										) 
								) 
						),
						"/devices/tvbox" => array (
								"post" => array (
										"tags" => array (
												"3.devices" 
										),
										"summary" => "電視盒會員送",
										"description" => "電視盒會員送",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "user_no",
														"description" => "會員(user_pk等會員整合後使用)",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "mongo_id",
														"description" => "會員mongo_id",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "member_id",
														"description" => "會員ID",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "dealer",
														"description" => "經銷商",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "key_word",
														"description" => "關鍵字",
														"in" => "formData",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功" 
												) 
										) 
								) 
						),
						"/other/check/sms" => array (
								"get" => array (
										"tags" => array (
												"9.other" 
										),
										"summary" => "檢驗簡訊",
										"description" => "檢驗簡訊",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "phone",
														"description" => "行動電話號碼",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "code",
														"description" => "簡訊",
														"in" => "formData",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功" 
												) 
										) 
								) 
						),
						"/other/send/sms" => array (
								"post" => array (
										"tags" => array (
												"9.other" 
										),
										"summary" => "發送簡訊",
										"description" => "發送簡訊",
										"parameters" => array (
												array (
														"name" => "Authorization",
														"description" => "Middle Layer token",
														"in" => "header",
														"type" => "string",
														"required" => TRUE 
												),
												array (
														"name" => "phone",
														"description" => "電話號碼",
														"in" => "formData",
														"type" => "string" 
												),
												array (
														"name" => "msm",
														"description" => "簡訊內容",
														"in" => "formData",
														"type" => "string" 
												) 
										),
										"responses" => array (
												"200" => array (
														"description" => "成功" 
												) 
										) 
								) 
						) 
				) 
		);
		$this->output->set_content_type ( "application/json" );
		$this->output->set_output ( json_encode ( $doc_array ) );
	}
	
	/**
	 * 回傳的資料整理
	 *
	 * @param unknown $type        	
	 * @return string[]
	 */
	function __get_responses_data($type) {
		$responses = array ();
		switch ($type) {
			case "page info" :
				$responses = array (
						"title" => "page info",
						"type" => "object",
						"description" => "回傳頁數資訊",
						"properties" => array (
								"count" => array (
										"type" => "integer",
										"description" => "資料總比數" 
								),
								"page" => array (
										"type" => "integer",
										"description" => "頁數" 
								),
								"page_max" => array (
										"type" => "integer",
										"description" => "最大頁數" 
								),
								"page_size" => array (
										"type" => "integer",
										"description" => "每頁筆數" 
								) 
						) 
				);
				break;
			case "boards data" :
				$responses = array (
						"title" => "boards data",
						"type" => "object",
						"description" => "留言資料",
						"properties" => array (
								"no" => array (
										"type" => "integer",
										"description" => "留言編號" 
								),
								"video_type" => array (
										"type" => "string",
										"description" => "影音類型" 
								),
								"video_id" => array (
										"type" => "integer",
										"description" => "影音編號" 
								),
								"member_id" => array (
										"type" => "string",
										"description" => "會員id" 
								),
								"nick_name" => array (
										"type" => "string",
										"description" => "會員暱稱" 
								),
								"propic" => array (
										"type" => "string",
										"description" => "會員圖像" 
								),
								"messages" => array (
										"type" => "string",
										"description" => "留言訊息" 
								),
								"video_time" => array (
										"type" => "integer",
										"description" => "彈幕時間" 
								),
								"color" => array (
										"type" => "string",
										"description" => "彈幕顏色" 
								),
								"size" => array (
										"type" => "integer",
										"description" => "彈幕尺寸" 
								),
								"time_tw" => array (
										"type" => "string",
										"description" => "留言時間" 
								),
								"time_utc" => array (
										"type" => "string",
										"description" => "留言時間" 
								),
								"time_unix" => array (
										"type" => "string",
										"description" => "留言時間" 
								),
								"reply" => array (
										"type" => "array",
										"description" => "回應(參考回應API文件)" 
								) 
						) 
				);
				break;
			case "replys data" :
				$responses = array (
						"title" => "replys data",
						"type" => "object",
						"description" => "留言資料",
						"properties" => array (
								"no" => array (
										"type" => "integer",
										"description" => "留言編號" 
								),
								"video_type" => array (
										"type" => "string",
										"description" => "影音類型" 
								),
								"video_id" => array (
										"type" => "integer",
										"description" => "影音編號" 
								),
								"nick_name" => array (
										"type" => "string",
										"description" => "會員暱稱" 
								),
								"propic" => array (
										"type" => "string",
										"description" => "會員圖像" 
								),
								"messages" => array (
										"type" => "string",
										"description" => "留言訊息" 
								),
								"video_time" => array (
										"type" => "integer",
										"description" => "彈幕時間" 
								),
								"color" => array (
										"type" => "string",
										"description" => "彈幕顏色" 
								),
								"size" => array (
										"type" => "integer",
										"description" => "彈幕尺寸" 
								),
								"time_tw" => array (
										"type" => "string",
										"description" => "留言時間" 
								),
								"time_utc" => array (
										"type" => "string",
										"description" => "留言時間" 
								),
								"time_unix" => array (
										"type" => "string",
										"description" => "留言時間" 
								) 
						) 
				);
				break;
			default :
				$responses = array (
						"description" => "OK" 
				);
				break;
		}
		return $responses;
	}
}

/* End of file swaggerDoc.php */
/* Location: ./application/controllers/api/swaggerDoc.php */
