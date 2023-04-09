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
        Schema::create('blocked_unblock_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blocked_by_user_id')->default(0);
            $table->bigInteger('blocked_user_id')->default(0);
            $table->string('reason')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocked_unblock_users');
    }
};
