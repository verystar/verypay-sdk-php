### VeryPay SDK

> 本SDK适用于php开发语言接入VeryPay支付系统

## 安装

在项目目录下创建一个 composer.json 文件，指明依赖如下：

```
{
    "require": {
        "verystar/pay": "~1.1"
    }
}
```

执行`composer install`安装

## 使用

```
<?php
require 'vendor/autoload.php';

use Verypay\Pay;

//实例化支付对象
$pay = new Pay(
    [
        'app_key'    => '您的app_key',
        'app_secret' => '您的app_secret'
    ]
);

//实例二：生成唯一订单号
$ret = $pay->unifidoOrder(
    [
        'store_sn'  => '10001',
        'client_sn' => '10001',
        'total_fee' => 10,
        'out_sn'    => '19' . rand(10, 99),
        'body'      => 'test goods',
        'channel'   => 'wx'
    ]
);

```
