<?php

namespace Ixolit\CDE;
use Ixolit\CDE\WorkingObjects\CDEMailAPI;

/**
 * This class gives static access to the default API implementations.
 */
class CDE {
	private function __construct() {}

	/**
	 * @return Interfaces\KVSAPI
	 */
	public static function getKVSAPI() {
		return new CDEKVSAPI();
	}

	/**
	 * @return Interfaces\MetaAPI
	 */
	public static function getMetaAPI() {
		return new CDEMetaAPI();
	}

	/**
	 * @return Interfaces\PagesAPI
	 */
	public static function getPagesAPI() {
		return new CDEPagesAPI();
	}

	/**
	 * @return Interfaces\ResourceAPI
	 */
	public static function getResourceAPI() {
		return new CDEResourceAPI();
	}

	/**
	 * @return Interfaces\RequestAPI
	 */
	public static function getRequestAPI() {
		return new CDERequestAPI();
	}

	/**
	 * @return Interfaces\ResponseAPI
	 */
	public static function getResponseAPI() {
		return new CDEResponseAPI();
	}

	/**
	 * @return Interfaces\GeoLookupAPI
	 */
	public static function getGeoAPI() {
		return new CDEGeoLookupAPI();
	}

	/**
	 * @return Interfaces\MailAPI
	 */
	public static function getMailAPI() {
		return new CDEMailAPI();
	}

	/**
	 * @return Interfaces\FilesystemAPI
	 */
	public static function getFilesystemAPI() {
		return new CDEFilesystemAPI();
	}

	/**
	 * @return Interfaces\SitemapRenderer
	 */
	public static function getSitemapRenderer() {
		return new SitemapRenderer(
			self::getPagesAPI(),
			self::getRequestAPI()
		);
	}
}
