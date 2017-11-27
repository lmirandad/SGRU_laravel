<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersxsectorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usersxsector', function(Blueprint $table)
		{
			$table->integer('idusersxsector', true);
			$table->integer('iduser')->index('fk_usersxsector_users_idx');
			$table->integer('idsector')->index('fk_usersxsector_sector_idx');
			$table->integer('iduser_created_by')->nullable()->index('fk_usersxsector_users1_idx');
			$table->integer('iduser_updated_by')->nullable()->index('fk_usersxsector_users2_idx');
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
		Schema::drop('usersxsector');
	}

}
