<?php

namespace Rhio;

class Curl
{
    public $ch;
    private $header = array(
        "Accept-Charset: utf-8;q=0.7,*;q=0.7",
        "Keep-Alive: 60",
        "Connection: close"
    );

    public function __construct($debug=False)
    {
        $this->ch = curl_init();
       
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Rhio Client 1.0");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($this->ch, CURLOPT_MAXCONNECTS, 1);
        
        if ($debug) {
            curl_setopt($this->ch, CURLOPT_VERBOSE, 1);
            curl_setopt($this->ch, CURLOPT_STDERR, fopen('curl.log', 'w+'));
        }
    }

    public function set_header($data)
    {
        $this->header[] = $data;
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
    }

    public function set_url($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
    }

    public function set_post($fields)
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields);
    }

    public function set_put($fields)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    }

    public function set_delete($fields)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    }

    public function set_auth($user, $pass)
    {
        curl_setopt($this->ch, CURLOPT_USERPWD, $user.':'.$pass);
        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    }

    public function exec()
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);
        $result = array(
            'header' => '',
            'body' => '',
            'curl_error' => '',
            'http_code' => '',
            'last_url' => ''
        );
        
        if ($error !== "") {
            $result['curl_error'] = $error;
            return $result;
        }
       
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $result['header'] = substr($response, 0, $header_size);
        $result['body'] = substr($response, $header_size);
        $result['http_code'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $result['last_url'] = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
        
        return $result;
    }
}