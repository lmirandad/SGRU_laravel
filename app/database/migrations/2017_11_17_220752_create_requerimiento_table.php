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
			$table->dateTime('fecha_registro');
			$table->dateTime('fecha_fin_registro')->nullable();
			$table->integer('cantidad_usuarios');
			$table->integer('idherramienta')->index('fk_requerimiento_herramienta1_idx');
			$table->integer('idtipo_requerimiento')->nullable()->index('fk_requerimiento_tipo_requerimiento1_idx');
			$table->integer('idestado_requerimiento')->nullable()->index('fk_requerimiento_estado_requerimiento1_idx');
			$table->integer('idasignacion')->nullable()->index('fk_requerimiento_asignacion1_idx');
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
