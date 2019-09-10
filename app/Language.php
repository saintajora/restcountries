<?php

namespace App;

class Language extends BGModel {

	// Laravel
	protected $table = 'languages';
	protected $primaryKey = 'iso639_1';
	public $incrementing = false;
	
	protected static $model_fields = [
			'name'=>'string',
			'iso639_1'=>'string',
			'iso639_2'=>'string'
	];

	// Country relationship
	public function country() {
		return $this->belongsToMany('App\Country', 'country_languages', 'iso639_2', 'alpha2Code');
	}
}
