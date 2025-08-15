<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodoAcademicoIdToMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->unsignedBigInteger('periodo_academico_id')->nullable()->after('id');
            $table->foreign('periodo_academico_id')->references('id')->on('periodos_academicos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropForeign(['periodo_academico_id']);
            $table->dropColumn('periodo_academico_id');
        });
    }
}
