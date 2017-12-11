<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudxherramientaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solicitudxherramienta', function(Blueprint $table)
		{
			$table->integer('idsolicitudxherramienta', true);
			$table->integer('idsolicitud')->index('fk_solicitudxherramienta_solicitud_idx');;
			$table->integer('idherramienta')->index('fk_solicitudxherramienta_herramienta_idx');;
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
		Schema::drop('solicitudxherramienta');
	}

}
