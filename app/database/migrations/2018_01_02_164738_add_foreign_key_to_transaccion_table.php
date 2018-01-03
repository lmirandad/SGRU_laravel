<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTransaccionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transaccion', function(Blueprint $table)
		{
			$table->foreign('idestado_transaccion', 'fk_transaccion_estado_transaccion')->references('idestado_transaccion')->on('estado_transaccion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idrequerimiento', 'fk_transaccion_requerimiento')->references('idrequerimiento')->on('requerimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_transaccion_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_transaccion_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

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
			$table->dropForeign('fk_transaccion_estado_transaccion');
			$table->dropForeign('fk_transaccion_requerimiento');
			$table->dropForeign('fk_transaccion_users1');
			$table->dropForeign('fk_transaccion_users2');
		});
	}

}
