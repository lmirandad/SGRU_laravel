<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioVenaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuario_vena', function(Blueprint $table)
		{
			$table->integer('idusuario_vena', true);
			$table->string('numero_documento',100);
			$table->string('fecha_bloqueo',100);
			$table->string('motivo',1000);
			$table->datetime('fecha_registro');	
			$table->integer('iduser_created_by')->nullable()->index('fk_usuario_observado_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_usuario_observado_users2_idx');
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
		Schema::drop('usuario_vena');
	}

}
