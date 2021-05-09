<?php

namespace Appkita\CI4Restful\User;

class File extends \User {
    private $mdl = [];
    private $user = [];

    function __construct(Array $model) {
        $this->mdl = $mdl;
    }

    private function clear(string $val, $lower=true) {
        $val = str_replace(' ', '', $val);
        return ($lower) ? \strtolower($val) : $val;
    }

    private function cekPath(string $path) {
        if ($this->user['path'] == '*') {
            return true;
        } else if ($this->clear($this->user['path']) == $this->clear($path)) {
            return true;
        } else {
            return false;
        }
    }

    private function getDB(string $key, string $value, string $path, string $ip) {
        $indek = array_search($value, array_column($this->model, $key));
        if ($indek >= 0) {
            $this->user = $this->model[$indek];
            if (!$this->cekPath($path)) {
                return false;
            }
            if (!$this->whitelist($ip)) {
                if (!$this->blacklist($ip)) {
                    return false;
                }
            }
            return $this->user;
        }
        return false;
    }
    
    public function key(string $key, string $path, string $ip) {
        return $this->getDB('key', $key, $path, $ip);
    }

    public function username(string $user, string $pass = '', string $path, string $ip) {
        if ($this->getDB('username', $key, $path, $ip)) {
            if (!empty($pass)) {
                if ($this->user['password'] != $pass) {
                    return false;
                }
            }
            return $this;
        } else {
            return false;
        }
    }

    public function blacklist(string $ip) {
        if (\sizeof($this->user) < 1) {
           throw new Exception('Please cek user');
        }
        if (!isset($this->user['blacklist'])) {
            throw new Exception('Database error config');
        }
        if (\in_array($ip, $this->user['blacklist'])) {
            return false;
        } else {
            return true;
        }
    }

    public function whitelist(string $ip) {
        if (\sizeof($this->user) < 1) {
           throw new Exception('Please cek user');
        }
        if (!isset($this->user['whitelist'])) {
            throw new Exception('Database error config');
        }
        if (\in_array($ip, $this->user['whitelist'])) {
            return true;
        } else {
            return false;
        }
    }
}