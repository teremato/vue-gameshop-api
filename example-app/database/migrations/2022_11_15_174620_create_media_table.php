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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->string('media_type');
            $table->string('file_path');
            $table->string('url');
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');

            $table->foreign('publisher_id')
                ->references('id')
                ->on('publishers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
