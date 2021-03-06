<?php

namespace Ixolit\CDE\Controller;

use Ixolit\CDE\Auth\AuthenticationRequiredException;
use Ixolit\CDE\Exceptions\ControllerSkipViewException;
use Ixolit\CDE\Exceptions\InformationNotAvailableInContextException;
use Ixolit\CDE\Interfaces\FilesystemAPI;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\Interfaces\ResponseAPI;
use Ixolit\CDE\PSR7\Response;
use Ixolit\CDE\WorkingObjects\ViewModel;
use Psr\Http\Message\ResponseInterface;

class ControllerLogic {
	/**
	 * @var RequestAPI
	 */
	private $requestApi;

	/**
	 * @var ResponseAPI
	 */
	private $responseApi;

	/**
	 * @var FilesystemAPI
	 */
	private $fsApi;

	public function __construct(RequestAPI $requestApi, ResponseAPI $responseApi, FilesystemAPI $fsApi) {
		$this->requestApi  = $requestApi;
		$this->responseApi = $responseApi;
		$this->fsApi = $fsApi;
	}

	public function execute() {
		global $view;

		try {
			$path = defined('VHOSTS_DIR') ? VHOSTS_DIR : '/vhosts/';
			$path .= urlencode($this->requestApi->getEffectiveVhost());
			$path .= '/layouts/';
			$path .= $this->requestApi->getLayout()->getName();
			$path .= '/pages';
			$path .= rtrim($this->requestApi->getPagePath(), '/');
			$path .= '/controller.php';

			$viewData = [];
			if ($this->fsApi->exists($path)) {
				try {
					$controllerData = include($path);
				} catch (ControllerSkipViewException $e) {
					exit;
				}
				if ($controllerData instanceof ResponseInterface) {
					$this->responseApi->sendPSR7($controllerData);
					exit;
				}
				if (!empty($controllerData) && is_array($controllerData)) {
                    foreach ($controllerData as $key => $value) {
                        $viewData[$key] = $value;
                    }
                }
			}
			$view = new ViewModel($viewData);
		} catch (AuthenticationRequiredException $e) {
			if ($loginPage = getMeta('loginPage')) {
				$currentUri = $this->requestApi->getPSR7()->getUri();
				$newUri     = $currentUri
					->withPath('/' . $this->requestApi->getLanguage() . $loginPage)
					->withQuery('backurl=' . \urlencode($currentUri->getPath()));
				$this->responseApi->sendPSR7(new Response(302, [
					'Location' => [(string)$newUri]
				], '', '1.1'));
			} else {
				throw $e;
			}
		} catch (InformationNotAvailableInContextException $e) {
			// We are not in a page, no controller logic.
			return;
		} catch (\Exception $e) {
			if (\function_exists('previewInfo') && previewInfo()) {
				include(__DIR__ . '/errorpage.php');
				exit;
			} else {
				throw $e;
			}
		}
	}
}