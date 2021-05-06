<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication Token JWT]
 */
use Firebase\JWT\JWT;
use Config\Services;

class Token
{
    private $JWT_KEY = '1klso1LMWnLKLQPzIksx#3';
    private $JWT_USERNAME = 'email';
    private $COLOUMN_USERNAME = 'email';
    private $JWT_TIMEOUT = 3600;
    private $JWT_ISS = 'https://app-kita.com';
    private $JWT_AUD = 'https://app-kita.net';
    private $TOKEN_USERNAME = 'email';
    private $userMdl= null;

    public function init(object $config) {
        foreach($config as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
    }

    public function decode() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return false;
        }
        $decodedToken = $matches[1];
        if (! $decodedToken) {
            return false;
        }
        $decodedToken = JWT::decode($encodedToken, $this->JWT_KEY, ['HS256']);
        if (!$decodedToken) {
            return false;
        }
        if (!isset($decodedToken->iss) && !isset($decodedToken->aud)) {
            return false;
        }
        
        if ($decodedToken->iss != $this->JWT_ISS|| $decodedToken->aud != $this->JWT_AUD) {
            return false;
        }
        if (!isset($decodedToken->{$this->JWT_USERNAME})) {
             return $this->failerror();
        }
        
        $get = $this->userMdl->asObject()->where($this->COLOUMN_USERNAME, $decodedToken->{$this->JWT_USERNAME})->get();
        if ($get) {
            return $this->failerror();
        }
        return $get;
    }

    public function encode($data) : string {
        $issuedAtTime = time();
        $tokenTimeToLive = $this->JWT_TIMEOUT ?? 3600;
        $tokenExpiration = $issuedAtTime + $tokenTimeToLive;

        $payload = [
            'iss' => $this->JWT_ISS,
            'aud' => $this->JWT_AUD,
            'iat' => $issuedAtTime,
            'exp' => $tokenExpiration,
            $this->TOKEN_USERNAME => $data
        ];
        if (!empty($data)){
            if (\is_array($data)) {
                $payload = array_merge($payload, $data);
            } else {
                $payload['data'] = $data;
            }
        }
        $jwt = JWT::encode($payload, $this->JWT_KEY);
        return $jwt;
    }
}