<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaxsectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramientaxsector', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_herramientaxsector_herramienta')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsector', 'fk_herramientaxsector_sector')->references('idsector')->on('sector')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_herramientaxsector_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramientaxsector_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramientaxsector', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramientaxsector_herramienta');
			$table->dropForeign('fk_herramientaxsector_sector');
			$table->dropForeign('fk_herramientaxsector_users1');
			$table->dropForeign('fk_herramientaxsector_users2');
		});
	}

}
