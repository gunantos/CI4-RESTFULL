<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication basic]
 */

class Basic{
    
    private $JWT_USERNAME = 'email';
    private $COLOUMN_USERNAME = 'email';
    private $COLOUMN_PASSWORD = 'password';
    private $userMdl= null;

    public function init(object $config) {
        foreach($config as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
    }

    public function decode() {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        if (!$has_supplied_credentials) {
            return false;
        }
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
            $user = $this->userMdl->asObject()->where($this->COLOUMN_USERNAME, $_SERVER['PHP_AUTH_USER'])->get();
            if (!$user) {
                return $this->failerror();
            }
            if (password_verify($_SERVER['PHP_AUTH_PW'], $user->{$this->COLOUMN_PASSWORD})) {
                return $user;
            } else {
                return $this->failerror();
            }
    }
}