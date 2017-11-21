<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCargaArchivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('carga_archivo', function(Blueprint $table)
		{
			$table->foreign('iduser_registrador', 'fk_carga_archivo_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idestado_carga_archivo', 'fk_carga_archivo_estado_carga_archivo1')->references('idestado_carga_archivo')->on('estado_carga_archivo')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_carga_archivo_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_carga_archivo_users3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('idtipo_carga_archivo', 'fk_carga_archivo_tipo_carga_archivo1')->references('idtipo_carga_archivo')->on('tipo_carga_archivo')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('carga_archivo', function(Blueprint $table)
		{
			$table->dropForeign('fk_carga_archivo_users1');
			$table->dropForeign('fk_carga_archivo_estado_carga_archivo1');
			$table->dropForeign('fk_carga_archivo_users2');
			$table->dropForeign('fk_carga_archivo_users3');
			$table->dropForeign('fk_carga_archivo_tipo_carga_archivo1');
		});
	}

}
