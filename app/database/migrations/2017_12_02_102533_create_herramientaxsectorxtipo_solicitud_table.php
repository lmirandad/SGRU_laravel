<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaxsectorxtipoSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxsectorxtipo_solicitud', function(Blueprint $table)
		{
			$table->integer('idherramientaxsectorxtipo_solicitud', true);
			$table->integer('idherramientaxsector')->index('fk_herramientaxsectorxtipo_solicitud_herramientaxsector_idx');
			$table->integer('idtipo_solicitud')->index('fk_herramientaxsectorxtipo_solicitud_tipo_solicitud_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxsectorxtipo_solicitud_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxsectorxtipo_solicitud_users2_idx');
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
		Schema::drop('herramientaxsectorxtipo_solicitud');
	}

}
