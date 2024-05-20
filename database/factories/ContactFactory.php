<?php

// namespace Database\Factories;

// use Illuminate\Database\Eloquent\Factories\Factory;

// /**
//  * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
//  */
// class ContactFactory extends Factory
// {
//     /**
//      * Define the model's default state.
//      *
//      * @return array<string, mixed>
//      */
//     public function definition(): array
//     {
//         return [
//             //
//         ];
//     }
// }

use Faker\Generator as Faker;

$factory->define(App\Models\Contacts::class, function (Faker $faker) {
    return [
        'status' => $faker->numberBetween(0, 1),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'title' => $faker->jobTitle,
        'company' => $faker->company,
        'company_name_for_emails' => $faker->company,
        'email' => $faker->email,
        'email_status' => $faker->randomElement(['valid', 'invalid', 'unknown']),
        'email_confidence' => $faker->randomFloat(2, 0, 100),
        'seniority' => $faker->jobTitle,
        'departments' => $faker->randomElement(['HR', 'Marketing', 'Sales', 'Finance']),
        'contact_owner' => $faker->name,
        'first_phone' => $faker->phoneNumber,
        'work_direct_phone' => $faker->phoneNumber,
        'home_phone' => $faker->phoneNumber,
        'mobile_phone' => $faker->phoneNumber,
        'corporate_phone' => $faker->phoneNumber,
        'other_phone' => $faker->phoneNumber,
        'stage' => $faker->randomElement(['Lead', 'Prospect', 'Customer']),
        'lists' => $faker->randomElement(['List 1', 'List 2', 'List 3']),
        'last_contacted' => $faker->dateTimeThisYear(),
        'account_owner' => $faker->name,
        'employees' => $faker->numberBetween(1, 1000),
        'industry' => $faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Retail']),
        'keywords' => $faker->words(3, true),
        'person_linkedin' => $faker->url,
        'url' => $faker->url,
        'website' => $faker->url,
        'company_linkedin_url' => $faker->url,
        'facebook_url' => $faker->url,
        'twitter_url' => $faker->url,
        'city' => $faker->city,
        'state' => $faker->state,
        'country' => $faker->country,
        'company_address' => $faker->address,
        'company_city' => $faker->city,
        'company_state' => $faker->state,
        'company_country' => $faker->country,
        'company_phone' => $faker->phoneNumber,
        'seo_description' => $faker->sentence,
        'technologies' => $faker->words(3, true),
        'annual_revenue' => $faker->randomNumber(6),
        'total_funding' => $faker->randomNumber(6),
        'latest_funding' => $faker->dateTimeThisYear(),
        'latest_funding_amount' => $faker->randomNumber(6),
        'last_raised_at' => $faker->dateTimeThisYear(),
        'email_sent' => $faker->boolean,
        'email_open' => $faker->boolean,
        'email_bounced' => $faker->boolean,
        'replied' => $faker->boolean,
        'demoed' => $faker->boolean,
        'number_of_retail_locations' => $faker->numberBetween(1, 100),
        'apollo_contact_id' => $faker->uuid,
        'apollo_account_id' => $faker->uuid,
    ];
});

