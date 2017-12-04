<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoSolicitudxslaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tipo_solicitudxsla', function(Blueprint $table)
		{
			$table->integer('idtipo_solicitudxsla', true);
			$table->integer('sla_pendiente')->nullable();
			$table->integer('sla_procesando')->nullable();
			$table->integer('idherramientaxsectorxtipo_solicitud')->index('fk_tipo_solicitudxsla_herramientaxsectorxtipo_solicitud_idx');
			$table->integer('idsla')->index('fk_tipo_solicitudxsla_sla_idx');
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
		Schema::drop('tipo_solicitudxsla');
	}

}
