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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status');


            // $table->bigInteger('person_id')->unsigned();
            // $table->foreign('person_id')->references('id')->on('persons')->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            // $table->foreignId('title_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');
            // $table->foreignId('seniority_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');

            $table->bigInteger('title_id')->nullable()->unsigned();
            $table->bigInteger('seniority_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            // $table->string('departments')->nullable();
            $table->string('contact_owner')->nullable();
            $table->string('first_phone')->nullable()->index();
            $table->string('work_direct_phone')->nullable()->index();
            $table->string('home_phone')->nullable()->index();
            $table->string('mobile_phone')->nullable()->index();
            $table->string('corporate_phone')->nullable()->index();
            $table->string('other_phone')->nullable()->index();

            $table->timestamp('last_contacted')->nullable();
            $table->string('linkedin')->nullable();


            // $table->string('first_name');
            // $table->string('last_name')->nullable();
            // $table->string('title')->nullable();

            // $table->string('company')->nullable();
            // $table->string('company_name_for_emails')->nullable();
            // $table->string('email')->nullable()->index();
            // $table->string('email_status')->nullable();
            // $table->string('email_confidence')->nullable();

            // $table->string('seniority')->nullable();
            // $table->string('departments')->nullable();
            // $table->string('contact_owner')->nullable();
            // $table->string('first_phone')->nullable()->index();
            // $table->string('work_direct_phone')->nullable()->index();
            // $table->string('home_phone')->nullable()->index();
            // $table->string('mobile_phone')->nullable()->index();
            // $table->string('corporate_phone')->nullable()->index();
            // $table->string('other_phone')->nullable()->index();

            $table->string('stage')->nullable();
            $table->string('lists')->nullable();

            // $table->timestamp('last_contacted')->nullable();
            // $table->integer('employees')->nullable();
            // $table->string('industry')->nullable();
            // $table->longText('keywords')->nullable();
            // $table->string('person_linkedin')->nullable();
            // $table->longText('url')->nullable();
            // $table->string('website')->nullable();
            // $table->longText('company_linkedin_url')->nullable();
            // $table->longText('facebook_url')->nullable();
            // $table->longText('twitter_url')->nullable();
            // $table->string('city')->nullable();
            // $table->string('state')->nullable();
            // $table->string('country')->nullable();
            // $table->string('company_address')->nullable();
            // $table->string('company_city')->nullable();
            // $table->string('company_state')->nullable();
            // $table->string('company_country')->nullable();
            // $table->string('company_phone')->nullable()->index();
            // $table->longText('seo_description')->nullable();
            // $table->longText('technologies')->nullable();
            // $table->decimal('annual_revenue', 15, 2)->nullable();
            // $table->string('total_funding')->nullable();
            // $table->string('latest_funding')->nullable();
            // $table->string('latest_funding_amount')->nullable();
            // $table->timestamp('last_raised_at')->nullable();

            // NOT REQUIRED
            // $table->boolean('email_sent')->nullable();
            // $table->boolean('email_open')->nullable();
            // $table->boolean('email_bounced')->nullable();
            // $table->boolean('replied')->nullable();
            // $table->boolean('demoed')->nullable();
            // NOT REQUIRED END

            // $table->integer('number_of_retail_locations')->nullable();
            $table->string('account_owner')->nullable();
            $table->string('apollo_contact_id')->nullable();
            $table->string('apollo_account_id')->nullable();

            $table->foreignId('city_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('cascade');

            $table->timestamps();



            // $table->bigInteger('seniority_id')->unsigned();
            // $table->foreign('seniority_id')->references('id')->on('technologies')->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign('title_id')->references('id')->on('title')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('seniority_id')->references('id')->on('seniority')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('company_id')->references('id')->on('company')->onUpdate('CASCADE')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
