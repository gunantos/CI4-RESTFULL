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

class Userapi {

    private static $config_type = 'file';
    private static $config_parameter = [];
    private static $model = null;
    private static $modelName = null;
    private static $config_user = [];

    public static function getParameter(String $key = '') {
        if (empty($key)) return Self::$config_parameter;
        if (isset($parameter[$key])) {
            return $parameter[$key];
        } else {
            return '';
        }
    }

    public static function initialize($config, Array $parameter = [], Array $userlist = []) {
        if (\is_array($config)) {
            if (isset($config['parameter'])) {
                Userapi::$config_parameter = $config['parameter'];
            }
            if (isset($config['type'])) {
                Userapi::$config_type = $config['type'];
            }
            if (isset($config['userlist'])) {
                Userapi::$config_user = $config['userlist'];
            }
        } else {
            Self::$config_type = $config;
            Self::$config_parameter = $parameter;
            Self::$config_user = $userlist;
        }
        if (strtolower(self::$config_type)=='database') {
            self::setMdl(self::getParameter('model'));
        }
        return self;
    }

    private static function setMdl($which) {
        if ($which) {
            Self::$model     = is_object($which) ? $which : null;
            Self::$modelName = is_object($which) ? null : $which;
        }
        
        if (empty(Self::$model) && ! empty(Self::$modelName))
        {
            if (class_exists(Self::$modelName))
            {
                Self::$model = model(Self::$modelName);
            }
        }

        if (! empty(Self::$model) && empty(Self::$modelName))
        {
            Self::$modelName = get_class(Self::$model);
        }
        return self;
    }
    
    public static function cekKey(string $key) {
        if (self::$config_type == 'file') {
            return self::key_file($key);
        } else {
            return self::key_database($key);
        }
    }

    private static function key_database(string $key) : Array {
        if (empty(self::$model)) {
            throw new Exception('Please select database model user');
        }
        
    }


    private static function file() {

    }

    private static function database() {

    }
}