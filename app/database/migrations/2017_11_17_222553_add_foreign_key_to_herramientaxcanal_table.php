<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaxcanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramientaxcanal', function(Blueprint $table)
		{
			$table->foreign('idherramienta', 'fk_herramientaxcanal_herramienta1')->references('idherramienta')->on('herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idcanal', 'fk_herramientaxcanal_canal1')->references('idcanal')->on('canal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_herramientaxcanal_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramientaxcanal_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramientaxcanal', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramientaxcanal_herramienta1');
			$table->dropForeign('fk_herramientaxcanal_canal1');
			$table->dropForeign('fk_herramientaxcanal_users1');
			$table->dropForeign('fk_herramientaxcanal_users2');
		});
	}

}
