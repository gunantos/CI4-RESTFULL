<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication API KEY]
 */

namespace Appkita\CIRestful\Authentication;

use \Config\Services;
use \Appkita\CIRestful;
use \CodeIgniter\API\ResponseTrait;

class Key 
{

    public function decode(string $path, string $ip) {
        $mdl = Cek();
        $config = Config('Restfull');
        $API_KEY = $config->key_header;

        $keyname = str_replace(' ', '', $API_KEY);
        $keyname = strtoupper(str_replace('-', '_', $keyname));
        if (!isset($_SERVER['HTTP_'. $keyname])) {
            return false;
        }
        if (empty($_SERVER['HTTP_'. $keyname])) {
            return false;
        }
        $key = $_SERVER['HTTP_'. $keyname];
        $user = $mdl->key($key, $path, $ip);
        if ($user) {
            return $user;
        } else {
           return $this->failUnauthorized('Not Authroization');
        }
    }
}