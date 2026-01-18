<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('role')->default('user');
            // $table->string('first_address')->nullable();
            // $table->string('second_address')->nullable();
            // $table->string('postcode')->nullable();
            // $table->unsignedBigInteger('city')->nullable();
            // $table->unsignedBigInteger('state')->nullable();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'username', 'role', 'first_address', 'second_address', 'postcode', 'city', 'state']);
        });
    }
};
