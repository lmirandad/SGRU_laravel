<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDenominacionHerramientaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('denominacion_herramienta', function(Blueprint $table)
		{
			$table->integer('iddenominacion_herramienta', true);
			$table->string('nombre', 100);
			$table->integer('flag_seguridad')->nullable();
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
		Schema::drop('denominacion_herramienta');
	}

}
