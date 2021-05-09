<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication Token JWT]
 */

namespace Appkita\CI4Restful\Authentication;
use \Firebase\JWT\JWT;
use \Config\Services;
use \Appkita\CI4Restful;
use \CodeIgniter\API\ResponseTrait;
class Token
{
    private $JWT_KEY = '1klso1LMWnLKLQPzIksx#3';
    private $JWT_USERNAME = 'email';
    private $COLOUMN_USERNAME = 'email';
    private $JWT_TIMEOUT = 3600;
    private $JWT_ISS = 'https://app-kita.com';
    private $JWT_AUD = 'https://app-kita.net';
    private $mdl = null;

    function __construct() {
        $config = Config('Restfull');
        $cfg = $config->jwt;
        $this->JWT_KEY = $config['JWT_KEY'];
        $this->JWT_USERNAME = $config['JWT_USERNAME'];
        $this->JWT_TIMEOUT = $config['JWT_TIMEOUT'];
        $this->JWT_ISS = $config['JWT_ISS'];
        $this->JWT_AUD = $config['JWT_AUD'];
        $this->mdl = Cek();
    }

    public function decode(string $path, string $ip) {
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
            return $this->failUnauthorized('Not Authroization');
        }
        if (!isset($decodedToken->{$this->JWT_USERNAME})) {
             return $this->failUnauthorized('Not Authroization');
        }
        $get = $this->mdl->username($decodedToken->{$this->JWT_USERNAME});
        if ($get) {
          return $this->failUnauthorized('Not Authroization');
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
            $this->JWT_USERNAME => $data
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