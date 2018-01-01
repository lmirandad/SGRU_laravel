<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilAplicativoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('perfil_aplicativo', function(Blueprint $table)
		{
			$table->integer('idperfil_aplicativo', true);
			$table->string('nombre',200);	
			$table->integer('idherramienta')->nullable()->index('fk_perfil_aplicativo_herramienta_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_perfil_aplicativo_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_perfil_aplicativo_users2_idx');
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
		Schema::drop('perfil_aplicativo');
	}


}
