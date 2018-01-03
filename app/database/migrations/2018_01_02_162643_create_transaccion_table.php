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
			$table->datetime('fecha_registro');
			$table->datetime('fecha_cierre')->nullable();
			$table->string('cargo_canal',200)->nullable();
			$table->string('perfil_aplicativo',200)->nullable();
			$table->string('numero_documento',10)->nullable();
			$table->string('nombre_usuario',200)->nullable();
			$table->integer('usuario_bloqueado')->nullable();
			$table->integer('idestado_transaccion')->nullable()->index('fk_transaccion_estado_transaccion_idx');
			$table->integer('idrequerimiento')->nullable()->index('fk_transaccion_requerimiento_idx');
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
