<?php
/**
 * Functions for data sanitization.
 * 
 * @author Ben Goetzinger
 * @package RestCountries
 */
namespace App;

class Scrubber {

	/**
	 * Scrub a string. Recursive for arrays of strings.
	 * 
	 * @param string $str
	 * @return string on success|boolean false on failure
	 */
	public static function string($str) {
		if(is_array($str)) {
			$ret = [];
			foreach($str as $s) {
				$ret[] = static::string($s);
			}
		} else {
			$ret = filter_var($str, FILTER_SANITIZE_STRING);
		}

		return $ret;
	}

	/**
	 * Scrub a URL. Recursive for arrays of URLs.
	 *
	 * @param string $str
	 * @return string on success|boolean false on failure
	 */
	public static function url($url) {
		if(is_array($url)) {
			$ret = [];
			foreach($url as $s) {
				$ret[] = static::string($s);
			}
		} else {
			$ret = filter_var($url, FILTER_SANITIZE_URL);
		}
		
		return $ret;
	}

	/**
	 * Scrub an integer. Recursive for arrays of integers.
	 *
	 * @param int $str
	 * @return int on success|boolean false on failure
	 */
	public static function int($int) {
		if(is_array($int)) {
			$ret = [];
			foreach($int as $s) {
				$ret[] = static::string($s);
			}
		} else {
			$ret = filter_var($int, FILTER_SANITIZE_NUMBER_INT);
		}
		
		return $ret;
	}
}