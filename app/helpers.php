<?php
    
    function encrypt($value) {
        $key = 'encrypter key';
        $method = "AES-128-ECB";
        $iv = null;
        $options = 0;
        return openssl_encrypt ($value, $method, $key); 
    }
   
    function decrypt($value) {
        $key = 'encrypter key';
        $method = "AES-128-ECB";
        $iv = null;
        $options = 0;
        return openssl_decrypt($value, $method, $key, $options, $iv); 
    }
 
