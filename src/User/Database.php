<?php

namespace Appkita\Restful\User;
use \Codeigniter\Model;

class Database extends \User {
    private $config = null;
    private $mdl = null;
    private $user = [];

    function __construct(Array $config) {
        $this->config = $config;
        $this->mdl = model('\\'. APP_NAMESPACE . '\\Models\\'. $config['model']);
    }

    private function clear(string $val, $lower=true) {
        $val = str_replace(' ', '', $val);
        return ($lower) ? \strtolower($val) : $val;
    }

    private function cekPath(string $path) {
        if ($this->user[$this->config['path']] == '*') {
            return true;
        } else if ($this->clear($this->user[$this->config['path']]) == $this->clear($path)) {
            return true;
        } else {
            return false;
        }
    }

    private function getDB(string $key, string $value, string $path, string $ip) {
        $user = $this->mdl->asArray()->where($key, $value)->get();
        if (!$this->user) {
            return false;
        }
        $user[$this->config['blacklist']] = \explode(';', $user[$this->config['blacklist']]);
        $user[$this->config['whitelist']] = \explode(';', $user[$this->config['whitelist']]);
        $user[$this->config['path']] = \explode(';', $user[$this->config['path']]);
        
        $this->user = $user;
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
    
    public function key(string $key, string $path, string $ip) {
        return $this->getDB($this->config['key'], $key, $path, $ip);
    }

    public function username(string $user, string $pass = '', string $path, string $ip) {
        if ($this->getDB($this->config['username'], $key, $path, $ip)) {
            if (!empty($pass)) {
                $cek = \password_verify($this->user[$this->config['password']], $pass);
                if (!$cek) {
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
        if (!isset($this->user[$this->config['blacklist']])) {
            throw new Exception('Database error config');
        }
        if (\in_array($ip, $this->user[$this->config['blacklist']])) {
            return false;
        } else {
            return true;
        }
    }

    public function whitelist(string $ip) {
        if (\sizeof($this->user) < 1) {
           throw new Exception('Please cek user');
        }
        if (!isset($this->user[$this->config['whitelist']])) {
            throw new Exception('Database error config');
        }
        if (\in_array($ip, $this->user[$this->config['whitelist']])) {
            return true;
        } else {
            return false;
        }
    }
}