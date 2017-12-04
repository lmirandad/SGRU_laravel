<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('solicitud', function(Blueprint $table)
		{
			$table->foreign('idtipo_solicitud', 'fk_solicitud_tipo_solicitud1')->references('idtipo_solicitud')->on('tipo_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idestado_solicitud', 'fk_solicitud_estado_solicitud1')->references('idestado_solicitud')->on('estado_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('identidad', 'fk_solicitud_entidad')->references('identidad')->on('entidad');
			$table->foreign('iduser_created_by', 'fk_solicitud_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_solicitud_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idcarga_archivo', 'fk_solicitud_carga_archivo1')->references('idcarga_archivo')->on('carga_archivo')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idasignacion', 'fk_solicitud_asignacion1')->references('idasignacion')->on('asignacion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsla', 'fk_solicitud_sla')->references('idsla')->on('sla')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('solicitud', function(Blueprint $table)
		{
			$table->dropForeign('fk_solicitud_tipo_solicitud1');
			$table->dropForeign('fk_solicitud_estado_solicitud1');
			$table->dropForeign('fk_solicitud_entidad');
			$table->dropForeign('fk_solicitud_users1');
			$table->dropForeign('fk_solicitud_users2');
			$table->dropForeign('fk_solicitud_carga_archivo1');
			$table->dropForeign('fk_solicitud_asignacion1');
			$table->dropForeign('fk_solicitud_sla');
		});
	}

}
