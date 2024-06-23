<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use App\Models\Contacts;
use App\Imports\CustomException;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use App\Models\Company;
use App\Models\Seniority;
use App\Models\Title;
use App\Models\Contact;
use App\Models\Technologies;
use App\Models\ComapnyTechnologies;
use App\Models\Keywords;
use App\Models\ComapnyKeywords;
use App\Models\Departments;
use App\Models\CompanyDepartments;
use App\Models\CompanyFundings;
use App\Models\Industry;

use Illuminate\Console\Command;

class DataParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:data-parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $comapnyCountryId = null;
        $comapnyStateId = null;
        $comapnyCityId = null;
        $countryId = null;
        $stateId = null;
        $cityId = null;
        $industryId = null;
        $companyId = null;
        $seniorityId = null;
        $titleId = null;
        $contactId = null;

        $contacts = Contacts::orderBy('id')->get();


        foreach($contacts as $index => $contact)
        {
            if(isset($contact['country']) && $contact['country'] != null) {
                $country = Countries::updateOrCreate(
                    ['name' => $contact['country']],
                    ['name' => $contact['country']]
                );
                $countryId = $country->id;
            }

            if(isset($contact['state']) && $contact['state'] != null) {
                $state = States::updateOrCreate(
                    ['name' => $contact['state']],
                    [
                        'name' => $contact['state'],
                        'country_id' => $countryId
                    ]
                );
                $stateId = $state->id;
            }

            if(isset($contact['city']) && $contact['city'] != null) {
                $city = Cities::updateOrCreate(
                    ['name' => $contact['city']],
                    [
                        'name' => $contact['city'],
                        // 'state_code' => $state->code ?? 0,
                        'state_id' => $stateId,
                        'country_id' => $countryId
                    ]
                );
                $cityId = $city->id;
            }

            // Company

            if(isset($contact['company_country']) && $contact['company_country'] != null) {
                $comapnyCountry = Countries::updateOrCreate(
                    ['name' => $contact['company_country']],
                    ['name' => $contact['company_country']]
                );
                $comapnyCountryId = $comapnyCountry->id;
            }

            if(isset($contact['company_state']) && $contact['company_state'] != null) {
                $comapnyState = States::updateOrCreate(
                    ['name' => $contact['company_state']],
                    [
                        'name' => $contact['company_state'],
                        'country_id' => $comapnyCountry->id
                    ]
                );
                $comapnyStateId = $comapnyState->id;
            }


            if(isset($contact['company_city']) && $contact['company_city'] != null) {
                $comapnyCity = Cities::updateOrCreate(
                    ['name' => $contact['company_city']],
                    [
                        'name' => $contact['company_city'],
                        // 'state_code' => $state->code ?? 0,
                        'state_id' => $comapnyState->id,
                        'country_id' => $comapnyCountry->id
                    ]
                );
                $comapnyCityId = $comapnyCity->id;
            }

            $companyAddress = $contact['company_address'];

            if(isset($contact['industry']) && $contact['industry'] != null) {
                $industry = industry::updateOrCreate(
                    ['name' => $contact['industry']],
                    ['name' => $contact['industry']]
                );
            }

            $company = Company::updateOrCreate(
                [
                    'name' => $contact['company'],
                    'email' => $contact['email'],
                ],
                [
                    'name' => $contact['company'],
                    'email_name' => $contact['company_name_for_emails'],
                    'email'                 => $contact['email'],
                    'email_status'          => $contact['email_status'],
                    'email_confidence'      => $contact['email_confidence'],
                    'employees' => $contact['employees'],
                    'industry_id' => $industry->id ?? null,
                    'website' => $contact['website'],
                    'linkedin' => $contact['company_linkedin_url'],
                    'facebook' => $contact['facebook_url'],
                    'twitter' => $contact['twitter_url'],
                    'phone' => $contact['company_phone'],
                    'retail_locations' => $contact['number_of_retail_locations'],
                    'seo_description' => $contact['seo_description'],
                    'address' => $contact['company_address'],
                    'city_id' => $comapnyCityId ?? null,
                    'state_id' => $comapnyStateId ?? null,
                    'country_id' => $comapnyCountryId ?? null,
                ]
            );
            $companyId = $company->id;

            if(isset($contact['seniority']) && $contact['seniority'] != null) {
                $seniority = Seniority::updateOrCreate(
                    ['name' => $contact['seniority']],
                    ['name' => $contact['seniority']]
                );
                $seniorityId = $seniority->id;
            }

            if(isset($contact['title']) && $contact['title'] != null) {
                $title = Title::updateOrCreate(
                    ['name' => $contact['title']],
                    ['name' => $contact['title']]
                );
                $titleId = $title->id;
            }

            $savedContact = Contact::updateOrCreate(
                [
                    'first_name' => $contact['first_name'],
                    // 'email' => $contact[5],
                    'mobile_phone' => $contact['mobile_phone']
                ],
                [
                    'status' => 1,
                    'first_name' => $contact['first_name'] ?? '***',
                    'last_name' => $contact['last_name'] ?? 'NULL',
                    'title_id' => $title->id ?? null,
                    'seniority_id' => $seniority->id ?? null,
                    'contact_owner'         => $contact['contact_owner'],
                    'first_phone'           => $contact['first_phone'],
                    'work_direct_phone'     => $contact['work_direct_phone'],
                    'home_phone'            => $contact['mobile_phone'],
                    'mobile_phone'          => $contact['mobile_phone'],
                    'corporate_phone'       => $contact['corporate_phone'],
                    'other_phone'           => $contact['other_phone'],
                    'last_contacted'        => $contact['last_contacted'],
                    'linkedin'              => $contact['person_linkedin'],
                    'stage'                 => $contact['stage'],
                    'lists'                 => $contact['lists'],
                    'account_owner'         => $contact['account_owner'],
                    'apollo_contact_id'     => $contact['apollo_contact_id'],
                    'apollo_account_id'     => $contact['apollo_account_id'],
                    'city_id' => $cityId ?? null,
                    'state_id' => $stateId ?? null,
                    'country_id' => $countryId ?? null,
                ]
            );
            $contactId = $contact->id;

            if(isset($contact['technologies']) && $contact['technologies'] != null) {
                // Split the comma-separated technologies
                $technologies = explode(',', $contact['technologies']);

                foreach ($technologies as $tech) {
                    $tech = trim($tech); // Remove any surrounding whitespace
                    if (!empty($tech)) {
                        $technology = Technologies::updateOrCreate(
                            ['name' => $tech],
                            ['name' => $tech]
                        );
                        // Associate the technology with the contact
                        $company->technologies()->syncWithoutDetaching($technology->id);
                    }
                }
            }

            if(isset($contact['keywords']) && $contact['keywords'] != null) {
                // Split the comma-separated Keywords
                $keywords = explode(',', $contact['keywords']);

                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword); // Remove any surrounding whitespace
                    if (!empty($keyword)) {
                        $word = Keywords::updateOrCreate(
                            ['word' => $keyword],
                            ['word' => $keyword]
                        );
                        // Associate the word with the contact
                        $company->keywords()->syncWithoutDetaching($word->id);
                    }
                }
            }

            if(isset($contact['departments']) && $contact['departments'] != null) {
                // Split the comma-separated Department
                $departments = explode(',', $contact['departments']);

                foreach ($departments as $department) {
                    $department = trim($department); // Remove any surrounding whitespace
                    if (!empty($department)) {
                        $dept = Departments::updateOrCreate(
                            ['name' => $department],
                            ['name' => $department]
                        );

                        // Associate the word with the contact
                        $company->departments()->syncWithoutDetaching($dept->id);
                    }
                }
            }

            $date = null;
            // $date = $this->validateAndReformatDate($row[43]);
            $contact = CompanyFundings::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'latest_funding' => $contact['latest_funding'],
                    'latest_funding_amount' => $contact['latest_funding_amount'],
                    'last_raised_at' => $date,
                ],
                [
                    'company_id'            => $companyId,
                    'annual_revenue'        => is_int($contact['annual_revenue']) ? $contact['annual_revenue'] : null,
                    'total_funding'         => $contact['total_funding'],
                    'latest_funding'        => $contact['latest_funding'],
                    'latest_funding_amount' => $contact['latest_funding_amount'],
                    'last_raised_at'        => $date,
                ]
            );
        }
    }
}
