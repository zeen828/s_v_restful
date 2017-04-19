<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . '/third_party/websocket-php-master/lib/Base.php';
// require APPPATH . '/third_party/websocket-php-master/lib/Exception.php';
// require APPPATH . '/third_party/websocket-php-master/lib/ConnectionException.php';
// require APPPATH . '/third_party/websocket-php-master/lib/Client.php';
require APPPATH . '/third_party/Facebook/autoload.php';
require APPPATH . '/third_party/Wwebsocket/Exception.php';
require APPPATH . '/third_party/Wwebsocket/ConnectionException.php';
require APPPATH . '/third_party/Wwebsocket/Base.php';
require APPPATH . '/third_party/Wwebsocket/Client.php';
use WebSocket\Client;

class Homes extends CI_Controller
{

    public function index ()
    {
        show_404();
    }
    
    public function fbtest ()
    {
        require APPPATH . '/third_party/Facebook/autoload.php';
        $tmp['facebook_app_id'] = '1090679151006121';
        $tmp['facebook_app_secret'] = 'fcd21c1e7cd3315b0a167028366da00d';
        $fb = new Facebook\Facebook([
                'app_id' => $tmp['facebook_app_id'],
                'app_secret' => $tmp['facebook_app_secret'],
                'default_graph_version' => 'v2.7'
        ]);
        print_r($fb);
        
        $helper = $fb->getRedirectLoginHelper();
        //$helper = $fb->getCanvasHelper();
        print_r($helper);
        $permissions = array('public_profile', 'publish_actions', 'email'); // optional
        $callback = 'http://plugin-background.vidol.tv/homes/fb_user';
        $loginUrl = $helper->getLoginUrl($callback, $permissions);
        echo '<a href="'.$loginUrl.'">Log in with Facebook!</a>';
    }
    
    public function fb_user(){
        require APPPATH . '/third_party/Facebook/autoload.php';
        $tmp['facebook_app_id'] = '1090679151006121';
        $tmp['facebook_app_secret'] = 'fcd21c1e7cd3315b0a167028366da00d';
        $fb = new Facebook\Facebook([
                'app_id' => $tmp['facebook_app_id'],
                'app_secret' => $tmp['facebook_app_secret'],
                'default_graph_version' => 'v2.7'
        ]);
        //print_r($fb);
        try {
            $helper = $fb->getRedirectLoginHelper();
            $accessToken = $helper->getAccessToken();
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,about,age_range,bio,birthday,cover,currency,devices,education,email,favorite_athletes,favorite_teams,first_name,gender,hometown,inspirational_people,install_type,installed,interested_in,is_verified,languages,last_name,link,locale,location,meeting_for,middle_name,name,name_format,payment_pricepoints', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        $user = $response->getGraphUser();
        print_r($user);
        
        echo 'Name: ' . $user['name'];
        
    }

    public function test ()
    {
        // $sk_url = 'ws://echo.websocket.org/';
        $sk_url = 'ws://54.199.206.243:8080/barrage';
        // $sk_data = 'Hello WebSocket.org!';
        // $sk_data = '{"video_type":"episode","video_id":"205"}';
        $sk_data = '{"video_type":"live","video_id":"798","msg":"影音798"}';
        $client = new Client($sk_url);
        $client->send($sk_data);
        echo $client->receive(); // Will output 'Hello WebSocket.org!'
    }

    public function test2 ()
    {
        $this->load->add_package_path(APPPATH . 'third_party/');
        $this->load->library("websocket", '', 'ws');
        $ws_data = json_encode(array(
                'video_type' => 'live',
                'video_id' => 798,
                'msg' => 'test php websocket'
        ));
        $this->ws->barrage($ws_data);
    }

    public function pdb ()
    {
        $this->p_db = $this->load->database('postgre_read', TRUE);
        echo $this->p_db->platform();
        echo $this->p_db->version();
        // $query = $this->p_db->query('select * from oauth_access_tokens limit
        // 10;');
        // if ($query->num_rows() > 0) {
        // foreach ($query->result() as $row) {
        // print_r($row);
        // }
        // }
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZGVudGl0eSI6eyJpZCI6MjU1NzIsInVpZCI6IjZlM1YzVGZ3VFgiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZX0sImFwcGxpY2F0aW9uX2lkIjoxLCJleHBpcmVzX2F0IjoxNDY2MzIxODcwLCJyYW5kX2tleSI6IjY3NjNjNWRhODkzMjVjOWMyYzU0Y2FkYTE4N2RiYmQ0In0.VAPUEVhUgHR8wHCu5F-nbRmikwKkhdvrHzFE5JaYjybku4LNpmsbRq5Tycr0atc3v6uxokw2o-zhGxrpO_eOTA';
        $this->p_db->limit(100);
        $this->p_db->order_by('id', 'DESC');
        $this->p_db->select('*, now() as now_date');
        //$this->p_db->where('token', $token);
        $query = $this->p_db->get('oauth_access_tokens');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                print_r($row);
                $expires = $row->expires_in;
                $time_created = strtotime($row->created_at);
                $time_now = strtotime($row->now_date);
                if(($time_now - $time_created) <= $row->expires_in){
                    $a = 'a';
                }else{
                    $a = 'b';
                }
                print_r(array(
                        $a,
                        $time_now - $time_created,
                        $expires,
                        $time_created,
                        $time_now
                ));
            }
        }
    }
    
    public function tttt(){
        $this->load->add_package_path(APPPATH . 'third_party/Facebook')->library('Facebook');
    }
    
    public function www(){
    	//$this->load->add_package_path(APPPATH . 'third_party/Wwebsocket');
    	$client = new Client('ws://ws-event.vidol.tv:8080/barrage:{"video_type":"event","video_id":"76"}');
    	$client->send('{"video_type":"event","video_id":"76","data":{"video_type":"event","video_id":"76","video_time":"0","member_id":"u0tc9T","nick_name":"Chyan Lu","propic":"https:\/\/graph.facebook.com\/1107054302667151\/picture?type=small","messages":"You could probably get that result using a (multi-line) UILabel and a UIButton and no subclassing. Configure your UILabel to be multiline (i.e. 0 lines) and align all the button edges to those of the UILabel. The UILabel will resize according to content and the button will follow. you can then use the UILabel as your button\'s text or just place it underneath the button and duplicate the button\'s text in it programatically.","time_unix":1473673837}}');
    	echo $client->receive();
    }
    
    public function phpinfo(){
    	//phpinfo();
    }
    
    public function db_close() {
    	try {
    		$this->r_db = $this->load->database ( 'postgre_production_read', TRUE );
    		$this->w_db = $this->load->database ( 'postgre_production_write', TRUE );
    		var_dump ( $this->r_db );
    		var_dump ( $this->w_db );
    		$this->r_db->close ();
    		$this->w_db->close ();
    		var_dump ( $this->r_db );
    		var_dump ( $this->w_db );
    		unset ( $this->r_db );
    		unset ( $this->w_db );
    		var_dump ( $this->r_db );
    		var_dump ( $this->w_db );
    	} catch ( Exception $e ) {
    		show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
    	}
    }
    
}
