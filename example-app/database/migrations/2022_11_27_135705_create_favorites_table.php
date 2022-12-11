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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("user_favorite");
            $table->unsignedInteger("post_favorite");
            $table->timestamps();

            $table->foreign("user_favorite")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->foreign("post_favorite")
                ->references("id")
                ->on("posts")
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
        Schema::dropIfExists('favorites');
    }
};
