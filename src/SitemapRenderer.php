<?php

namespace Ixolit\CDE;

use Ixolit\CDE\Interfaces\PagesAPI;
use Ixolit\CDE\Interfaces\RequestAPI;
use Ixolit\CDE\WorkingObjects\Page;

class SitemapRenderer implements Interfaces\SitemapRenderer {
	/**
	 * @var PagesAPI
	 */
	private $pagesApi;

	/**
	 * @var CDERequestAPI
	 */
	private $requestApi;

	public function __construct(PagesAPI $pagesApi, RequestAPI $requestApi) {
		$this->pagesApi = $pagesApi;
		$this->requestApi = $requestApi;
	}

	/**
	 * Renders a sitemap.xml file for the given vhost. Always uses the default layout.
	 *
	 * @param null|string $vhost defaults to current effective vhost
	 * @param array       $languages defaults to all available if empty
	 *
	 * @return string
	 */
	function render($vhost = null, $languages = []) {
		$output = '';
		$output .= '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<urlset'.
			' xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'.
			' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' .
			' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' .
				' http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"' .
			'>';

		if (empty($languages)) {
			$languages = $this->pagesApi->getLanguages();
		}
		foreach ($languages as $lang) {
			$output .= $this->renderSitemapFragment($this->pagesApi->getAll($vhost, $lang, 'default'));
		}

		$output .= '</urlset>';

		return $output;
	}

	/**
	 * Renders an XML sitemap fragment.
	 *
	 * @param Page[] $pages
	 *
	 * @return string
	 *
	 * @internal
	 */
	function renderSitemapFragment($pages) {
		$output = '';
		foreach ($pages as $page) {
			$output  .= '<url>';
			$output  .= '<loc>' . \xml($page->getPageUrl()) . '</loc>';
			$output  .= '<changefreq>daily</changefreq>';
			$slashes  = \substr_count($page->getPageUrl(), '/');
			$priority = \round(1 - ($slashes - 2)/10, 2);
			$output  .= '<priority>' . \xml($priority) . '</priority>';
			$output  .= '</url>';
		}
		return $output;
	}
}
