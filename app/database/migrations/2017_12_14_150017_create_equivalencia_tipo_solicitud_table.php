<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquivalenciaTipoSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equivalencia_tipo_solicitud', function(Blueprint $table)
		{
			$table->integer('idequivalencia_tipo_solicitud', true);
			$table->string('nombre_equivalencia');
			$table->integer('iduser_created_by')->nullable()->index('fk_equivalencia_tipo_solicitud_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_equivalencia_tipo_solicitud_users2_idx');
			$table->integer('idtipo_solicitud')->nullable()->index('fk_equivalencia_tipo_solicitud_tipo_solicitud_idx');
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
		Schema::drop('equivalencia_tipo_solicitud');
	}

}
