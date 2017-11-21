<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToObservacionRequerimientoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('observacion_requerimiento', function(Blueprint $table)
		{
			$table->foreign('idrequerimiento', 'fk_observacion_requerimiento_requerimiento1')->references('idrequerimiento')->on('requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_observacion_requerimiento_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_observacion_requerimiento_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('observacion_requerimiento', function(Blueprint $table)
		{
			$table->dropForeign('fk_observacion_requerimiento_requerimiento1');
			$table->dropForeign('fk_observacion_requerimiento_users1');
			$table->dropForeign('fk_observacion_requerimiento_users2');
		});
	}

}
