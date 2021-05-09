<?php
use PHPUnit\Framework\TestCase;
class Expample extends \Appkita\CI4Restful\Restfull {
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