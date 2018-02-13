<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnOnUsuarioPlanillaTable extends Migration {

	public function up()
      {
          Schema::table('usuario_planilla', function($table) {
             $table->dropColumn('subdetalle_canal');
             $table->dropColumn('entidad');
          });
      }

      public function down()
      {
          Schema::table('usuario_planilla', function($table) {
             $table->string('subdetalle_canal', 500)->nullable();
             $table->string('entidad', 500)->nullable();
          });
      }

}
