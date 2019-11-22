<?php

namespace Rhio;

use Exception;
use Rhio\Curl;

class Rhio
{
    public $curl;

    public function __construct($debug=False)
    {
        $this->curl = new Curl($debug);
    }

    public function set_header($header)
    {
        $this->curl->set_header($header);
    }

    public function get($url, $data=array(), $header=array())
    {
        $payload = '';
        $p_url = parse_url($url);

        if (isset($p_url['user']) && $p_url['user'] !== '' && isset($p_url['pass'])) {
            $this->curl->set_auth($p_url['user'], $p_url['pass']);
        }

        $this->curl->set_header('Host: '.$p_url['host']);

        if (count($data) > 0) {
            $payload = http_build_query($data);
        }

        if ($url[strlen($url - 1)] === '/') {
            $this->curl->set_url($url.'?'.$payload);
        } else {
            $this->curl->set_url($url.'/?'.$payload);
        }

        // set aditional header data if provided
        if (count($header) > 0) {
            foreach ($header as $h) {
                $this->curl->set_header($h);
            }
        }

        return $this->curl->exec();
    }

    public function post($url, $data, $header=array())
    {
        $payload = '';
        $p_url = parse_url($url);

		if (isset($p_url['user']) && $p_url['user'] !== '' && isset($p_url['pass'])) {
            $this->curl->set_auth($p_url['user'], $p_url['pass']);
        }

        $this->curl->set_url($url);
        $this->curl->set_header('Host: '.$p_url['host']);
        $this->curl->set_post($data);

        // set aditional header data if provided
        if (count($header) > 0) {
            foreach ($header as $h) {
                $this->curl->set_header($h);
            }
        }

        return $this->curl->exec();
    }

    public function put($url, $data, $header=array())
    {
        $payload = '';
        $p_url = parse_url($url);

		if (isset($p_url['user']) && $p_url['user'] !== '' && isset($p_url['pass'])) {
            $this->curl->set_auth($p_url['user'], $p_url['pass']);
        }

        $this->curl->set_url($url);
        $this->curl->set_header('Host: '.$p_url['host']);
        $this->curl->set_put($data);

        // set aditional header data if provided
        if (count($header) > 0) {
            foreach ($header as $h) {
                $this->curl->set_header($h);
            }
        }

        return $this->curl->exec();
    }

    public function delete($url, $data, $header=array())
    {
        $payload = '';
        $p_url = parse_url($url);

		if (isset($p_url['user']) && $p_url['user'] !== '' && isset($p_url['pass'])) {
            $this->curl->set_auth($p_url['user'], $p_url['pass']);
        }

        $this->curl->set_url($url);
        $this->curl->set_header('Host: '.$p_url['host']);
        $this->curl->set_delete($data);

        // set aditional header data if provided
        if (count($header) > 0) {
            foreach ($header as $h) {
                $this->curl->set_header($h);
            }
        }

        return $this->curl->exec();
    }
}
