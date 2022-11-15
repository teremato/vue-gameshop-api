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
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("game_id");
            
            $table->string('OC');
            $table->string("RAM");
            $table->string("processor");
            $table->string("videocard");
            $table->string("memory");
            $table->string("directX");
            $table->timestamps();

            $table->foreign("game_id")
                ->references("id")
                ->on("games")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_settings');
    }
};
