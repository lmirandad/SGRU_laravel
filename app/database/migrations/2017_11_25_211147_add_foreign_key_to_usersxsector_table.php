<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsersxsectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('usersxsector', function(Blueprint $table)
		{
			$table->foreign('iduser', 'fk_usersxsector_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsector', 'fk_usersxsector_sector')->references('idsector')->on('sector')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_usersxsector_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_usersxsector_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('usersxsector', function(Blueprint $table)
		{
			$table->dropForeign('fk_usersxsector_users');
			$table->dropForeign('fk_usersxsector_sector');
			$table->dropForeign('fk_usersxsector_users1');
			$table->dropForeign('fk_usersxsector_users2');
		});
	}

}
