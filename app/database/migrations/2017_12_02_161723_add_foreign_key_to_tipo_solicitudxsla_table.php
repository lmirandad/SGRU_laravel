<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTipoSolicitudxslaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tipo_solicitudxsla', function(Blueprint $table)
		{
			$table->foreign('idherramientaxsectorxtipo_solicitud', 'fk_tipo_solicitud_herramientaxsectorxtipo_solicitud')->references('idherramientaxsectorxtipo_solicitud')->on('herramientaxsectorxtipo_solicitud')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idsla', 'fk_tipo_solicitudxsla_sla')->references('idsla')->on('sla')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_tipo_solicitudxsla_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_tipo_solicitudxsla_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tipo_solicitudxsla', function(Blueprint $table)
		{
			$table->dropForeign('fk_tipo_solicitud_herramientaxsectorxtipo_solicitud');
			$table->dropForeign('fk_tipo_solicitudxsla_sla');
			$table->dropForeign('fk_tipo_solicitudxsla_users1');
			$table->dropForeign('fk_tipo_solicitudxsla_users2');
		});
	}

}
