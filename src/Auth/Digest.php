<?php
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Authentication digest]
 */
class Digest {
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

    public function decode() {
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

        $user = $this->userMdl->asObject()->where($this->COLOUMN_USERNAME, $data['username'])->get();
        if (!$user || empty($user)) {
            return $this->failerror();
        }
        $username = $user->{$this->COLOUMN_USERNAME};
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response = md5($username.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        if ($data['response'] != $valid_response) {
             return $this->failerror();
        }
        return $user;
    }
}