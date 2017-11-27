<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaxtipoSolicitudxuserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramientaxtipo_solicitudxuser', function(Blueprint $table)
		{
			$table->foreign('idherramientaxtipo_solicitud', 'fk_herramientaxtipo_solicitudxuser_herramientaxtipo_solicitud')->references('idherramientaxtipo_solicitud')->on('herramientaxtipo_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser', 'fk_herramientaxtipo_solicitudxuser_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_herramientaxtipo_solicitudxuser_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramientaxtipo_solicitudxuser_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramientaxtipo_solicitudxuser', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramientaxtipo_solicitudxuser_herramientaxtipo_solicitud');
			$table->dropForeign('fk_herramientaxtipo_solicitudxuser_users1');
			$table->dropForeign('fk_herramientaxtipo_solicitudxuser_users2');
			$table->dropForeign('fk_herramientaxtipo_solicitudxuser_users3');
		});
	}

}
