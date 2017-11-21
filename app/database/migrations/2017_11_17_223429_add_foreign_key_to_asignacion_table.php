<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToAsignacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('asignacion', function(Blueprint $table)
		{
			$table->foreign('idestado_asignacion', 'fk_asignacion_estado_asignacion1')->references('idestado_asignacion')->on('estado_asignacion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_asignado', 'fk_asignacion_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsolicitud', 'fk_asignacion_solicitud1')->references('idsolicitud')->on('solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_asignacion_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_asignacion_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('asignacion', function(Blueprint $table)
		{
			$table->dropForeign('fk_asignacion_estado_asignacion1');
			$table->dropForeign('fk_asignacion_users1');
			$table->dropForeign('fk_asignacion_users2');
			$table->dropForeign('fk_asignacion_users3');
			$table->dropForeign('fk_asignacion_solicitud1');
		});
	}

}
