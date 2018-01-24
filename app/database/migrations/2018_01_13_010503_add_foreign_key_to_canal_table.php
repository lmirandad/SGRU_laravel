<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('canal', function(Blueprint $table)
		{
			$table->foreign('idsector', 'fk_canal_sector1')->references('idsector')->on('sector')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_canal_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_canal_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idusuario_responsable', 'fk_canal_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idcanal_agrupado', 'fk_canal_canal_agrupado')->references('idcanal_agrupado')->on('canal_agrupado')->onUpdate('NO ACTION')->onDelete('NO ACTION');			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('canal', function(Blueprint $table)
		{
			$table->dropForeign('fk_canal_sector1');
			$table->dropForeign('fk_canal_users1');
			$table->dropForeign('fk_canal_users2');
			$table->dropForeign('fk_canal_users3');
			$table->dropForeign('fk_canal_canal_agrupado');
		});
	}
}
