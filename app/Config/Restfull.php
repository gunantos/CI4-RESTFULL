<?
/**
 * @author [Gunanto Simamora]
 * @website [https://app-kita.com]
 * @email [gunanto.simamora@gmail.com]
 * @create date 2021-05-06 12:55:49
 * @modify date 2021-05-06 12:55:49
 * @desc [Configuration RESTFULL API]
 */
namespace Config;

class Restfull extends BaseConfig
{
    /**
     * @var Array $auth_type
     * default: ['token', 'key', 'basic', 'digest']
     */
    public $auth_type_default = ['token', 'key', 'basic', 'digest'];

    /**
     * @var String $auth_cek_type = database / file
     */
    public $auth_cek = 'database';

    /**
     * @var Array $auth_cek_db
     * if you using database please follow your database user sistem
     */
    public $auth_cek_db = [
        'model' => 'UserMdl',
        'username'=>'email',
        'password'=>'password',
        'whitelist'=>'whitelist',
        'blacklist'=>'blacklist',
        'key'=>'key',
        'path'=>'*' //if you using multy path add ";" path and "_" after path end methode ex: user;token_create;token_index,data_index, data_show
    ];

    /**
     * @var Array $auth_cek_db
     * if you using file
     */
    public $auth_cek_file = [
        [
            'username'=>'user',
            'password'=>'pass',
            'whitelist'=>[],
            'blacklist'=>[],
            'key'=>'key',
            'path'=>'*' //if you using multy path create array value and add "_" after path and methode ex ['token', 'data_index', 'data_show']
        ]
        ];

    
    /**
     * @var Array JWT configuration
     */

     public $jwt = [
         'JWT_KEY' => 'lmLK123POPmnslzioXt75#', //secure your jwt with key
         'JWT_AUD' => 'https://app-kita.com',
         'JWT_ISS' => 'https://app-kita.net',
         'JWT_TIMEOUT' => 3600, // second
         'JWT_USERNAME' => 'username', //jwt username value insert in JWT data
     ];

     /**
      * @var string $key_header
      * character "-" and "_" will be same
      */
     public $key_header = 'X-API-KEY';

     /**
      * @var bool $force_https
      */
     public $https = true;
}