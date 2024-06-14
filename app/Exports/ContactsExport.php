<?php

namespace App\Exports;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactsExport implements FromCollection
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $contacts = Contacts::query();
        if(isset($filters)){
            $fromEmployees = 0;
            $toEmployees = 1000000;
            $fromRevenue = 0;
            $toRevenue = 10000000000;
            $fromFunding = 0;
            $toFunding = 10000000000;
            foreach($filters as $key => $filter){
                if( $key == 'name' && $filter != null ){
                    $contacts =  $contacts
                        ->orWhere('first_name', 'LIKE', '%'.$filter.'%')
                        ->orWhere('last_name', 'LIKE', '%'.$filter.'%');
                }
                if( $key == 'title' && $filter != null ){
                    $contacts =  $contacts->orWhereIn('title', $filter);
                }
                if($key == 'seniority' && $filter != null){
                    $contacts = $contacts->orWhereIn('seniority', $filter);
                }
                if($key == 'department' && $filter != null){
                    $contacts = $contacts->orWhereIn('departments', $filter);
                }
                if($key == 'company' && $filter != null){
                    $contacts = $contacts->orWhereIn('company', $filter);
                }
                if($key == 'exclude_company' && $filter != null){
                    $contacts = $contacts->orWhereNotIn('company', $filter);
                }
                if($key == 'company_city' && $filter != null){
                    $contacts = $contacts->orWhereIn('company_city', $filter);
                }
                if($key == 'company_state' && $filter != null){
                    $contacts = $contacts->orWhereIn('company_state', $filter);
                }
                if($key == 'company_country' && $filter != null){
                    $contacts = $contacts->orWhereIn('company_country', $filter);
                }
                if($key == 'city' && $filter != null){
                    $contacts = $contacts->orWhereIn('city', $filter);
                }
                if($key == 'state' && $filter != null){
                    $contacts = $contacts->orWhereIn('state', $filter);
                }
                if($key == 'country' && $filter != null){
                    $contacts = $contacts->orWhereIn('country', $filter);
                }
                if($key == 'from_employees' && $filter != null) {
                    $fromEmployees = $filter;
                }
                if($key == 'to_employees' && $filter != null) {
                    $toEmployees = $filter;
                }
                if($key == 'industry' && $filter != null){
                    $contacts = $contacts->orWhereIn('industry', $filter);
                }
                if($key == 'keywords' && $filter != null){
                    // VALIDATE THIS
                    $contacts = $contacts->orWhereIn('keywords', $filter);
                }
                if($key == 'technologies' && $filter != null){
                    // VALIDATE THIS
                    $contacts = $contacts->orWhereIn('technologies', $filter);
                }
                if($key == 'from_revenue' && $filter != null) {
                    $fromRevenue = $filter;
                }
                if($key == 'to_revenue' && $filter != null) {
                    $toRevenue = $filter;
                }
                if($key == 'from_funding' && $filter != null) {
                    $fromFunding = $filter;
                }
                if($key == 'to_funding' && $filter != null) {
                    $toFunding = $filter;
                }
                if($key == 'email_status' && $filter != null){
                    $contacts = $contacts->orWhereIn('email_status', $filter);
                }
            }
            $contacts = $contacts->orWhereBetween('employees', [$fromEmployees, $toEmployees]);
            $contacts = $contacts->orWhereBetween('annual_revenue', [$fromRevenue, $toRevenue]);
            $contacts = $contacts->orWhereBetween('latest_funding', [$fromFunding, $toFunding]);
        }
        $contacts = $contacts->limit(25000)->get();

        $transformedData = collect([$this->headings()]);
        // Transform data to replace IDs with actual values
        $transformedData = $transformedData->merge($contacts->map(function ($item) {
            return [
                // 'date' => $item->date,
                // 'status' => $item->status,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'title' => $item->title,
                'company' => $item->company,
                'company_name_for_emails' => $item->company_name_for_emails,
                'email' => $item->email,
                'email_status' => $item->email_status,
                'email_confidence' => $item->email_confidence,
                'seniority' => $item->seniority,
                'departments' => $item->departments,
                'contact_owner' => $item->contact_owner,
                'first_phone' => $item->first_phone,
                'work_direct_phone' => $item->work_direct_phone,
                'home_phone' => $item->home_phone,
                'mobile_phone' => $item->mobile_phone,
                'corporate_phone' => $item->corporate_phone,
                'other_phone' => $item->other_phone,
                'stage' => $item->stage,
                'lists' => $item->lists,
                'last_contacted' => $item->last_contacted,
                'account_owner' => $item->account_owner,
                'employees' => $item->employees,
                'industry' => $item->industry,
                'keywords' => $item->keywords,
                'person_linkedin' => $item->person_linkedin,
                'website' => $item->website,
                'company_linkedin_url' => $item->company_linkedin_url,
                'facebook_url' => $item->facebook_url,
                'twitter_url' => $item->twitter_url,
                'city' => $item->city,
                'state' => $item->state,
                'country' => $item->country,
                'company_address' => $item->company_address,
                'company_city' => $item->company_city,
                'company_state' => $item->company_state,
                'company_country' => $item->company_country,
                'company_phone' => $item->company_phone,
                'seo_description' => $item->seo_description,
                'technologies' => $item->technologies,
                'annual_revenue' => $item->annual_revenue,
                'total_funding' => $item->total_funding,
                'latest_funding' => $item->latest_funding,
                'latest_funding_amount' => $item->latest_funding_amount,
                // 'last_raised_at' => $item->last_raised_at,
                // 'email_sent' => $item->email_sent,
                // 'email_open' => $item->email_open,
                // 'email_bounced' => $item->email_bounced,
                // 'replied' => $item->replied,
                // 'demoed' => $item->demoed,
                // 'number_of_retail_locations' => $item->number_of_retail_locations,
                // 'apollo_contact_id' => $item->apollo_contact_id,
                // 'apollo_account_id' => $item->apollo_account_id,
            ];
        }));

        return $transformedData;
        // return $contacts->get()->first();

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            // 'date',
            // 'status',
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
            // 'last_raised_at',
            // 'email_sent',
            // 'email_open',
            // 'email_bounced',
            // 'replied',
            // 'demoed',
            // 'number_of_retail_locations',
            // 'apollo_contact_id',
            // 'apollo_account_id'
        ];

    }

    // public function chunkSize(): int
    // {
    //     return 1000; // Set your desired chunk size
    // }
}
