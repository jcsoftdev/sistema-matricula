<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApoderadoIdToEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->unsignedBigInteger('apoderado_id')->nullable();
            $table->foreign('apoderado_id')->references('id')->on('apoderados')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['apoderado_id']);
            $table->dropColumn('apoderado_id');
        });
    }
}
