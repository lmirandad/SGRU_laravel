<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCargaArchivoPlanillaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('carga_archivo_planilla', function(Blueprint $table)
		{
			$table->foreign('iduser_registrador', 'fk_carga_archivo_planilla_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_carga_archivo_planilla_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_carga_archivo_planilla_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');		

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('carga_archivo_planilla', function(Blueprint $table)
		{
			$table->dropForeign('fk_carga_archivo_planilla_users1');
			$table->dropForeign('fk_carga_archivo_planilla_users2');
			$table->dropForeign('fk_carga_archivo_planilla_users3');
		});
	}

}
