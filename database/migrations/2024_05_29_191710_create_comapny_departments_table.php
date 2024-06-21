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
        Schema::create('comapny_departments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('department_id')->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comapny_departments');
    }
};
