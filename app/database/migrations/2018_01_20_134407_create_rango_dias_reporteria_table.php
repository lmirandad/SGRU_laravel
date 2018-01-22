<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangoDiasReporteriaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rango_dias_reporteria', function(Blueprint $table)
		{
			$table->integer('idrango_dias_reporteria', true);
			$table->string('nombre',200);
			$table->integer('dia_minimo')->nullable();
			$table->integer('dia_maximo')->nullable();
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
		Schema::drop('rango_dias_reporteria');
	}

}
