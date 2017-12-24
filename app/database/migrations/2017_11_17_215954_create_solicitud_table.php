<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('solicitud', function(Blueprint $table)
		{
			$table->integer('idsolicitud', true);
			$table->string('asunto', 300);
			$table->string('codigo_solicitud',100);
			$table->integer('idherramienta')->nullable()->index('fk_solicitud_herramienta_idx');
			$table->integer('identidad')->index('fk_solicitud_entidad_idx');
			$table->integer('idtipo_solicitud')->nullable()->index('fk_solicitud_tipo_solicitud1_idx');
			$table->integer('idestado_solicitud')->index('fk_solicitud_estado_solicitud1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_solicitud_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_solicitud_users2_idx');
			$table->integer('idcarga_archivo')->nullable()->index('fk_solicitud_carga_archivo1_idx');
			$table->integer('idsla')->nullable()->index('fk_solicitud_sla_idx');
			$table->dateTime('fecha_solicitud');
			$table->integer('idtipo_solicitud_general')->nullable()->index('fk_solicitud_tipo_solicitud_general_idx');
			$table->integer('idmotivo_rechazo')->nullable()->index('fk_solicitud_motivo_rechazo_idx');
			$table->string('motivo_anulacion',200)->nullable();
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
		Schema::drop('solicitud');
	}

}
