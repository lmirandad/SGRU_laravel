<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('username', 100);
			$table->string('password', 64);
			$table->string('email', 45);
			$table->string('remember_token', 100)->nullable();
			$table->string('nombre', 100);
			$table->string('apellido_paterno', 200);
			$table->string('apellido_materno', 200);
			$table->dateTime('fecha_nacimiento')->nullable();
			$table->string('genero', 1);
			$table->string('numero_doc_identidad', 20);
			$table->string('telefono', 20)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('idrol')->index('fk_users_rol_idx');
			$table->integer('usuario_bloqueado')->nullable();
			$table->integer('usuario_vena')->nullable();
			$table->integer('idtipo_doc_identidad')->index('fk_users_tipo_doc_identidad1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_users_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_users_users2_idx');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
