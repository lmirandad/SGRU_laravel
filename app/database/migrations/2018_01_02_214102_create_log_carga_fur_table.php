<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogCargaFurTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_carga_fur', function(Blueprint $table)
		{
			$table->integer('idlog_carga_fur', true);
			$table->string('numero_fila',200);
			$table->string('resultado',500);
			$table->string('nombre_archivo',500);
			$table->integer('iduser_created_by')->nullable()->index('fk_log_carga_fur_users_idx');	
			$table->integer('idrequerimiento')->nullable()->index('fk_log_carga_fur_requerimiento_idx');
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
		Schema::drop('log_carga_fur');
	}

}
