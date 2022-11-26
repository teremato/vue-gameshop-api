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
        // Schema::create('posts', function (Blueprint $table) {
        //     $table->unsignedBigInteger("photo")->change()
        //         ->rename("photo", "photo_id");

        //     $table->dropForeign(["photo"]);

        //     $table->foreign("photo_id")
        //         ->references("id")
        //         ->on("media")
        //         ->onDelete("cascade");
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->string("photo")->nullable();

            $table->dropForeign(["photo"]);

            $table->foreign("photo")
                ->references("id")
                ->on("media")
                ->onDelete("cascade");
        });
    }
};
