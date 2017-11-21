<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaxusersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxusers', function(Blueprint $table)
		{
			$table->integer('idherramientaxusers', true);
			$table->integer('idherramienta')->index('fk_herramientaxusers_herramienta1_idx');
			$table->integer('iduser')->index('fk_herramientaxusers_users1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxusers_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxusers_users3_idx');
			$table->integer('estado');
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
		Schema::drop('herramientaxusers');
	}

}
