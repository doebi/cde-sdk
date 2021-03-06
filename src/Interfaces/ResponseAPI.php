<?php

namespace Ixolit\CDE\Interfaces;

use Ixolit\CDE\Exceptions\CookieSetFailedException;
use Ixolit\CDE\Exceptions\HeaderSetFailedException;
use Ixolit\CDE\Exceptions\InvalidStatusCodeException;
use Psr\Http\Message\ResponseInterface;

interface ResponseAPI {
	/**
	 * Sends out a PSR-7 response.
	 *
	 * @param ResponseInterface $response
	 */
	public function sendPSR7(ResponseInterface $response);

	/**
	 * Performs a permanent or temporary redirect to the location specified via $location. Performs a permanent or
	 * temporary redirect to the location specified via $location.
	 *
	 * This function is only supported within pages. Templates residing within the files/ or static/ folders should
	 * not call this function.
	 *
	 * @param string $location
	 * @param bool   $permanent specifies if the redirect should be permanent (Status Code 301 - "Moved Permanently"),
	 *                          or temporary (Status Code 302 - "Found").
	 */
	public function redirectTo($location, $permanent = false);

	/**
	 * Performs a permanent or temporary redirect to the page path specified via $page.
	 *
	 * This function is only supported within pages. Templates residing within the files/ or static/ folders should
	 * not call this function.
	 *
	 * @param string      $page           is a relative path to a local page on the site (e.g. "/about/legal").
	 * @param string|null $lang
	 * @param bool        $permanent      specifies if the redirect should be permanent (Status Code 301 - "Moved
	 *                                    Permanently"), or temporary (Status Code 302 - "Found").
	 * @param bool        $abortRendering abort the rendering after this call.
	 *
	 * @return
	 */
	public function redirectToPage($page, $lang = null, $permanent = false, $abortRendering = true);

	/**
	 * Sets the content type for the current response. Useful for sending out JSON.
	 *
	 * @param string $contentType
	 */
	public function setContentType($contentType);

	/**
	 * Set the HTTP response's status code.
	 *
	 * @param int $statusCode
	 *
	 * @throws InvalidStatusCodeException if the status code is invalid.
	 */
	public function setStatusCode($statusCode);

	/**
	 * Sets a HTTP cookie in the response.
	 *
	 * @param string $name
	 * @param string $value
	 * @param int    $maxAge in seconds. 0 means session cookie.
	 * @param string $path
	 * @param string $domain
	 * @param bool   $secure
	 * @param bool   $httponly
	 *
	 * @throws CookieSetFailedException
	 */
	public function setCookie($name, $value, $maxAge = 0, $path = null, $domain = null, $secure = false, $httponly = false);

	/**
	 * @param $name
	 * @param $value
	 *
	 * @throws HeaderSetFailedException
	 */
	public function setHeader($name, $value);
}
