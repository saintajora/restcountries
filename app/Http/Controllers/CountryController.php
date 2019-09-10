<?php
/**
 * Controller for the Rest Countries search engine
 * 
 * @author Ben Goetzinger
 * @package RestCountries
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scrubber;
use App\Country;

class CountryController extends Controller {
	
	/**
	 * Logic for the /search endpoint
	 * 
	 * @param Request $req
	 * @return string
	 */
	public function search_countries(Request $req) {
		
		$search = Scrubber::string($req->input('query'));
		if(!$search) {
			return $this->json_err('Invalid Input.');
		}
		
		$max = intval($req->input('maxResults'));
		$num_results = is_int($max) ? $max : 50;
		$order_by = $req->input('orderBy') == 'population' ? 'population' : 'name';
		$chars = strlen($search);
		
		$db = false;
		try {
			$c = Country::first();
			if(!empty($c)) $db = true;
		} catch(\Exception $e) {}

		if($db == true) {
			if($chars == 2) {
				$countries = Country::with('languages')->where('alpha2code', $search)->orderBy($order_by)->limit($num_results)->get();
			} elseif($chars == 3) {
				$countries = Country::with('languages')->where('alpha3code', $search)->orderBy($order_by)->limit($num_results)->get();
			} else {
				$countries = Country::with('languages')->where('name', 'LIKE', '%'.$search.'%')->orderBy($order_by)->limit($num_results)->get();
			}
		} else {
			if($chars == 2 || $chars == 3) {
				$countries = Country::api('alpha/'.$search, $num_results);
			} else {
				$countries = Country::api('name/'.urlencode($search), $num_results);
			}
		}

		if(!isset($countries) || empty($countries)) {
			return $this->json_err('No Results.');
		}
		
		if($order_by == 'population') usort($countries, function($a, $b) { return $b->population - $a->population; });
		
		return json_encode($countries);
	}
	
	/**
	 * Shorthand function for returning errors.
	 * 
	 * @param string $str
	 * @return string
	 */
	protected function json_err($str) {
		return json_encode(['error'=>true, 'message'=>$str]);
	}
}
