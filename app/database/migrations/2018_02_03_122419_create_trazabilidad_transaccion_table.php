<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrazabilidadTransaccionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trazabilidad_transaccion', function(Blueprint $table)
		{
			$table->integer('idtrazabilidad_transaccion', true);
			$table->string('descripcion',1000);
			$table->datetime('fecha_registro')->nullable();
			$table->integer('idtransaccion')->nullable()->index('fk_trazabilidad_transaccion_transaccion_idx');	
			$table->integer('iduser_created_by')->nullable()->index('fk_trazabilidad_transaccion_users1_idx');
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
		Schema::drop('trazabilidad_transaccion');
	}


}
