<?php

namespace Omnipay\Yeepay\Common;

class CryptAES
{
    protected $cipher     = MCRYPT_RIJNDAEL_128;
    protected $mode       = MCRYPT_MODE_ECB;
    protected $pad_method = NULL;
    protected $secret_key = '';
    protected $iv         = '';
    protected $blocksize  = 128;

    public function __construct(string $key)
    {
        $this->secret_key = $key;
    }
 
    public function set_cipher($cipher)
    {
        $this->cipher = $cipher; 
    }
 
    public function set_mode($mode)
    {
        $this->mode = $mode;
    }
 
    public function set_iv($iv)
    {
        $this->iv = $iv;
    }
 
    public function set_key($key)
    {
        $this->secret_key = $key;
        dd($this->secret_key);
    }
 
    public function require_pkcs5()
    {
        $this->pad_method = 'pkcs5';
    }
 
    protected function pad_or_unpad($str, $ext)
    {
        if ( is_null($this->pad_method) )
        {
            return $str;
        }
        else 
        {
            $func_name = __CLASS__ . '::' . $this->pad_method . '_' . $ext . 'pad';
            
            if ( is_callable($func_name) )
            {
                $size = mcrypt_get_block_size($this->cipher, $this->mode);
                return call_user_func($func_name, $str, $size);
            }
        }
 
        return $str; 
    }
 
    protected function pad($str)
    {
        return $this->pad_or_unpad($str, ''); 
    }
 
    protected function unpad($str)
    {
        return $this->pad_or_unpad($str, 'un'); 
    }
 
    public function encrypt($str)
    {
        $str = $this->pad($str);
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');
        
        if ( empty($this->iv) )
        {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
 
        mcrypt_generic_init($td, $this->secret_key, $iv);
        $cyper_text = mcrypt_generic($td, $str);
        $rt = bin2hex($cyper_text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
 
        return $rt;
    }
 
    public function decrypt($str) {
        $td = mcrypt_module_open($this->cipher, '', $this->mode, '');
 
        if ( empty($this->iv) )
        {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
 
        mcrypt_generic_init($td, $this->secret_key, $iv);
        $decrypted_text = mdecrypt_generic($td, self::hex2bin($str));
        $rt = $decrypted_text;
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
 
        return $this->unpad($rt);
    }
 
    public static function hex2bin($hexdata) {
        $bindata = '';
        $length = strlen($hexdata); 
        for ($i=0; $i < $length; $i += 2)
        {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    /**
     * pkcs#5 pad
     */
    public static function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    /**
     * pkcs#5 unpad
     */
    public static function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的密文
     */
    public function encrypt_openssl($text)
    {
        $text = self::pkcs5_pad($text,16);
        try {
            $ivlen = openssl_cipher_iv_length('aes-128-ecb');
            $iv = openssl_random_pseudo_bytes($ivlen);
            $encrypted = openssl_encrypt($text,'aes-128-ecb',$this->secret_key,OPENSSL_NO_PADDING,$iv);

            // while ($msg = openssl_error_string())
            // echo $msg . "<br />\n";
            // dd(openssl_error_string());
            if(false === $encrypted) {
                return false;
            }
            $rt = bin2hex($encrypted); 
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $rt;
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt_openssl($text)
    {
        // dd(self::hex2bin($encrypted));
        $ivlen = openssl_cipher_iv_length('aes-128-ecb');
        $iv = openssl_random_pseudo_bytes($ivlen);
        try {
            $decrypted = openssl_decrypt(
                self::hex2bin($text),
                'aes-128-ecb',
                $this->secret_key,
                OPENSSL_NO_PADDING,
                $iv
            );
            $str = self::pkcs5_unpad($decrypted);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        // return array(0, $xml_content); 
        return $str;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function getMode()
    {
        return 'aes-128-ecb';
    }

    public function encrypt_openssl_back($text)
    {
        // dd($text);
        // print_r($this->secret_key);exit;
        $text = self::pkcs5_pad($text,16);
        // dd($textt);
        try {
            // $l = strlen($this->secret_key);
            // if ($l < 16)
            //     $this->secret_key = str_repeat($this->secret_key, ceil(16/$l));
            // if ($m = mb_strlen($text)%16)
            //     $text .= str_repeat("\x00",  16 - $m);
            $ivlen = openssl_cipher_iv_length('aes-128-cbc');
            $iv = openssl_random_pseudo_bytes($ivlen);
            // $encrypted = openssl_encrypt($text,'aes-128-ecb',$this->secret_key,OPENSSL_NO_PADDING,$iv);
            // $encrypted = openssl_encrypt($text,'aes-256-cbc',$this->secret_key,OPENSSL_RAW_DATA,$iv);
            // $encrypted = openssl_encrypt($text,'aes-256-cbc',$this->secret_key,OPENSSL_NO_PADDING,$iv);
            $encrypted = openssl_encrypt($text,'aes-128-cbc',$this->secret_key,OPENSSL_NO_PADDING,$iv);

            while ($msg = openssl_error_string())
            echo $msg . "<br />\n";
            // exit;
            // dd(openssl_error_string());
            if(false === $encrypted) {
                return false;
            }
            $rt = bin2hex($encrypted); 
            $rt_base64 = base64_encode($rt); 

            echo $encrypted;
            echo '<br/>';
            echo $rt;            
            echo '<br/>';
            echo '==base64';
            echo $rt_base64;
            echo '<br/>';
        } catch (Exception $e) {
            return $e->getMessage();
        }
        exit;
        return $rt;
    }
}
