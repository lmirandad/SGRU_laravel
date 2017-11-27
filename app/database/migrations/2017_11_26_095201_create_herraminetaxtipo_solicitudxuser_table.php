<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHerraminetaxtipoSolicitudxuserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('herramientaxtipo_solicitudxuser', function(Blueprint $table)
		{
			$table->integer('idherramientaxtipo_solicitudxuser', true);
			$table->integer('idherramientaxtipo_solicitud')->index('fk_herramientaxtipo_solicitudxuser_herramientaxtipo_solicitud_idx');
			$table->integer('iduser')->index('fk_herramientaxtipo_solicitudxuser_users1_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_herramientaxtipo_solicitudxuser_users2_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_herramientaxtipo_solicitudxuser_users3_idx');
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
		Schema::drop('herramientaxtipo_solicitudxuser');
	}

}
