<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanalAgrupadoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('canal_agrupado', function(Blueprint $table)
		{
			$table->integer('idcanal_agrupado', true);
			$table->string('nombre',200);
			$table->integer('idsector')->nullable()->index('fk_canal_agrupado_sector_idx');		
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
		Schema::drop('canal_agrupado');
	}

}
