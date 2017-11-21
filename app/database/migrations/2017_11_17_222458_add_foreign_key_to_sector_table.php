<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sector', function(Blueprint $table)
		{
			$table->foreign('iduser_created_by', 'fk_sector_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('iduser_updated_by', 'fk_sector_users2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sector', function(Blueprint $table)
		{
			$table->dropForeign('fk_sector_users1');
			$table->dropForeign('fk_sector_users2');
		});
	}

}
