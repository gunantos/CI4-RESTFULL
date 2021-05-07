<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication basic]
 */

namespace Appkita\CIRestful\Authentication;

use \Config\Services;
use \Appkita\CIRestful;
use \CodeIgniter\API\ResponseTrait;
class Basic{

    public function decode(string $path, string $ip) {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        if (!$has_supplied_credentials) {
            return false;
        }
        $mdl = Cek();
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $user = $mdl->username($username, $password, $path, $ip);
            if (!$user) {
                return $this->failUnauthorized('Not Authroization');
            } else {
                return $user;
            }
    }
}