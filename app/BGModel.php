<?php

/**
 * My little extension to the Model class. Just for sanitization for the moment.
 * 
 * @author Ben Goetzinger
 * @package RestCountries
 */
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class BGModel extends Model {

	// Fields to scrub go here as array('field'=>'data_type')
	protected static $model_fields = [];

	/**
	 * Sanitize the data and assign it to the object.
	 *
	 * @param Array $data
	 * @return boolean
	 */
	protected static function parse_field_data(&$data) {
		$fail = false;

		$newData = [];
		foreach(static::$model_fields as $fname=>$ftype) {
			$scrub = Scrubber::{$ftype}($data[$fname]);
			if($scrub === false) {
				$fail = true;
			} else {
				$newData[$fname] = $scrub;
			}
		}

		if($fail) {
			Log::error('API data parse error. ' . var_export($data, true));
			return false;
		} else {
			$data = $newData;
			return true;
		}
	}

	/**
	 * Populate the object from the allowed fields.
	 *
	 * @param Array $data
	 * @return void
	 */
	protected function populate($data) {
		foreach(static::$model_fields as $fname=>$ftype) {
			$this->{$fname} = $data[$fname];
		}
	}
}
