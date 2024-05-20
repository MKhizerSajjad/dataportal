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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->string('company_name_for_emails')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('email_status')->nullable();
            $table->string('email_confidence')->nullable();
            $table->string('seniority')->nullable();
            $table->string('departments')->nullable();
            $table->string('contact_owner')->nullable();
            $table->string('first_phone')->nullable()->index();
            $table->string('work_direct_phone')->nullable()->index();
            $table->string('home_phone')->nullable()->index();
            $table->string('mobile_phone')->nullable()->index();
            $table->string('corporate_phone')->nullable()->index();
            $table->string('other_phone')->nullable()->index();
            $table->string('stage')->nullable();
            $table->string('lists')->nullable();
            $table->timestamp('last_contacted')->nullable();
            $table->string('account_owner')->nullable();
            $table->integer('employees')->nullable();
            $table->string('industry')->nullable();
            $table->longText('keywords')->nullable();
            $table->string('person_linkedin')->nullable();
            $table->string('url')->nullable();
            $table->string('website')->nullable();
            $table->longText('company_linkedin_url')->nullable();
            $table->longText('facebook_url')->nullable();
            $table->longText('twitter_url')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_phone')->nullable()->index();
            $table->longText('seo_description')->nullable();
            $table->longText('technologies')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('total_funding')->nullable();
            $table->string('latest_funding')->nullable();
            $table->string('latest_funding_amount')->nullable();
            $table->timestamp('last_raised_at')->nullable();
            $table->boolean('email_sent')->nullable();
            $table->boolean('email_open')->nullable();
            $table->boolean('email_bounced')->nullable();
            $table->boolean('replied')->nullable();
            $table->boolean('demoed')->nullable();
            $table->integer('number_of_retail_locations')->nullable();
            $table->string('apollo_contact_id')->nullable();
            $table->string('apollo_account_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
