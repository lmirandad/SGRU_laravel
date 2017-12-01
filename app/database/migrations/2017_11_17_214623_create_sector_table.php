<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sector', function(Blueprint $table)
		{
			$table->integer('idsector', true);
			$table->string('nombre', 100);
			$table->string('descripcion', 200)->nullable();
			$table->integer('iduser_created_by')->nullable()->index('fk_sector_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_sector_users2_idx');
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
		Schema::drop('sector');
	}

}
