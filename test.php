<?php

use PHPUnit\Framework\TestCase;
use Rhio\Rhio;

final class RhioTest extends TestCase
{
    public function testCanExecuteGetRequest()
    {
        $rhio = new Rhio();
        $result = $rhio->get("http://postman-echo.com");
        
        $this->assertEquals('200', $result['http_code']);
    }

    public function testCanExecuteGetRequestWithParameters()
    {
        $rhio = new Rhio();
        $data = array(
            'q' => 'blobfish'
        );
        $result = $rhio->get("http://postman-echo.com/get", $data);
        $json = json_decode($result['body']);
        
        $this->assertEquals('200', $result['http_code']);
        $this->assertEquals('blobfish', $json->args->q);
    }

    public function testCanExecuteGetRequestWithCustomHeader() {
        $rhio = new Rhio(True);
        $header_key = 'test-header-key';
        $header_value = '123456789';
        $header = $header_key.': '.$header_value;
        $rhio->set_header($header);
        $result = $rhio->get("https://postman-echo.com/headers");
        $json = json_decode($result['body']);

        $this->assertEquals('200', $result['http_code']);
        $this->assertEquals($header_value, $json->headers->$header_key);
    }

    public function testCanExecutePostRequest()
    {
        $rhio = new Rhio(True);
        $result = $rhio->post("http://postman-echo.com/post", array('foo' => 'bar'));
        $json = json_decode($result['body']);

        $this->assertEquals('200', $result['http_code']);
        $this->assertEquals('bar', $json->form->foo);
    }

    public function testCanExecutePutRequest()
    {
        $rhio = new Rhio(True);
        $result = $rhio->put("http://postman-echo.com/put", array('foo' => 'bar'));
        $json = json_decode($result['body']);
        
        $this->assertEquals('200', $result['http_code']);
        $this->assertEquals('bar', $json->form->foo);
    }

    public function testCanExecuteDeleteRequest()
    {
        $rhio = new Rhio(True);
        $result = $rhio->delete("http://postman-echo.com/delete", array('foo' => 'bar'));
        $json = json_decode($result['body']);
        
        $this->assertEquals('200', $result['http_code']);
        $this->assertEquals('bar', $json->form->foo);
    }

    public function testCanExecuteGetWithAuth()
    {
        $rhio = new Rhio();
        $result = $rhio->get("http://postman:password@postman-echo.com/digest-auth");

        $this->assertEquals('200', $result['http_code']);
    }
}