<?php

namespace App\Models;

use App\Models\Sources\Reddit;
use Illuminate\Database\Eloquent\Model;

class Source extends Model {

	public function getSourcesObsession($obsession) {

		$sourceIds = SourceObsession::where('obsession_id', $obsession['id'])->pluck('source_id')->toArray();
		$sources = self::whereIn('id', $sourceIds)->get()->toArray();
		return $sources;
	}

	public function addDataPerSource($source) {

		switch($source['type']) {
			case 'reddit':
				$redditModel = new Reddit();
				$redditModel->addNewContent($source['source_key']);
		}

	}

}