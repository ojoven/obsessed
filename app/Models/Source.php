<?php

namespace App\Models;

interface Source {

	public function addNewPosts($sourceId);

	public function addNewComments($sourceId);

}