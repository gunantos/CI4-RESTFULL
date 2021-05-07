<?php
use PHPUnit\Framework\TestCase;
use \Appkita\CIRestful;

class Expample extends \Appkita\CIRestful\RestController {
    protected $auth = 'key';
    public function index() {
        echo 'berhasil';
    }
}

class Testing extends TestCase
{
    public function test() {
        $cls = new Expample();
        return $cls->index();
    }
}