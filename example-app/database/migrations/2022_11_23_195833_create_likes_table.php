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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("user_likes");
            $table->unsignedInteger("post_like");
            $table->string("type")->nullable();
            $table->timestamps();

            $table->foreign("user_likes")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->foreign("post_like")
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
        Schema::dropIfExists('likes');
    }
};
