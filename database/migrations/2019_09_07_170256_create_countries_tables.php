<?php
/**
 * Migration for Rest Countries cache
 * 
 * @author Ben Goetzinger
 * @package RestCountries
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('countries', function (Blueprint $table) {
			$table->string('name')->unique();
			$table->char('alpha2Code', 2)->unique();
			$table->char('alpha3Code', 3)->unique();
			$table->string('region');
			$table->string('subregion')->default('');
			$table->string('flag')->default('');
			$table->integer('population')->unsigned();
			$table->primary('alpha2Code');
			$table->timestamps();
		});
		
		Schema::create('languages', function (Blueprint $table) {
			$table->string('name')->unique();
			$table->char('iso639_1', 2)->unique();
			$table->char('iso639_2', 3)->unique();
			$table->primary('iso639_2');
			$table->timestamps();
		});
		
		Schema::create('country_languages', function (Blueprint $table) {
			$table->char('alpha2Code', 2);
			$table->char('iso639_2', 2);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('countries');
		Schema::dropIfExists('languages');
		Schema::dropIfExists('country_languages');
	}
}
