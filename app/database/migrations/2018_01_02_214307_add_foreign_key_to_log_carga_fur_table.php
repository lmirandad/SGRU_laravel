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
			$table->foreign('idtransaccion', 'fk_log_carga_fur_transaccion')->references('idtransaccion')->on('transaccion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
		Schema::table('log_carga_fur', function(Blueprint $table)
		{
			$table->dropForeign('fk_log_carga_fur_transaccion');
			$table->dropForeign('fk_log_carga_fur_users');
		});
	}

}
