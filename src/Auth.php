<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Library CI4 untuk pembuatan WEB API Restfull dilengkapi dengan Authentication type JWT, KEY, Basic, Digest yang bisa terintegrasi dengan database, memiliki fitur blacklist, whitelist, management api]
 */

namespace Appkita\CIRestful;
class Auth {
    private $allow_auth = [];
    private $path = '';
    private $ip = '';
    function __construct($config = ['allow_auth'=>'', 'path'=>'', 'ip'=>'']) {
        $this->allow_auth = $config['allow_auth'];
        $this->path = $config['path'];
        $this->ip = $config['ip'];
    }

    private function ceklogin($class) {
        $class = strtolower($class);
        $class = ucwords($class);
        if (!class_exists($class)) {
            set_error_handler(function ($servirity, $class) {
                 throw new \ErrorException('Class not found or '. $class, 0, $servirity, __DIR__.DIRECTORY_SEPARATOR.'Auth.php', 22);
            });
        }
        $cls = new $class();
        $user = $cls->decode($this->path, $this->ip);
        if (!$user) {
            return false;
        }
        $this->user = $user;
    }

    public function cek() {
        $auth = $this->allow_auth;
        if (!$auth || empty($auth) || (is_array($auth) && sizeof($auth) < 1)) return true;
        if (is_array($auth)) {
            $user = null;
            for($i = 0; $i < sizeof($auth); $i++) {
                if ($user = $this->ceklogin($auth[$i])) {
                    return $user;
                    break;
                }
            }
            if (!empty($user)) {
                return $user;
            }else {
                return false;
            }
        } else {
            if (!empty(auth)){
                if ($user = $this->ceklogin($auth)) {
                    return $user;
                } else {
                    return false;
                }
            }
        }
    }
}