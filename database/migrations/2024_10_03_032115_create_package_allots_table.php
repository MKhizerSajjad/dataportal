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
        Schema::create('package_allots', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->integer('limit')->unsigned();
            $table->foreignId('package_id')->references('id')->on('packages')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_allots');
    }
};
