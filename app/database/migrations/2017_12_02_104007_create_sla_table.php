<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sla', function(Blueprint $table)
		{
			$table->integer('idsla', true);
			$table->dateTime('fecha_inicio')->nullable();
			$table->dateTime('fecha_fin')->nullable();
			$table->integer('idherramientaxsector')->index('fk_sla_herramientaxsector_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_sla_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_sla_users2_idx');
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
		Schema::drop('sla');
	}

}
