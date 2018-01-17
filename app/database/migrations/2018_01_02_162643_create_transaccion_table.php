<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaccion', function(Blueprint $table)
		{
			$table->integer('idtransaccion', true);
			$table->string('codigo_requerimiento',100)->nullable();
			$table->datetime('fecha_registro');
			$table->datetime('fecha_inicio_procesando')->nullable();
			$table->datetime('fecha_cierre')->nullable();
			$table->string('cargo_canal',200)->nullable();
			$table->string('numero_documento',10)->nullable();
			$table->string('nombre_usuario',200)->nullable();
			$table->integer('usuario_bloqueado')->nullable();			
			
			$table->integer('idherramienta')->nullable()->index('fk_transaccion_herramienta_idx');
			$table->integer('idpunto_venta')->nullable()->index('fk_transaccion_punto_venta_idx');


			$table->integer('idsolicitud')->nullable()->index('fk_transaccion_solicitud_idx');
			$table->string('accion_requerimiento',200)->nullable();

			$table->integer('idestado_transaccion')->nullable()->index('fk_transaccion_estado_transaccion_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_transaccion_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_transaccion_users2_idx');
			$table->string('observaciones',400)->nullable();
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
		Schema::drop('transaccion');
	}

}
