<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obsession extends Model {

	public function addDataObsessions() {

		$sourceModel = new Source();

		// Get obsessions
		$obsessions = $this->getObsessions();

		// For each obsession
		foreach ($obsessions as $obsession) {

			// Get sources
			$sources = $sourceModel->getSourcesObsession($obsession);

			// For each source, add new posts
			foreach ($sources as $source) {

				$sourceModel->addDataPerSource($source);
			}

		}

	}

	public function getObsessions() {

		$obsessions = self::get()->toArray();
		return $obsessions;
	}

}