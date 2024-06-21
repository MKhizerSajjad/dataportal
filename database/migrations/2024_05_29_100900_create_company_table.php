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
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('email_status')->nullable();
            $table->string('email_confidence')->nullable();
            $table->integer('employees')->nullable();
            $table->bigInteger('industry_id')->nullable()->unsigned();
            $table->string('website')->nullable();
            $table->longText('url')->nullable();
            $table->longText('linkedin')->nullable();
            $table->longText('facebook')->nullable();
            $table->longText('twitter')->nullable();
            $table->string('phone')->nullable()->index();
            $table->integer('retail_locations')->nullable();
            $table->longText('seo_description')->nullable();
            $table->string('address')->nullable();

            $table->foreignId('city_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');

            // $table->bigInteger('city_id')->unsigned();
            // $table->bigInteger('state_id')->unsigned();
            // $table->bigInteger('country_id')->unsigned();
            $table->timestamps();

            $table->foreign('industry_id')->references('id')->on('industry')->onUpdate('CASCADE')->onDelete('CASCADE');
            // $table->foreign('city_id')->references('id')->on('city')->onUpdate('CASCADE')->onDelete('CASCADE');
            // $table->foreign('state_id')->references('id')->on('state')->onUpdate('CASCADE')->onDelete('CASCADE');
            // $table->foreign('country_id')->references('id')->on('country')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
