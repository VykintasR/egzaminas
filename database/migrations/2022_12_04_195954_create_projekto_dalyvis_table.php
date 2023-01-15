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
        Schema::create('projekto_dalyvis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('projekto_id')->constrained('projektas')->cascadeOnDelete();
            $table->foreignId('darbuotojo_id')->nullable()->constrained('darbuotojas')->cascadeOnDelete();
            $table->foreignId('roles_id')->constrained('role');
            $table->string('pavadinimas');
            $table->date('dalyvavimo_pradzios_data');
            $table->date('dalyvavimo_pabaigos_data');
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
        Schema::table('projekto_dalyvis', function (Blueprint $table) {
            $table->dropForeign(['projekto_id']);
            $table->dropForeign(['darbuotojo_id']);
            $table->dropForeign(['roles_id']);
            $table->dropIfExists();
        });
    }
};
