<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication digest]
 */

namespace Appkita\CI4Restful\Authentication;

use \Config\Services;
use \Appkita\CI4Restful;
use \CodeIgniter\API\ResponseTrait;

class Digest {
    private $COLOUMN_USERNAME = 'email';
    private $COLOUMN_PASSWORD = 'password';
    private $userMdl= null;

    private function http_digest_parse($txt)
    {
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }

    public function decode(string $path, string $ip) {
        $config = Config('Restfull');
        $mdl = Cek();
        $realm = 'Restricted area';

        if (!isset($_SERVER['PHP_AUTH_DIGEST'])) {
            return false;
        }
        if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
            return false;
        }

        if ($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) {
            return false;
        }

        $user = $mdl->username($data['username'], '', $path, $ip);
        if (!$user) {
            return $this->failUnauthorized('Not Authroization');
        }
        $username = $user['username'];
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response = md5($username.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        if ($data['response'] != $valid_response) {
             return $this->failUnauthorized('Not Authroization');
        }
        return $user;
    }
}