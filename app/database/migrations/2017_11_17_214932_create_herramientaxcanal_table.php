<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaxcanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxcanal', function(Blueprint $table)
		{
			$table->integer('idherramientaxcanal', true);
			$table->integer('idherramienta')->index('fk_herramientaxcanal_herramienta1_idx');
			$table->integer('idcanal')->index('fk_herramientaxcanal_canal1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxcanal_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxcanal_users2_idx');
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
		Schema::drop('herramientaxcanal');
	}


}
