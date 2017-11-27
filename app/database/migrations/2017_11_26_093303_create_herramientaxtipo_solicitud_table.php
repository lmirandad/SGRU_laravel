<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaxtipoSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxtipo_solicitud', function(Blueprint $table)
		{
			$table->integer('idherramientaxtipo_solicitud', true);
			$table->integer('idherramienta')->index('fk_herramientaxtipo_solicitud_herramienta_idx');
			$table->integer('idtipo_solicitud')->index('fk_herramientaxtipo_solicitud_tipo_solicitud_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxtipo_solicitud_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxtipo_solicitud_users2_idx');
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
		Schema::drop('herramientaxtipo_solicitud');
	}

}
