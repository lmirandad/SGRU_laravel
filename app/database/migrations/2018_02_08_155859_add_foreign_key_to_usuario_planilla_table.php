<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsuarioPlanillaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('usuario_planilla', function(Blueprint $table)
		{
			$table->foreign('idcarga_archivo_planilla', 'fk_usuario_planilla_carga_archivo_planilla')->references('idcarga_archivo_planilla')->on('carga_archivo_planilla')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_usuario_planilla_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_usuario_planilla_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');		

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('usuario_planilla', function(Blueprint $table)
		{
			$table->dropForeign('fk_usuario_planilla_carga_archivo_planilla');
			$table->dropForeign('fk_usuario_planilla_users1');
			$table->dropForeign('fk_usuario_planilla_users2');
		});
	}

}
