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
			$table->foreign('idherramienta', 'fk_requerimiento_herramienta1')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idtipo_requerimiento', 'fk_requerimiento_tipo_requerimiento1')->references('idtipo_requerimiento')->on('tipo_requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idestado_requerimiento', 'fk_requerimiento_estado_requerimiento1')->references('idestado_requerimiento')->on('estado_requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idasignacion', 'fk_requerimiento_asignacion1')->references('idasignacion')->on('asignacion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idtipo_accion_requerimiento', 'fk_requerimiento_tipo_accion_requerimiento')->references('idtipo_accion_requerimiento')->on('tipo_accion_requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
			$table->dropForeign('fk_requerimiento_herramienta1');
			$table->dropForeign('fk_requerimiento_tipo_requerimiento1');
			$table->dropForeign('fk_requerimiento_estado_requerimiento1');
			$table->dropForeign('fk_requerimiento_asignacion1');
			$table->dropForeign('fk_requerimiento_tipo_accion_requerimiento');
			$table->dropForeign('fk_requerimiento_users1');
			$table->dropForeign('fk_requerimiento_users2');
		});
	}

}
