<?php
/**
 * Created by PhpStorm.
 * User: 蔡旭东 caixudong@verystar.cn
 * Date: 2018/10/8 9:23 PM
 */

require '../vendor/autoload.php';

use Verypay\Pay;

$notify_posts = $_POST;

//实例化支付对象
$pay = new Pay(
    [
        'app_key'    => '您的app_key',
        'app_secret' => '您的app_secret',
    ]
);

$ok = $pay->checkNotifySign($notify_posts);

if ($ok) {
    echo "success";
} else {
    echo "fail";
}