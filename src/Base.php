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

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use Config\Services;
use Exception;
abstract class Base extends Controller
{
	/**
	 * @var string|null The model that holding this resource's data
	 */
	protected $modelName;

	/**
	 * @var object|null The model that holding this resource's data
	 */
	protected $model;

	protected $auth = ['TOKEN', 'KEY', 'BASIC', 'DIGEST'];
	protected $https = true;
	protected $format = 'json';

	protected $_methods= null;
	protected $_path = null;
	protected $_url = null;
	protected $_ip_address = null;

	protected function getClientIpAddress()
  {
      if (!empty($_SERVER['HTTP_CLIENT_IP']))   //Checking IP From Shared Internet
      {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      }
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //To Check IP is Pass From Proxy
      {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      else
      {
        $ip = $_SERVER['REMOTE_ADDR'];
      }

      return $ip;
  }
  	abstract protected function setAuth($auth);
	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		if ($https) {
			force_https();
		}
		$this->cors();
		parent::initController($request, $response, $logger);
		$this->setModel($this->modelName);
		
		$ipaddress = $this->getIPAddress();
		$class = $this->router->fetch_class();
		$function = $this->router->fetch_method();
		$path = $class.'_'.$function;
		$authentication = new Auth(
			[
				'allow_auth'=>$this->auth,
				'path'=>$path,
				'ip'=>$ipaddress
			]
		);
		if ($authentication->cek()){
			return $this->failUnauthorized('Not Authroization');
		}
	}

	private function cors() {
		 header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
	}
	/**
	 * Set or change the model this controller is bound to.
	 * Given either the name or the object, determine the other.
	 *
	 * @param object|string|null $which
	 *
	 * @return void
	 */
	public function setModel($which = null)
	{
		// save what we have been given
		if ($which)
		{
			$this->model     = is_object($which) ? $which : null;
			$this->modelName = is_object($which) ? null : $which;
		}

		// make a model object if needed
		if (empty($this->model) && ! empty($this->modelName))
		{
			if (class_exists($this->modelName))
			{
				$this->model = model($this->modelName);
			}
		}

		// determine model name if needed
		if (! empty($this->model) && empty($this->modelName))
		{
			$this->modelName = get_class($this->model);
		}
	}
}