<?php

namespace Omnipay\YeePay;

class Helper
{
    public static function getPostField($assoc = [])
    {
        // invalid characters for "name" and "filename"
        $disallow = array("\0", "\"", "\r", "\n");

        $body = [];
        // build normal parameters
        foreach ($assoc as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode('\r\n', array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                '',
                filter_var($v), 
            ));
        }

        // generate safe boundary 
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));
    
        // add boundary for each parameters
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });
    
        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = "";

        $result = [
            'boundary' => $boundary,
            'postfields' => implode("\r\n", $body),
            'httpheader' => [
                "Expect" => "100-continue",
                "Content-Type" => "multipart/form-data; boundary={$boundary}",
            ]
        ];

        return $result;
    }
} 