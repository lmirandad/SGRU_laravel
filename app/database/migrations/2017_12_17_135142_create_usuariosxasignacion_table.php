<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosxasignacionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuariosxasignacion', function(Blueprint $table)
		{
			$table->integer('idusuariosxasignacion', true);
			$table->string('motivo_asignacion',200)->nullable();
			$table->integer('estado_usuario_asignado');
			$table->integer('idusuario_asignado')->nullable()->index('fk_usuariosxasignacion_users1_idx');			
			$table->integer('idasignacion')->nullable()->index('fk_usuariosxasignacion_asignacion_idx');			
			$table->integer('iduser_created_by')->nullable()->index('fk_usuariosxasignacion_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_usuariosxasignacion_users3_idx');
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
		Schema::drop('usuariosxasignacion');
	}

}
