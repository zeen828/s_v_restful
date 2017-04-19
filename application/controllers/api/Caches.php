<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

require_once APPPATH . '/libraries/REST_Controller.php';
class Caches extends REST_Controller {
	private $data_result;
	public function __construct() {
		parent::__construct ();
		// 引用
		$this->load->helper ( 'formats' );
		// 初始化
		$this->data_result = format_helper_return_data ();
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function cancel_configs_ios_get() {
		try {
			$cancel ['api'] = $this->output->delete_cache ( '/api/configs/ios' );
			$cancel ['json'] = $this->output->delete_cache ( '/api/configs/ios.json' );
			$this->response ( $cancel, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function cancel_configs_slide_get() {
		try {
			$cancel ['api'] = $this->output->delete_cache ( '/api/configs/slide' );
			$cancel ['json'] = $this->output->delete_cache ( '/api/configs/slide.json' );
			$this->response ( $cancel, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function cancel_configs_tvbox_get() {
		try {
			$cancel ['api'] = $this->output->delete_cache ( '/api/configs/tvbox' );
			$cancel ['json'] = $this->output->delete_cache ( '/api/configs/tvbox.json' );
			$this->response ( $cancel, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function cancel_billings_get() {
		try {
			$cancel ['api'] = $this->output->delete_cache ( '/api/configs/ios' );
			$cancel ['json'] = $this->output->delete_cache ( '/api/configs/ios.json' );
			$this->response ( $cancel, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function cancel_vote_get() {
		try {
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			$cache_name = sprintf ( '%s_%s_data_result', ENVIRONMENT, 'vote' );
			$this->data_result = $this->cache->memcached->delete ( $cache_name );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
	public function cancel_afternoon_get() {
		try {
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			$cache_name = sprintf ( '%s_%s_data_result', ENVIRONMENT, 'afternoon' );
			$this->data_result = $this->cache->memcached->delete ( $cache_name );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
