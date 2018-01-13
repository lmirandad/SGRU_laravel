<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCanalAgrupadoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('canal_agrupado', function(Blueprint $table)
		{
			$table->foreign('idsector', 'fk_canal_agrupado_sector')->references('idsector')->on('sector')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('canal_agrupado', function(Blueprint $table)
		{
			$table->dropForeign('fk_canal_agrupado_sector');
		});
	}

}
