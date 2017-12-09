<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaEquivalenciaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramienta_equivalencia', function(Blueprint $table)
		{
			$table->integer('idherramienta_equivalencia', true);
			$table->string('nombre_equivalencia', 100);
			$table->integer('idherramienta')->nullable()->index('fk_herramienta_equivalencia_herramienta_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramienta_equivalencia_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramienta_equivalencia_users2_idx');
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
		Schema::drop('herramienta_equivalencia');
	}

}
