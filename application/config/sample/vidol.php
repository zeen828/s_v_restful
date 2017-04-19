<?php
defined('BASEPATH') or exit('No direct script access allowed');

//伺服器IP
$config['server_ip'] = '127.0.0.1';

// 寄送信件使用
$config['email_from'] = 'noreply@email'; // 寄件者
$config['email_reply'] = 'noreply@email'; // 信件回覆
$config['email_bcc'] = array(
		'noreply@email',
); // 不記名副本

// 寄送mail忘記密碼
$config['email_user_password_uri'] = '';
$config['email_user_password_doman'] = '';
$config['email_user_password_id'] = '';

// 寄送mail信箱認證
$config['email_user_verify_vidol_uri'] = '';
$config['email_user_verify_uri'] = '';
$config['email_user_verify_doman'] = '';
$config['email_user_verify_id'] = '';

// google authenticator key
$config['google_authenticator_key'] = '';