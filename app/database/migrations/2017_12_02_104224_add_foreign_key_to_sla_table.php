<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSlaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sla', function(Blueprint $table)
		{
			
			$table->foreign('idherramientaxsector', 'fk_sla_herramientaxsector')->references('idherramientaxsector')->on('herramientaxsector')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_created_by', 'fk_sla_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_sla_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sla', function(Blueprint $table)
		{
			$table->dropForeign('fk_sla_herramientaxsector');
			$table->dropForeign('fk_sla_users1');
			$table->dropForeign('fk_sla_users2');
		});
	}
}
