<?php namespace Verypay;

/**
 *
 * VeryPay
 *
 * Author: swen@verystar.cn
 * Create: 16-7-4 10:07
 * Editor: created by PhpStorm
 */

use Verypay\Lib\Curl;
use Verypay\Lib\Helper;

class Pay
{
    protected $output = 'json';

    /**
     * @var Curl
     */
    protected $curl;

    /***
     *
     * 接口域名
     *
     * @var string
     */
    private $api_url = 'https://api.pay.verystar.cn/';

    /****
     *
     * 测试接口
     *
     * @var string
     */
    private $test_api_url = 'http://test.api.pay.verystar.cn/';


    /****
     *
     * 测试接口
     *
     * @var string
     */
    private $internal_api_url = 'http://api-internal.pay.verystar.cn/';
    /***
     *
     * 接口版本
     *
     * @var string
     */
    private $ver = 'v3';

    /***
     *
     * 当前支付类别
     *
     * @var
     */
    protected $pay_channel;

    /***
     *
     * 商户app_key
     *
     * @var string
     */
    private $app_key = "";

    /***
     *
     * 是否测试接口
     *
     * @var bool
     */
    private $test = false;


    /***
     *
     * 开启internal地址
     *
     * @var bool
     */
    private $internal = false;

    /***
     *
     * 商户秘钥
     *
     * @var string
     */
    private $app_secret = "";

    public function __construct($options = [])
    {
        if ($options) {
            $this->init($options);
        }
        $this->curl = new Curl();
    }

    private function getServerUrl($api)
    {
        $url = $this->api_url;
        if ($this->test === true) {
            $url = $this->test_api_url;
        } else {
            if ($this->internal) {
                $url = $this->internal_api_url;
            }
        }

        return rtrim($url, '/') . '/' . trim($api, '/') . '.' . $this->output;
    }

    /****
     *
     *
     * 调用middleware支付远程接口
     *
     * @param       $api
     * @param array $data
     *
     * @return array|mixed
     */
    protected function call($api, $data = [])
    {
        //检查key
        if (!$this->app_key) {
            return ['retcode' => 10001, 'msg' => 'app_key错误'];
        }

        //检查秘钥
        if (!$this->app_secret) {
            return ['retcode' => 10003, 'msg' => '秘钥错误'];
        }

        $data['app_key'] = $this->app_key;
        $data['time']    = time();
        $data['sign']    = $this->getSign($data, $this->app_secret);

        $res = $this->curl->post($this->getServerUrl($api), $data);
        if ($res->getError()) {
            return ['retcode' => 400, 'msg' => '服务器繁忙，请重试'];
        }

        $body = json_decode($res->getBody(), true);

        if (!$body || !is_array($body) || !isset($body['retcode'])) {
            return ['retcode' => 10007, 'msg' => '服务器繁忙，请重试'];
        }

        if (!$this->checkSign($body)) {
            return ['retcode' => 10003, 'msg' => '接口验权错误'];
        }

        return $body;
    }

    /**
     * 初始化参数
     *
     * @param array $options
     *
     * @return $this
     */
    public function init($options = array())
    {

        if (isset($options['app_key']) && $options['app_key']) {
            $this->app_key = $options['app_key'];
        }

        if (isset($options['app_secret']) && $options['app_secret']) {
            $this->app_secret = $options['app_secret'];
        }

        //设置版本号
        if (isset($options['ver']) && $options['ver']) {
            $this->ver = $options['ver'];
        }

        //设置测试环境域名
        if (isset($options['test']) && $options['test'] === true) {
            $this->test = true;
        }

        return $this;
    }

    public function setInternal($internal)
    {
        $this->internal = $internal;
        return $this;
    }

    /***
     *
     * 获取URL加密参数sign值
     *
     * @param $params
     * @param $key
     *
     * @return string
     */
    protected function getSign($params, $key)
    {
        // a.除sign 字段外，对所有传入参数按照字段名的ASCII 码从小到大排序（字典序）后，
        // 使用URL 键值对的格式（即key1=value1&key2=value2…）拼接成字符串string；
        // 除去数组中的空值和签名参数
        $para_filter = Helper::paraFilter($params);
        // 对数组排序
        $para_filter = Helper::argSort($para_filter);
        $str         = Helper::createLinkstring($para_filter);
        return md5($str . $key);
    }

    /****
     *
     * 验证服务器接口返回的合法性
     *
     * @param $params
     *
     * @return bool
     */
    protected function checkSign($params)
    {
        $temp_sign = $params['sign'];
        unset($params['sign']);
        $sign = $this->getSign(isset($params['data']) ? $params['data'] : [], $this->app_secret);//本地签名

        return $temp_sign == $sign ? true : false;
    }

    /****
     *
     *
     * 统一下单接口
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function unifidoOrder($data)
    {
        return $this->call($this->ver . '/pay/unifiedorder', $data);
    }

    /****
     *
     * 刷卡支付
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function micropay($data)
    {
        return $this->call($this->ver . '/pay/micropay', $data);
    }

    /****
     *
     * 查询订单
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function queryOrder($data)
    {
        return $this->call($this->ver . '/pay/orderquery', $data);
    }


    /****
     *
     * 申请退款
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function refund($data)
    {
        return $this->call($this->ver . '/pay/refund', $data);
    }

    /***
     *
     * 关闭订单
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function closeOrder($data)
    {
        return $this->call($this->ver . '/pay/closeorder', $data);
    }

    /***
     *
     * 卡券查询
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function ticketQuery($data)
    {
        return $this->call($this->ver . '/ticket/query', $data);
    }

    /***
     *
     * 卡券核销
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function ticketVerify($data)
    {
        return $this->call($this->ver . '/ticket/verification', $data);
    }

    /***
     *
     * 订单查询门店
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function storeQuery($data)
    {
        return $this->call('merchant/store/query', $data);
    }


    /***
     *
     * 门店信息查询
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function storeInfo($data)
    {
        return $this->call('merchant/store/info', $data);
    }


    /***
     *
     * 获取支付码
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function getPayCode($data)
    {
        return $this->call($this->ver . '/paycode/get', $data);
    }

    /***
     *
     * 根据支付码查询支付结果
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function queryPayCode($data)
    {
        return $this->call($this->ver . '/paycode/query', $data);
    }

    /**
     * 获取支付宝的app_auth_token
     *
     * @param array $data 接口参数(目前不需要)
     *
     * @return array|mixed
     */
    public function getAlipayAuthToken($data = [])
    {
        return $this->call('internal/tool/getalipayauthtoken', $data);
    }

    /**
     * 口碑商品交易购买接口
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function koubeiUnifiedOrder($data)
    {
        return $this->call($this->ver . '/koubei/unifiedorder', $data);
    }

    /**
     * 口碑商品交易查询
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function koubeiQueryOrder($data)
    {
        return $this->call($this->ver . '/koubei/orderquery', $data);
    }

    /**
     * 口碑商品交易退货接口
     *
     * @param $data
     *
     * @return array|mixed
     */
    public function koubeiRefund($data)
    {
        return $this->call($this->ver . '/koubei/refund', $data);
    }
}