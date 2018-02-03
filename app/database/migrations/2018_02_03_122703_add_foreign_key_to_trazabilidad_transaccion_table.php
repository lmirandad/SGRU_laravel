<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTrazabilidadTransaccionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('trazabilidad_transaccion', function(Blueprint $table)
		{
			$table->foreign('idtransaccion', 'fk_trazabilidad_transaccion_transaccion')->references('idtransaccion')->on('transaccion')->onUpdate('NO ACTION')->onDelete('NO ACTION');			
			$table->foreign('iduser_created_by', 'fk_trazabilidad_transaccion_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');		

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('trazabilidad_transaccion', function(Blueprint $table)
		{
			$table->dropForeign('fk_trazabilidad_transaccion_transaccion');
			$table->dropForeign('fk_trazabilidad_transaccion_users1');
		});
	}

}
