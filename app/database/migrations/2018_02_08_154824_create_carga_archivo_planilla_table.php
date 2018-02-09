<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaArchivoPlanillaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carga_archivo_planilla', function(Blueprint $table)
		{
			$table->integer('idcarga_archivo_planilla', true);
			$table->dateTime('fecha_carga_archivo');
			$table->integer('estado_carga')->nullable();
			$table->integer('iduser_registrador')->index('fk_carga_archivo_planilla_users1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_carga_archivo_planilla_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_carga_archivo_planilla_users3_idx');			
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
		Schema::drop('carga_archivo_planilla');
	}

}
