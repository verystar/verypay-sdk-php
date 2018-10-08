<?php
/**
 * Author: swen@verystar.cn
 * Create: 16-7-5 17:09
 * Editor: created by PhpStorm
 */

require '../vendor/autoload.php';

use Verypay\Pay;

/**
 * 获取唯一码
 *
 * @param  string $prefix 默认空
 *
 * @return string 返回前缀加 + 年月日时分秒 + 微妙数 + 6位随机码
 */
function getUniqueCode($prefix = '')
{
    return $prefix . date('YmdHis') . substr(microtime(), 2, 6) . sprintf('%06d', mt_rand(0, 999999), STR_PAD_LEFT);
}

//实例化支付对象
$pay = new Pay(
    [
        'app_key'    => '您的app_key',
        'app_secret' => '您的app_secret',
        'test'       => true,
    ]
);

// 实例一：生成唯一订单号 unifidoOrder
//$ret = $pay->unifidoOrder(
//    [
//        'store_sn'  => '10001',
//        'client_sn' => '10001',
//        'total_fee' => 10,
//        'out_sn'    => '19' . mt_rand(10000, 99999),
//        'body'      => 'test goods',
//        'channel'   => 'wx'
//    ]
//);
//
//var_export($ret);

//实例二：刷卡支付
//$ret = $pay->micropay(
//    [
//        'store_sn'  => '10001',
//        'client_sn' => '10001',
//        'auth_code' => '280403182600310552',
//        'total_fee' => '5',
//        'out_sn'    => getUniqueCode(),
//    ]
//);
//
//var_export($ret);

//实例三：查询订单
//$ret = $pay->queryOrder(
//    [
//        'store_sn'  => '10001',
//        'client_sn' => '10001',
//        'out_sn'    => '3118032801265479615070',
//    ]
//);
//
//var_export($ret);


//实例四：退款
//$ret = $pay->refund(
//    [
//        'store_sn'      => '10001',
//        'client_sn'     => '10001',
//        'out_sn'        => '199909090',
//        'refund_fee'    => '5',
//        'op_user_id'    => '10001',
//        'op_user_pwd'   => '123456',
//        'refund_out_sn' => '123123'
//    ]
//);
//
//var_export($ret);

//实例五：关闭订单
//$ret = $pay->closeOrder(
//    [
//        'store_sn'  => '10001',
//        'client_sn' => '10001',
//        'out_sn'    => '1957',
//    ]
//);


//实例六：查询卡券
//$ret = $pay->ticketQuery(
//    [
//        'store_sn'    => '10001',
//        'client_sn'   => '10001',
//        'ticket_code' => '11',
//        'ticket_from' => '1'
//    ]
//);


//实例七：卡券核销
//$ret = $pay->ticketVerify(
//    [
//        'store_sn'    => '10001',
//        'client_sn'   => '10001',
//        'ticket_code' => '11',
//        'ticket_from' => '1'
//    ]
//);

//实例八： 订单查询门店
//$ret = $pay->storeQuery([
//
//]);

// 实例九: 门店查询
//$ret = $pay->storeInfo([
//    'store_sn'  => '10001',
//    'client_sn' => '10001',
//]);

//实例十： 获取支付码
//$ret = $pay->getPayCode([
//
//]);

// 实例十一： 付码查询支付结果
//$ret = $pay->queryPayCode([
//
//]);

// 实例十二： 获取支付宝 app_auth_token
// $ret = $pay->getAlipayAuthToken();

// 实例十三： 口碑商品交易购买接口
//$ret = $pay->koubeiUnifiedOrder([
//
//]);

// 实例十四： 口碑商品交易查询接口
//$ret = $pay->koubeiQueryOrder([
//
//]);

// 实例十五： 口碑商品交易退货接口
$ret = $pay->koubeiRefund([

]);

var_export($ret);





