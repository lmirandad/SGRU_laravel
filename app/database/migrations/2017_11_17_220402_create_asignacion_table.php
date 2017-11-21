<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asignacion', function(Blueprint $table)
		{
			$table->integer('idasignacion', true);
			$table->dateTime('fecha_asignacion');
			$table->integer('idestado_asignacion')->index('fk_asignacion_estado_asignacion1_idx');
			$table->integer('iduser_asignado')->nullable()->index('fk_asignacion_users1_idx');
			$table->integer('idsolicitud')->nullable()->index('fk_asignacion_solicitud1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_asignacion_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_asignacion_users3_idx');
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
		Schema::drop('asignacion');
	}

}
