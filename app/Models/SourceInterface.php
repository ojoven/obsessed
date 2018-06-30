<?php

namespace App\Models;

interface SourceInterface {

	public function addNewPosts($sourceId);

	public function addNewComments($sourceId);

}