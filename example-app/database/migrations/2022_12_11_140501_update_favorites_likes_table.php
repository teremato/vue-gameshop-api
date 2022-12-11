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
        Schema::table("favorites", function(Blueprint $table) {

            // $table->renameColumn("user_favorite", "user_id");
            // $table->renameColumn("post_favorite", "enitiy_id");
            // $table->string("entity_type");

            // $table->dropForeign("user_favorite");
            // $table->dropForeign("post_favorite");

            // $table->foreign("user_id")
            //     ->references("id")
            //     ->on("users")
            //     ->onDelete("cascade");

            // $table->foreign("enitiy_id")
            //     ->references("id")
            //     ->on("posts")
            //     ->onDelete("cascade");

            $table->foreign("enitiy_id")
                ->references("id")
                ->on("games")
                ->onDelete("cascade");
        });

        Schema::table("likes", function(Blueprint $table) {

            // $table->renameColumn("user_likes", "user_id");
            // $table->renameColumn("post_like", "entity_id");
            // $table->string("entity_type");

            // $table->dropForeign("user_likes");
            // $table->dropForeign("post_like");

            // $table->foreign("user_id")
            //     ->references("id")
            //     ->on("users")
            //     ->onDelete("cascade");

            // $table->foreign("enitiy_id")
            //     ->references("id")
            //     ->on("posts")
            //     ->onDelete("cascade");

            $table->foreign("enitiy_id")
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
        Schema::table("favorites", function(Blueprint $table) {
            $table->renameColumn("user_id", "user_favorite");
            $table->renameColumn("enitiy_id", "post_favorite");
            $table->dropColumn("entity_type");
        });

        Schema::table("likes", function(Blueprint $table) {
            $table->renameColumn("user_id", "user_likes");
            $table->renameColumn("enitiy_id", "post_like");
            $table->dropColumn("entity_type");
        });
    }
};
