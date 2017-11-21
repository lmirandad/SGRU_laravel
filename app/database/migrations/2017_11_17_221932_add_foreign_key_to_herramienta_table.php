<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToHerramientaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('herramienta', function(Blueprint $table)
		{
			$table->foreign('idtipo_herramienta', 'fk_herramienta_tipo_herramienta1')->references('idtipo_herramienta')->on('tipo_herramienta')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_herramienta_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_herramienta_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('herramienta', function(Blueprint $table)
		{
			$table->dropForeign('fk_herramienta_tipo_herramienta1');
			$table->dropForeign('fk_herramienta_users1');
			$table->dropForeign('fk_herramienta_users2');
		});
	}

}
