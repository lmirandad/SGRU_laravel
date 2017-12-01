<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntidadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entidad', function(Blueprint $table)
		{
			$table->integer('identidad', true);
			$table->string('nombre', 100);
			$table->string('descripcion', 200)->nullable();
			$table->string('codigo_enve',100)->nullable();
			$table->integer('idcanal')->index('fk_entidad_canal1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_entidad_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_entidad_users2_idx');
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
		Schema::drop('entidad');
	}


}
