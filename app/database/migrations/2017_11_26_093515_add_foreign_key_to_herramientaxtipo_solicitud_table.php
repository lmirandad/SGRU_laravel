<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaxtipoSolicitudTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramientaxtipo_solicitud', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_herramientaxtipo_solicitud_herramienta')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idtipo_solicitud', 'fk_herramientaxtipo_solicitud_tipo_solicitud')->references('idtipo_solicitud')->on('tipo_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_herramientaxtipo_solicitud_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramientaxtipo_solicitud_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramientaxtipo_solicitud', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramientaxtipo_solicitud_herramienta');
			$table->dropForeign('fk_herramientaxtipo_solicitud_tipo_solicitud');
			$table->dropForeign('fk_herramientaxtipo_solicitud_users1');
			$table->dropForeign('fk_herramientaxtipo_solicitud_users2');
		});
	}

}
