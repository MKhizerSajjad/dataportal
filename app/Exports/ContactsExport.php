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

        $filters = $this->filters;

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
        $contacts = $contacts->take(10)->get();


        $transformedData = collect([$this->headings()]);

        $transformedData = $transformedData->merge($contacts->map(function ($contact) {
            return [
                // $contact->status ?? '',
                $contact->first_name ?? '',
                $contact->last_name ?? '',
                $contact->title ?? '',
                $contact->company ?? '',
                $contact->company_name_for_emails ?? '',
                $contact->email ?? '',
                $contact->email_status ?? '',
                $contact->email_confidence ?? '',
                $contact->seniority ?? '',
                $contact->departments ?? '',
                $contact->contact_owner ?? '',
                $contact->first_phone ?? '',
                $contact->work_direct_phone ?? '',
                $contact->home_phone ?? '',
                $contact->mobile_phone ?? '',
                $contact->corporate_phone ?? '',
                $contact->other_phone ?? '',
                $contact->stage ?? '',
                $contact->lists ?? '',
                $contact->last_contacted ?? '',
                $contact->account_owner ?? '',
                $contact->employees ?? '',
                $contact->industry ?? '',
                $contact->keywords ?? '',
                $contact->person_linkedin ?? '',
                $contact->website ?? '',
                $contact->company_linkedin_url ?? '',
                $contact->facebook_url ?? '',
                $contact->twitter_url ?? '',
                $contact->city ?? '',
                $contact->state ?? '',
                $contact->country ?? '',
                $contact->company_address ?? '',
                $contact->company_city ?? '',
                $contact->company_state ?? '',
                $contact->company_country ?? '',
                $contact->company_phone ?? '',
                $contact->seo_description ?? '',
                $contact->technologies ?? '',
                $contact->annual_revenue ?? '',
                $contact->total_funding ?? '',
                $contact->latest_funding ?? '',
                $contact->latest_funding_amount ?? '',
                $contact->last_raised_at ?? '',
                $contact->email_sent ?? '',
                $contact->email_open ?? '',
                $contact->email_bounced ?? '',
                $contact->replied ?? '',
                $contact->demoed ?? '',
                $contact->number_of_retail_locations ?? '',
                $contact->apollo_contact_id ?? '',
                $contact->apollo_account_id ?? ''
            ];
        }));

        return $transformedData;

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

    // public function chunkSize(): int
    // {
    //     return 1000; // Set your desired chunk size
    // }
}
