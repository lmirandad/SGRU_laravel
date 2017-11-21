<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaArchivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carga_archivo', function(Blueprint $table)
		{
			$table->integer('idcarga_archivo', true);
			$table->dateTime('fecha_carga_archivo');
			$table->integer('iduser_registrador')->index('fk_carga_archivo_users1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_carga_archivo_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_carga_archivo_users3_idx');
			$table->integer('idestado_carga_archivo')->index('fk_carga_archivo_estado_carga_archivo1_idx');
			$table->integer('idtipo_carga_archivo')->index('fk_carga_archivo_tipo_carga_archivo1_idx');
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
		Schema::drop('carga_archivo');
	}

}
