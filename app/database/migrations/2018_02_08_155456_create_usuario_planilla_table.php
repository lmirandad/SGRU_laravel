<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioPlanillaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usuario_planilla', function(Blueprint $table)
		{
			$table->integer('idusuario_planilla', true);
			$table->string('nombre', 200);
			$table->string('apellido_paterno', 200);
			$table->string('apellido_materno', 200);
			$table->string('tipo_documento', 200);
			$table->string('numero_documento', 200);
			$table->string('canal', 500);
			$table->string('detalle_canal', 500);
			$table->string('subdetalle_canal', 500);
			$table->string('socio', 500);
			$table->string('ruc_socio', 100);
			$table->string('entidad', 500);
			$table->string('punto_venta', 500);
			$table->string('rol', 200);

			$table->timestamps();
			$table->softDeletes();
			
			$table->integer('idcarga_archivo_planilla')->index('fk_usuario_planilla_carga_archivo_planilla_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_usuario_planilla_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_usuario_planilla_users2_idx');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usuario_planilla');
	}

}
