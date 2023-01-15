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
        Schema::create('projektas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pavadinimas');
            $table->text('aprasymas');
            $table->bigInteger('projekto_biudzetas');
            $table->date('planuojama_pradzios_data');
            $table->date('planuojama_pabaigos_data');
            $table->date('reali_pradzios_data')->nullable();
            $table->date('reali_pabaigos_data')->nullable();
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
        Schema::dropIfExists('projektas');
    }
};
