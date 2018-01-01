<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntoVentaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('punto_venta', function(Blueprint $table)
		{
			$table->integer('idpunto_venta', true);
			$table->string('nombre',200);
			$table->integer('identidad')->nullable()->index('fk_punto_venta_entidad_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_punto_venta_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_punto_venta_users2_idx');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('punto_venta');
	}


}
