<?php

namespace App\Exports;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Contacts::all();

        // return Contacts::select("id", "name", "email")->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'status',
            'first_name',
            'last_name',
            'title',
            'company',
            'company_name_for_emails',
            'email',
            'email_status',
            'email_confidence',
            'seniority',
            'departments',
            'contact_owner',
            'first_phone',
            'work_direct_phone',
            'home_phone',
            'mobile_phone',
            'corporate_phone',
            'other_phone',
            'stage',
            'lists',
            'last_contacted',
            'account_owner',
            'employees',
            'industry',
            'keywords',
            'person_linkedin',
            'website',
            'company_linkedin_url',
            'facebook_url',
            'twitter_url',
            'city',
            'state',
            'country',
            'company_address',
            'company_city',
            'company_state',
            'company_country',
            'company_phone',
            'seo_description',
            'technologies',
            'annual_revenue',
            'total_funding',
            'latest_funding',
            'latest_funding_amount',
            'last_raised_at',
            'email_sent',
            'email_open',
            'email_bounced',
            'replied',
            'demoed',
            'number_of_retail_locations',
            'apollo_contact_id',
            'apollo_account_id'
        ];

    }
}
