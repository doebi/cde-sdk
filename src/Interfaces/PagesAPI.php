<?php

namespace Ixolit\CDE\Interfaces;

use Ixolit\CDE\Exceptions\MetadataNotAvailableException;
use Ixolit\CDE\WorkingObjects\Page;

/**
 * This API gives access to the pages functionality of the CDE (pages, languages, metadata, etc)
 */
interface PagesAPI {

	/**
	 * Returns the content of the page in the sitelayout
	 *
	 * @return string
	 */
	public function getContent();

	/**
	 * Get a page specified by path, virtual host, language and layout
	 *
	 * @param string|null $vhost
	 * @param string|null $lang
	 * @param string|null $layout
	 * @param string|null $scheme
	 *
	 * @return Page
	 */
	public function getPage($path, $vhost = null, $lang = null, $layout = null, $scheme = null);

	/**
	 * Get a list of all pages.
	 *
	 * @param string|null $vhost
	 * @param string|null $lang
	 * @param string|null $layout
	 * @param string|null $scheme
	 *
	 * @return Page[]
	 */
	public function getAll($vhost = null, $lang = null, $layout = null, $scheme = null);

	/**
	 * Returns a list of language codes supported on the current vhost. This is set up in vhost.ini.
	 *
	 * @return string[]
	 */
	public function getLanguages();

	/**
	 * Returns a breadcrumb to the given page page. Defaults to the current page, language and layout. Calling this
	 * function is only valid in the context of a page. For error pages, etc. this function returns an empty array.
	 *
	 * @param string|null $page
	 * @param string|null $lang
	 * @param string|null $layout
	 *
	 * @return \Ixolit\CDE\WorkingObjects\BreadcrumbEntry[]
	 */
	public function getBreadcrumb($page = null, $lang = null, $layout = null);

	/**
	 * @param string $meta
	 * @param string|null $lang
	 * @param string|null $pagePath
	 * @param string|null $layout
	 *
	 * @return string
	 *
	 * @throws MetadataNotAvailableException
	 */
	public function getMetadata($meta, $lang = null, $pagePath = null, $layout = null);

	/**
	 * @param string|null $lang
	 * @param string|null $pagePath
	 * @param string|null $layout
	 *
	 * @return string[]
	 *
	 * @throws MetadataNotAvailableException
	 */
	public function getAllMetadata($lang = null, $pagePath = null, $layout = null);

	/**
	 * Returns info about an active preview session if available otherwise null.
	 *
	 * @return mixed
	 */
	public function	getPreviewInfo();
}