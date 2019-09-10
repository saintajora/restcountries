<?php

/**
 * The Country Model. Back-end logic for procuring and loading country data.
 * 
 * @author Ben Goetzinger
 * @package RestCountries
 */
namespace App;

use Illuminate\Support\Facades\Log;

class Country extends BGModel {

	// Laravel
	protected $table = 'countries';
	protected $primaryKey = 'alpha2Code';
	public $incrementing = false;

	// Rest Country API
	protected static $api_url = 'https://restcountries.eu/rest/v2/';
	
	protected static $model_fields = [
			'name'=>'string',
			'alpha2Code'=>'string',
			'alpha3Code'=>'string',
			'flag'=>'url',
			'region'=>'string',
			'subregion'=>'string',
			'population'=>'int'
	];

	// Language relationship
	public function languages() {
		return $this->belongsToMany('App\Language', 'country_languages', 'alpha2Code', 'iso639_2');
	}

	/**
	 * Populates the local database cache from the api.
	 *
	 * @return Array on success|boolean false on failure
	 */
	public static function reload_from_api() {
		if(!$result = static::api('all', 99999))
			return false;

		$saved = 0;
		foreach($result as $r) {
			$langs = $r['languages'];
			unset($r['languages']);

			$c = new Country();
			$c->populate($r);

			if($c->save()) {
				$saved++;
				foreach($langs as $l) {
					$lang = Language::firstOrCreate($l);
					$c->languages()->save($lang);
				}
			}
		}

		return [
				'saved'=>$saved,
				'total'=>count($result)
		];
	}

	/**
	 * Execute an api request, scrub, and return the resulting objects.
	 *
	 * @param string $endpoint
	 * @param number $max_results
	 * @param array $args
	 * @return Country on success|boolean false on failure
	 */
	public static function api($endpoint, $max_results = 50, $args = []) {
		$curl = curl_init(static::$api_url . $endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if(!empty($args)) {
			$str = '';
			foreach($args as $key=>$val) {
				$str .= $key . '=' . $val . '&';
			}
			curl_setopt($curl, CURLOPT_POSTFIELDS, urlencode(rtrim('&', $str)));
		}

		$res = curl_exec($curl);
		if(!$res) {
			Log::error(curl_strerror(curl_errorno($curl)));
			return false;
		}

		$res = json_decode($res, true);
		if(isset($res['status']) && $res['status'] == '404') return false;

		$ret = [];
		if(isset($res[0])) {
			$count = 0;
			foreach($res as $cdata) {
				if(static::parse_field_data($cdata))
					$ret[] = $cdata;
				$count++;

				if($count >= $max_results)
					break;
			}
		} elseif(isset($res['name']) && static::parse_field_data($res)) {
			$ret[] = $res;
		} else {
			$ret = false;
		}
		
		return $ret;
	}

	/**
	 * Override in order to properly handle languages.
	 *
	 * {@inheritdoc}
	 * @see \App\BGModel::parse_field_data()
	 */
	protected static function parse_field_data(&$data) {
		$langs = [];
		if(is_array($data['languages']))
			foreach($data['languages'] as $l) {
				if(Language::parse_field_data($l))
					$langs[] = $l;
			}
		if(empty($langs))
			return false;
		unset($data['languages']);

		if(parent::parse_field_data($data)) {
			$data['languages'] = $langs;
			return true;
		} else {
			return false;
		}
	}
}
