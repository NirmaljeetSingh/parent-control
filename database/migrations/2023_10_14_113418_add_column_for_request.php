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
        Schema::table('friend_requests', function (Blueprint $table) {
            $table->bigInteger('parent_id')->comment('This is is only for friend request table this column belongsTo id column')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friend_requests', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};
