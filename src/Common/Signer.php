<?php

namespace Omnipay\YeePay\Common;

use Omnipay\YeePay\Common\CryptAES;

class Signer 
{
    /**
      *
      * @将数组转换为JSON字符串（兼容中文）
      * @$array 要转换的数组
      * @return string 转换得到的json字符串
      *
      */
    public static function cn_json_encode($array) {
        $array = self::cn_url_encode($array);
        $json = json_encode($array);
        return urldecode($json);
    }

    /**
      *
      * @将数组统一进行urlencode（兼容中文）
      * @$array 要转换的数组
      * @return array 转换后的数组
      *
      */
    private static function cn_url_encode($array) {
        self::arrayRecursive($array, "urlencode", true);
        return $array;
    }
    
    /**
      * @使用特定function对数组中所有元素做处理
      * @&$array 要处理的字符串
      * @$function 要执行的函数
      * @$apply_to_keys_also 是否也应用到key上
      *
      */
    private static function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
    }

    /**
      * @生成hmac签名
      * @$data 明文数组或者字符串
      * @$key 密钥
      * @return string
      *
     */
    public static function signHmac($hmacdata, $key) {
        
        if ( empty($hmacdata) ) {
        
            return null;    
        }
        
        if ( !$key || empty($key) ) {
            
            return null;
        }
 
        foreach ($hmacdata as $k=>$v) {
            $hmacdata[$k] = $v ? $v : '';
        }
        
        if ( is_array($hmacdata) ) {
        
            $data = implode("", $hmacdata);
        } else {
        
            $data = strval($hmacdata); 
        }

        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            
            $key = pack("H*",md5($key));
        }
        
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }

    /**
      * @取得aes加密
      * @$dataArray 明文字符串
      * @$key 密钥
      * @return string
      *
     */
    public static function getAes($data, $aesKey) {
        $aes = new CryptAES();
        $aes->set_key($aesKey);
        $aes->require_pkcs5();
        
        $encrypted = strtoupper($aes->encrypt($data));

        return $encrypted;

    }

    /**
      * @取得aes解密
      * @$dataArray 密文字符串
      * @$key 密钥
      * @return string
      *
     */
    public static function getDeAes($data, $aesKey) {

        $aes = new CryptAES();
        $aes->set_key($aesKey);
        $aes->require_pkcs5();
        $text = $aes->decrypt($data);

        return $text;
    }

}
