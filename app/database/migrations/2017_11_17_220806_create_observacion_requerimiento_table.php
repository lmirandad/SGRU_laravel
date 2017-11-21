<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservacionRequerimientoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('observacion_requerimiento', function(Blueprint $table)
		{
			$table->integer('idobservacion_requerimiento', true);
			$table->string('observacion', 200);
			$table->integer('idrequerimiento')->index('fk_observacion_requerimiento_requerimiento1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_observacion_requerimiento_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_observacion_requerimiento_users2_idx');			
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
		Schema::drop('observacion_requerimiento');
	}

}
