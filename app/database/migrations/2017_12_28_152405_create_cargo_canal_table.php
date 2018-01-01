<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoCanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cargo_canal', function(Blueprint $table)
		{
			$table->integer('idcargo_canal', true);
			$table->string('nombre',200);	
			$table->integer('idcanal')->nullable()->index('fk_cargo_canal_canal_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_cargo_canal_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_cargo_canal_users2_idx');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cargo_canal');
	}

}
