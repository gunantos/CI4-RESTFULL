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

	abstract protected function setAuth($auth);
	abstract protected function getAuth();

	protected function getCekFrom() {
		return strtolower(str_replace(' ', '', $this->auth_cek !== 'database')) ? 'file' : 'database';
	}
	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		$this->cors();
		parent::initController($request, $response, $logger);
		$this->setModel($this->modelName);
		$this->getConfiguration();
		if ($https) {
			force_https();
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

	protected function setConfig(String $name, $value, Bool $array = false) {
		if (!empty($name)) {
			if (isset($this->{$name})) {
				if ($array) {
					if (is_string($value)) {
						$value = [$value];
					} else if (is_object($value)) {
						$value = (array) $value;
					}
				}
				$this->{$name} = $value;
			}
		}
		return $this;
	}

	private function getConfiguration() {
		$config = config('Restfull');
		foreach($config as $key => $value) {
			$array = false;
			if (strtolower($key) == 'auth_type_default') {
				$array = true;
				$key = 'auth';
			}
			$this->setConfig($key, $value, $array);
		}
	}

	protected function failerror($messages  = 'unauthorization', int $status = 401, string $code = null)
	{
		if (! is_array($messages))
		{
			$messages = ['error' => $messages];
		}

		$response = [
			'status'   => $status,
			'error'    => $code ?? $status,
			'messages' => $messages,
		];
		
		header('Content-Type: application/json', true, 404);
		http_response_code(404);
		die(json_encode($response));
		exit();
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