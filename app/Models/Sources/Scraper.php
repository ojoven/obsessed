<?php

namespace App\Models\Sources;

use App\Lib\SimpleHtmlDom;
use App\Models\SourceInterface;

class Scraper implements SourceInterface {

	protected $pathToConfigFiles = '/Models/Sources/scrapers/';

	public function addNewContent($sourceId) {

		$this->addNewPosts($sourceId);
		$this->addNewComments($sourceId);

	}

	public function addNewPosts($sourceId) {

		$pathToConfigFile = app_path() . $this->pathToConfigFiles . $sourceId . '.php';
		if (!file_exists($pathToConfigFile)) return false;

		$config = include($pathToConfigFile);

		$page = 1;

		$loop = true;
		while ($loop) {

			$url = $this->getUrl($config['url'], $page);
			$html = SimpleHtmlDom::fileGetHtml($url);

			foreach ($html->find($config['postInList']) as $postDom) {

				foreach ($config['fields'] as $fieldName => $fieldAttributes) {

					if ($fieldAttributes['available'] && isset($fieldAttributes['pathList'])) {
						$position = isset($fieldAttributes['position']) ? $fieldAttributes['position'] : 0;
						$post[$fieldName] = SimpleHtmlDom::find($postDom, $fieldAttributes['pathList'], $position, $fieldAttributes['attribute']);
						if (isset($fieldAttributes['parse'])) {
							// Call to parser function
						}
					}

				}

				$post['source_type'] = 'scraper';
				$post['source_key'] = $sourceId;
			}

			$page++;
			$loop = false;

		}

	}

	public function getUrl($url, $page) {

		$url = str_replace('[page]', $page, $url);
		return $url;
	}

	public function addNewComments($sourceId) {
		
	}

}