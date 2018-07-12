<?php

namespace App\Models;

use App\Models\Sources\HackerNews;
use App\Models\Sources\Reddit;
use App\Models\Sources\Scraper;
use App\Models\Sources\YouTube;
use Illuminate\Database\Eloquent\Model;

class Source extends Model {

	public function getSourcesObsession($obsession) {

		$sourceIds = SourceObsession::where('obsession_id', $obsession['id'])->pluck('source_id')->toArray();
		$sources = self::whereIn('id', $sourceIds)->get()->toArray();
		return $sources;
	}

	public function addDataPerSource($source) {

		switch($source['type']) {

			case 'youtube':
				$hnModel = new YouTube();
				$hnModel->addNewContent($source['source_key'], $source['subtype']);
				break;

			case 'hackernews':
				$hnModel = new HackerNews();
				$hnModel->addNewContent($source['source_key']);
				break;

			case 'reddit':
				$redditModel = new Reddit();
				$redditModel->addNewContent($source['source_key']);
				break;

			case 'scraper':
				$scraperModel = new Scraper();
				$scraperModel->addNewContent($source['source_key']);
				break;
		}

	}

}