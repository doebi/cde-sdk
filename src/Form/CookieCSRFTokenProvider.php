<?php

namespace Ixolit\CDE\Form;

use Ixolit\CDE\CDECookieCache;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;

class CookieCSRFTokenProvider implements CSRFTokenProvider {

	const COOKIE_NAME_CSRF_TOKEN = 'csrf-token';

	/**
	 * @var RequestAPI
	 */
	private $requestAPI;
	/**
	 * @var ResponseAPI
	 */
	private $responseAPI;

	public function __construct(
		RequestAPI $requestAPI,
		ResponseAPI $responseAPI
	) {
		$this->requestAPI = $requestAPI;
		$this->responseAPI = $responseAPI;

		CDECookieCache::getInstance()->write(self::COOKIE_NAME_CSRF_TOKEN, $this->getCSRFToken());
	}

	public function getCSRFToken() {
		$token = CDECookieCache::getInstance()->read(self::COOKIE_NAME_CSRF_TOKEN);

		if ($token === null) {
			$token = \md5(\mt_rand(0, PHP_INT_MAX));
		}

		return $token;
	}
}