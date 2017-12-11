<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSolicitudxherramientaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('solicitudxherramienta', function(Blueprint $table)
		{
			$table->foreign('idsolicitud', 'fk_solicitudxherramienta_solicitud')->references('idsolicitud')->on('solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idherramienta', 'fk_solicitudxherramienta_herramienta')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('solicitudxherramienta', function(Blueprint $table)
		{
			$table->dropForeign('fk_solicitudxherramienta_solicitud');
			$table->dropForeign('fk_solicitudxherramienta_herramienta');
		});
	}

}
