<?php
/**
 *
 * koubei.php
 *
 * Author: swen@verystar.cn
 * Create: 28/03/2018 16:15
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

//购买接口

$api_return_data = $pay->koubeiUnifiedOrder(
    [
        'total_fee'          => 1,
        'out_sn'             => getUniqueCode(),
        'body'               => 'TEST礼品卡',
        'client_sn'          => '10001',
        'store_sn'           => '10001',
        'buyer_id'           => '123',
        'shop_id'            => '123',
        'biz_product'        => 'ONLINE_PURCHASE',
        'biz_scene'          => 'giftCard',
        'item_order_details' => json_encode(
            [
                [
                    'sku_id'         => '123456',
                    'original_price' => '0.01',
                    'price'          => '0.01',
                    'quantity'       => '1',
                ]
            ]
        )
    ]
);

echo print_r($api_return_data, true) . PHP_EOL;