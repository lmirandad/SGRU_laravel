<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsuariosxasignacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('usuariosxasignacion', function(Blueprint $table)
		{
			$table->foreign('idusuario_asignado', 'fk_usuariosxasignacion_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idasignacion', 'fk_usuariosxasignacion_asignacion')->references('idasignacion')->on('asignacion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_usuariosxasignacion_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_usuariosxasignacion_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('usuariosxasignacion', function(Blueprint $table)
		{
			$table->dropForeign('fk_usuariosxasignacion_users1');
			$table->dropForeign('fk_usuariosxasignacion_asignacion');
			$table->dropForeign('fk_usuariosxasignacion_users2');
			$table->dropForeign('fk_usuariosxasignacion_users3');

		});
	}

}
