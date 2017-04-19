<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Websocket
{

    private $_CI;

    public function __construct ($data = NULL, $from_type = NULL)
    {
        $this->_CI = &get_instance();
        require APPPATH . '/third_party/websocket-php-master/lib/Base.php';
        require APPPATH . '/third_party/websocket-php-master/lib/Exception.php';
        require APPPATH . '/third_party/websocket-php-master/lib/ConnectionException.php';
        require APPPATH . '/third_party/websocket-php-master/lib/Client.php';
    }

    public function barrage ($data)
    {
        echo APPPATH;
        if (! empty($data)) {
            $sk_url = 'ws://54.199.206.243:8080/barrage';
            $sk_data = '{"video_type":"channel","video_id":"205","msg":"影音34"}';
            $client = new Client($sk_url);
            $client->send($sk_data);
            $status = $client->receive();
            if (! empty($status)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
