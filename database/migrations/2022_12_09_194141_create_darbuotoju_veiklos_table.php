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
        Schema::create('darbuotoju_veiklos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('darbuotojo_id')->nullable()->constrained('darbuotojas')->cascadeOnDelete();
            $table->foreignId('veiklos_id')->constrained('veikla')->cascadeOnDelete();
            $table->dateTime('paskyrimo_data');
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
        Schema::table('darbuotoju_veiklos', function (Blueprint $table) {
            $table->dropForeign(['darbuotojo_id']);
            $table->dropForeign(['veiklos_id']);
            $table->dropIfExists();
        });
    }
};
