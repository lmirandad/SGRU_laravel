<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCargoCanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cargo_canal', function(Blueprint $table)
		{
			$table->foreign('idcanal', 'fk_cargo_canal_canal')->references('idcanal')->on('canal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_cargo_canal_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_cargo_canal_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cargo_canal', function(Blueprint $table)
		{
			$table->dropForeign('fk_cargo_canal_canal');
			$table->dropForeign('fk_cargo_canal_users1');
			$table->dropForeign('fk_cargo_canal_users2');
		});
	}

}
