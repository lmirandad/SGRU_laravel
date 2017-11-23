<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerramientaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramienta', function(Blueprint $table)
		{
			$table->integer('idherramienta', true);
			$table->string('nombre', 100);
			$table->string('descripcion', 100);
			$table->integer('iddenominacion_herramienta')->index('fk_herramienta_denominacion_herramienta_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramienta_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramienta_users2_idx');
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
		Schema::drop('herramienta');
	}

}
