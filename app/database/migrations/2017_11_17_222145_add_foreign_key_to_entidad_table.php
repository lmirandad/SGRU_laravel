<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToEntidadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entidad', function(Blueprint $table)
		{
			$table->foreign('idcanal', 'fk_entidad_canal1')->references('idcanal')->on('canal')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_entidad_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_entidad_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entidad', function(Blueprint $table)
		{
			$table->dropForeign('fk_entidad_canal1');
			$table->dropForeign('fk_entidad_users1');
			$table->dropForeign('fk_entidad_users2');
		});
	}

}
