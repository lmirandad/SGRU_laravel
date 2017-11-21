<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->foreign('idrol', 'fk_users_rol')->references('idrol')->on('rol')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idtipo_doc_identidad', 'fk_users_tipo_doc_identidad1')->references('idtipo_doc_identidad')->on('tipo_doc_identidad')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_users_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_users_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropForeign('fk_users_rol');
			$table->dropForeign('fk_users_tipo_doc_identidad1');
			$table->dropForeign('fk_users_users1');
			$table->dropForeign('fk_users_users2');
		});
	}

}
