<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToRequerimientoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('requerimiento', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_requerimiento_herramienta')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idpunto_venta', 'fk_requerimiento_punto_venta')->references('idpunto_venta')->on('punto_venta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idestado_requerimiento', 'fk_requerimiento_estado_requerimiento')->references('idestado_requerimiento')->on('estado_requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsolicitud', 'fk_requerimiento_solicitud')->references('idsolicitud')->on('solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_requerimiento_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_requerimiento_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('requerimiento', function(Blueprint $table)
		{
			$table->dropForeign('fk_requerimiento_herramienta');
			$table->dropForeign('fk_requerimiento_punto_venta');
			$table->dropForeign('fk_requerimiento_estado_requerimiento');
			$table->dropForeign('fk_requerimiento_solicitud');
			$table->dropForeign('fk_requerimiento_users1');
			$table->dropForeign('fk_requerimiento_users2');
		});
	}


}
