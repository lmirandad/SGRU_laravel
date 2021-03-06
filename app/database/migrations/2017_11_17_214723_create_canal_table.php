<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('canal', function(Blueprint $table)
		{
			$table->integer('idcanal', true);
			$table->string('nombre', 100);
			$table->string('descripcion', 200)->nullable();
			$table->integer('idsector')->index('fk_canal_sector1_idx');
			$table->integer('idcanal_agrupado')->nullable()->index('fk_canal_canal_agrupado_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_canal_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_canal_users2_idx');			
			$table->integer('idusuario_responsable')->nullable()->index('fk_canal_users3_idx');
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
		Schema::drop('canal');
	}

}
