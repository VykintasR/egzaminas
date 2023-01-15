<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('veikla', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('projekto_id')->constrained('projektas')->cascadeOnDelete();
            $table->string('pavadinimas');
            $table->integer('prioritetas');
            $table->text('aprasymas')->nullable();
            $table->date('planuojama_pradzios_data');
            $table->date('planuojama_pabaigos_data');
            $table->bigInteger('planuojamas_biudzetas');
            $table->date('reali_pradzios_data')->nullable();
            $table->date('reali_pabaigos_data')->nullable();
            $table->bigInteger('realus_biudzetas')->nullable();
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
        Schema::table('veikla', function (Blueprint $table) {
            $table->dropForeign(['projekto_id']);
            $table->dropIfExists();
        });
    }
};
