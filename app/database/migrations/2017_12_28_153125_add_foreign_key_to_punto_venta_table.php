<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPuntoVentaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('punto_venta', function(Blueprint $table)
		{
			$table->foreign('identidad', 'fk_punto_venta_entidad')->references('identidad')->on('entidad')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_punto_venta_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_punto_venta_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('punto_venta', function(Blueprint $table)
		{
			$table->dropForeign('fk_punto_venta_entidad');
			$table->dropForeign('fk_punto_venta_users1');
			$table->dropForeign('fk_punto_venta_users2');
		});
	}

}
