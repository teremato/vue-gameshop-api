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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publisher_id');
            
            $table->string('title');
            $table->text('description');
            $table->string('main_photo')->nullable();

            $table->integer('price');

            $table->string("slug")->nullable();

            $table->timestamps();

            $table->foreign('publisher_id')
                ->references('id')
                ->on('publishers')
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
        Schema::dropIfExists('games');
    }
};
