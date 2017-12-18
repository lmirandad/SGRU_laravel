<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToEquivalenciaTipoSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('equivalencia_tipo_solicitud', function(Blueprint $table)
		{
			$table->foreign('idtipo_solicitud', 'fk_equivalencia_tipo_solicitud_tipo_solicitud')->references('idtipo_solicitud')->on('tipo_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_equivalencia_tipo_solicitud_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_equivalencia_tipo_solicitud_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('equivalencia_tipo_solicitud', function(Blueprint $table)
		{
			$table->dropForeign('fk_equivalencia_tipo_solicitud_tipo_solicitud');
			$table->dropForeign('fk_equivalencia_tipo_solicitud_users1');
			$table->dropForeign('fk_equivalencia_tipo_solicitud_users2');
		});
	}

}
