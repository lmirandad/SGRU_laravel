<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaxsectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxsector', function(Blueprint $table)
		{
			$table->integer('idherramientaxsector', true);
			$table->integer('idherramienta')->index('fk_herramientaxsector_herramienta_idx');
			$table->integer('idsector')->index('fk_herramientaxsector_sector_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxsector_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxsector_users2_idx');
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
		Schema::drop('herramientaxsector');
	}

}
