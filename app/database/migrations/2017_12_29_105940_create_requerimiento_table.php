<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequerimientoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requerimiento', function(Blueprint $table)
		{
			$table->integer('idrequerimiento', true);
			$table->string('codigo_requerimiento',200)->nullable();
			$table->datetime('fecha_registro');
			$table->datetime('fecha_cierre')->nullable();
			$table->integer('idherramienta')->nullable()->index('fk_requerimiento_herramienta_idx');
			$table->integer('idpunto_venta')->nullable()->index('fk_requerimiento_punto_venta_idx');
			$table->string('observaciones',400)->nullable();
			$table->integer('idestado_requerimiento')->nullable()->index('fk_requerimiento_estado_requerimiento_idx');
			$table->integer('idsolicitud')->nullable()->index('fk_requerimiento_solicitud_idx');
			$table->string('accion_requerimiento',200)->nullable();
			$table->integer('iduser_created_by')->nullable()->index('fk_requerimiento_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_requerimiento_users2_idx');
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
		Schema::drop('requerimiento');
	}

}
