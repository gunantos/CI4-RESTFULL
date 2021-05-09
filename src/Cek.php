<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Library CI4 untuk pembuatan WEB API Restfull dilengkapi dengan Authentication type JWT, KEY, Basic, Digest yang bisa terintegrasi dengan database, memiliki fitur blacklist, whitelist, management api]
 */

namespace Appkita\CI4Restful;

use \Codeigniter\Model;
use \Config\Services;

class Cek {  
    private static $mdl = null;

    function __construct() {
		$config = config('Restfull');
        if (\strtolower(str_replace(' ', '', $config->auth_cek)) == 'database') {
            $this->mdl = new Database($config->auth_cek_db);
        } else {
            $this->mdl = new File($config->auth_cek_file);
        }
    }
    
    public function getUser() {
        return $this->user;
    }

    public function key(string $key, string $path, string $ip) {
        $user = $this->mdl->key($key, $path, $ip);
        if (!$user) {
            return false;
        }
        return $user;
    }

    public function username(string $user, string $pass, string $path, string $ip) {
        $user = $this->mdl->username($key, $path, $ip);
        if (!$user) {
            return false;
        }
        return $user;
    }
}