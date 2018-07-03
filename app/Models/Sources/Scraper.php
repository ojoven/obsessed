<?php

namespace App\Models\Sources;

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


	}

	public function addNewComments($sourceId) {
		
	}

}