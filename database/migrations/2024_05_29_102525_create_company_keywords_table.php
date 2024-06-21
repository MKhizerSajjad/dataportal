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
        Schema::create('company_keywords', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('keyword_id')->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_keywords');
    }
};
