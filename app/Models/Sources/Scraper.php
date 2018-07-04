<?php

namespace App\Models\Sources;

use App\Lib\SimpleHtmlDom;
use App\Models\SourceInterface;

class Scraper implements SourceInterface {

	protected $pathToConfigFiles = '/Models/Sources/scrapers/';
	protected $pathToParserFiles = '/Models/Sources/parsers/';

	protected $page;
	protected $sourceKey;

	public function addNewContent($sourceKey) {
		
		$this->sourceKey = $sourceKey;

		$this->addNewPosts($sourceKey);
		$this->addNewComments($sourceKey);

	}

	public function addNewPosts($sourceKey) {

		// Load configuration file
		$pathToConfigFile = app_path() . $this->pathToConfigFiles . $sourceKey . '.php';
		if (!file_exists($pathToConfigFile)) return false;
		$config = include($pathToConfigFile);

		// Load parser file (if it exists)
		$pathToParserFile = app_path() . $this->pathToParserFiles . $sourceKey . '.php';
		if (file_exists($pathToParserFile)) {
			require_once $pathToParserFile;
		}

		$this->page = 1;

		$loop = true;
		while ($loop) {

			$html = $this->getHtmlDom($config);
			if (!$html) break;

			foreach ($html->find($config['parameters']['postInList']) as $postDom) {

				foreach ($config['fieldsPost'] as $fieldName => $fieldAttributes) {

					if ($fieldAttributes['available'] && isset($fieldAttributes['pathList'])) {
						$position = isset($fieldAttributes['position']) ? $fieldAttributes['position'] : 0;
						$post[$fieldName] = SimpleHtmlDom::find($postDom, $fieldAttributes['pathList'], $position, $fieldAttributes['attribute']);
						if (isset($fieldAttributes['parse']) && $fieldAttributes['parse']) {
							// Call to parser function
							$functionName = $sourceKey . '_' . $fieldName;
							if (function_exists($functionName)) {
								$post[$fieldName] = $functionName($post[$fieldName]);
							}
						}
					}

				}

				$post['source_type'] = 'scraper';
				$post['source_key'] = $sourceKey;
			}

			$this->page++;
			$loop = false;

		}

	}

	public function getHtmlDom($config) {

		$url = $this->getUrl($config['parameters']['url'], $this->page);
		$html = file_get_contents($url);

		if (isset($config['parameters']['parseHTML'])
			&& $config['parameters']['parseHTML']) {

			$functionName = $this->sourceKey . '_html';
			if (function_exists($functionName)) {
				$html = $functionName($html);
			}

		}

		$htmlDom = SimpleHtmlDom::strGetHtml($html);

		return $htmlDom;
	}

	public function getUrl($url, $page) {

		$url = str_replace('[page]', $page, $url);
		return $url;
	}

	public function addNewComments($sourceKey) {
		
	}

}