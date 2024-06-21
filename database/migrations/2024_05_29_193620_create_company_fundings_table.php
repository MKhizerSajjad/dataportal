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
        Schema::create('company_fundings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('total_funding')->nullable();
            $table->string('latest_funding')->nullable();
            $table->string('latest_funding_amount')->nullable();
            $table->timestamp('last_raised_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_fundings');
    }
};
