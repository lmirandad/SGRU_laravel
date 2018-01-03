<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToLogCargaFurTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('log_carga_fur', function(Blueprint $table)
		{
			$table->foreign('idrequerimiento', 'fk_log_carga_fur_requerimiento')->references('idrequerimiento')->on('requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_log_carga_fur_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transaccion', function(Blueprint $table)
		{
			$table->dropForeign('fk_log_carga_fur_requerimiento');
			$table->dropForeign('fk_log_carga_fur_users');
		});
	}

}
