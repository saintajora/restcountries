<?php
use Illuminate\Database\Seeder;
use App\Country;

class DatabaseSeeder extends Seeder {

	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		if(!$refresh = Country::reload_from_api()) {
			echo "An error occured. Please consult the logs.".PHP_EOL;
			return;
		}
		
		echo "Saved ".$refresh['saved']." of ".$refresh['total'].PHP_EOL;
	}
}
