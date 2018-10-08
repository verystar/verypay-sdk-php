<?php
namespace Verypay\Lib;

/**
 * Defines a few helper methods.
 *
 * @author fifsky <caixudong@verystar.cn>
 */
class Helper
{
    /**
     *
     * 检测一个字符串否为Json字符串
     *
     * @param string $string
     *
     * @return true/false
     *
     */
    public static function isJson($string)
    {
        if (strpos($string, "{") !== false) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } else {
            return false;
        }
    }

    /**
     * 除去数组中的空值和签名参数
     *
     * @param array $para 签名参数组
     *
     * @return array
     */
    public static function paraFilter($para)
    {
        $para_filter = array();
        while (list ($key, $val) = each($para)) {
            if (strtolower(trim($key)) === "sign" || trim($val) === "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }

    /**
     * 对数组排序
     *
     * @param array $para 排序前的数组
     *
     * @return array
     */
    public static function argSort($para)
    {
        ksort($para, SORT_STRING);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     *
     * @param array $para 需要拼接的数组
     *
     * @return string
     */
    public static function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, strlen($arg) - 1);
        return $arg;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     *
     * @param array $para 需要拼接的数组
     *
     * @return string
     */
    public static function createLinkstringUrlencode($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . rawurlencode($val) . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, strlen($arg) - 1);
        return $arg;
    }

    /**
     * 转化方法 很重要
     *
     * @param object $object
     *
     * @return mixed
     */
    public static function object2array($object)
    {
        //return @json_decode(@json_encode($object), 1);
        return @json_decode(preg_replace('/{}/', '""', @json_encode($object)), 1);
    }


    /**
     * array转xml
     *
     * @param array $arr
     *
     * @return string
     */
    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     *
     * @param string $xml
     *
     * @return array
     */
    public static function xmlToArray($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 随机生成16位字符串
     *
     * @param int $length
     *
     * @return string 生成的字符串
     */
    public static function getRandomStr($length = 16)
    {
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwzxyABCDEFGHIJKLMNOPQRSTUVWZXY'), 0, $length);
    }
}
