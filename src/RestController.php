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

use CodeIgniter\API\ResponseTrait;

/**
 * An extendable controller to provide a RESTful API for a resource.
 */
class RestController extends Base
{
	use ResponseTrait;
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */

	function __construct() {
		parent::__construct();
		$this->init();
	}

	private function init() {
		$auth = new Auth((object)
			[
				'JWT_KEY' => $this->JWT_KEY,
				'JWT_AUD' => $this->JWT_AUD,
				'JWT_ISS' => $this->JWT_ISS,
				'JWT_TIMEOUT' => $this->JWT_TIMEOUT,
				'JWT_USERNAME' => $this->JWT_USERNAME,
				'COLOUMN_USERNAME' => $this->COLOUMN_USERNAME,
				'COLOUMN_PASSWORD' => $this->COLOUMN_PASSWORD,
				'COLOUMN_KEY' => $this->COLOUMN_KEY,
				'API_KEY' => $this->API_KEY,
				'userMdl' => $this->userMdl
			]
		);
	}

	protected function setAuth($auth) {
		$this->auth = $auth;
	}

	protected function getAuth() {
		return $this->auth;
	}

	public function index()
	{
		return $this->fail(lang('RESTful.notImplemented', ['index']), 501);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		return $this->fail(lang('RESTful.notImplemented', ['show']), 501);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		return $this->fail(lang('RESTful.notImplemented', ['new']), 501);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		return $this->fail(lang('RESTful.notImplemented', ['create']), 501);
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		return $this->fail(lang('RESTful.notImplemented', ['edit']), 501);
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		return $this->fail(lang('RESTful.notImplemented', ['update']), 501);
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @param mixed $id
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		return $this->fail(lang('RESTful.notImplemented', ['delete']), 501);
	}
}