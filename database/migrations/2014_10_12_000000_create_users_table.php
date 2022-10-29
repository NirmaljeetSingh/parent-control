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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->text('location')->nullable();
            $table->text('fav_location')->nullable();
            $table->longText('bio')->nullable();
            $table->string('phone_no')->unique();
            $table->string('email')->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_no_verified_at')->nullable();
            $table->string('password')->default('');
            $table->string('otp')->default('');
            $table->string('image')->default('');
            $table->string('parent_key')->default('');
            $table->enum('account_type',['parent','children'])->default('parent');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
