<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPerfilAplicativoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('perfil_aplicativo', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_perfil_aplicativo_herramienta')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_perfil_aplicativo_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_perfil_aplicativo_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('perfil_aplicativo', function(Blueprint $table)
		{
			$table->dropForeign('fk_perfil_aplicativo_herramienta');
			$table->dropForeign('fk_perfil_aplicativo_users1');
			$table->dropForeign('fk_perfil_aplicativo_users2');
		});
	}

}
