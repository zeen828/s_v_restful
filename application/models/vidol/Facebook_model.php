<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/third_party/Facebook/autoload.php';

class Facebook_model extends CI_Model
{

    private $FB;

    private $APP_ID;

    private $APP_SECRET;

    private $APP_TYPE;

    private $APP_VERSION;

    private $APP_PERMISSIONS;

    private $ACCESS_TOKEN;

    public function __construct ()
    {
        parent::__construct();
        $this->config->load('facebook');
        $this->APP_ID = $this->config->item('facebook_app_id');
        $this->APP_SECRET = $this->config->item('facebook_app_secret');
        $this->APP_TYPE = $this->config->item('facebook_login_type');
        $this->APP_VERSION = $this->config->item('facebook_graph_version');
        $this->APP_PERMISSIONS = $this->config->item('facebook_permissions');
        // 連接FB
        $this->FB = new Facebook\Facebook([
                'app_id' => $this->APP_ID,
                'app_secret' => $this->APP_SECRET,
                'default_graph_version' => $this->APP_VERSION
        ]);
    }

    public function get_login_url ()
    {
        $helper = $this->FB->getRedirectLoginHelper();
        $callback = $this->config->item('facebook_login_redirect_url');
        $loginUrl = $helper->getLoginUrl($callback, $this->APP_PERMISSIONS);
        return $loginUrl;
    }

    public function get_access_token ()
    {
        $helper = $this->FB->getRedirectLoginHelper();
        $this->ACCESS_TOKEN = $helper->getAccessToken();
    }

    public function get_user ()
    {
        $user_fields = $this->config->item('facebook_user_fields');
        $response = $this->FB->get(sprintf('/me?fields=%s', $user_fields), $this->ACCESS_TOKEN);
        $user = $response->getGraphUser();
        return $user;
    }
}
