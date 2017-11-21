<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaxusersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramientaxusers', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_herramientaxusers_herramienta1')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser', 'fk_herramientaxusers_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_herramientaxusers_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramientaxusers_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramientaxusers', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramientaxusers_herramienta1');
			$table->dropForeign('fk_herramientaxusers_users1');
			$table->dropForeign('fk_herramientaxusers_users2');
			$table->dropForeign('fk_herramientaxusers_users3');
		});
	}

}
