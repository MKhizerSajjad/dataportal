<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groupOptions = [
            'title' => Contacts::whereNotNull('title')->distinct()->pluck('title'),
            'company' => Contacts::whereNotNull('company')->distinct()->pluck('company'),
            'email_status' => Contacts::whereNotNull('email_status')->distinct()->pluck('email_status'),
            'seniority' => Contacts::whereNotNull('seniority')->distinct()->pluck('seniority'),
            'departments' => Contacts::whereNotNull('departments')->distinct()->pluck('departments'),
            'contact_owner' => Contacts::whereNotNull('contact_owner')->distinct()->pluck('contact_owner'),
            'stage' => Contacts::whereNotNull('stage')->distinct()->pluck('stage'),
            'employees' => Contacts::whereNotNull('employees')->distinct()->pluck('employees'),
            'industry' => Contacts::whereNotNull('industry')->distinct()->pluck('industry'),
            'keywords' => Contacts::whereNotNull('keywords')->distinct()->pluck('keywords'),
            'city' => Contacts::whereNotNull('city')->distinct()->pluck('city'),
            'state' => Contacts::whereNotNull('state')->distinct()->pluck('state'),
            'country' => Contacts::whereNotNull('country')->distinct()->pluck('country'),
            'company_city' => Contacts::whereNotNull('company_city')->distinct()->pluck('company_city'),
            'company_state' => Contacts::whereNotNull('company_state')->distinct()->pluck('company_state'),
            'company_country' => Contacts::whereNotNull('company_country')->distinct()->pluck('company_country'),
            'annual_revenue' => Contacts::whereNotNull('annual_revenue')->distinct()->pluck('annual_revenue'),
            'latest_funding_type' => Contacts::whereNotNull('latest_funding')->distinct()->pluck('latest_funding'),
            'technologies' => Contacts::whereNotNull('technologies')->distinct()->pluck('technologies'),
        ];

        $technologiesList = [];
        foreach ($groupOptions['technologies'] as $row) {
            $technologies = explode(', ', $row);

            $technologiesList = array_merge($technologiesList, $technologies);
        }
        $uniqueTechnologies = array_unique($technologiesList);
        $groupOptions['technologies'] = $uniqueTechnologies;

        // $filters = $request->except('_token', 'page', 'employees', 'annual_revenue', 'total_funding', 'companies', 'titles', 'seniorities', 'email_statuses', 'departments');
        // $rangeFilters = $request->only('employees', 'annual_revenue', 'total_funding');

        $data = Contacts::query();

        if(isset($request->industry)) {
            $data = $data->orWhereIn('industry', $request->industry);
        }

        // if(isset($request->company)) {
        //     if(isset($request->exclude_companies)) {
        //         $data = $data->orWhereNotIn('company', $request->company);
        //     } else {
        //         $data = $data->orWhereIn('company', $request->company);
        //     }
        // }

        if(isset($request->company)) {
            $data = $data->orWhereIn('company', $request->company);
        }

        if(isset($request->exclude_company)) {
            $data = $data->orWhereNotIn('company', $request->exclude_company);
        }

        if(isset($request->departments)) {
            $data = $data->orWhereIn('departments', $request->departments);
        }

        if(isset($request->from_employees) || isset($request->to_employees)) {
            $data = $data->orWhereBetween('employees', [$request->from_employees, $request->to_employees]);
        }

        if(isset($request->from_revenue) || isset($request->to_revenue)) {
            $data = $data->orWhereBetween('employees', [$request->from_revenue, $request->to_revenue]);
        }

        if(isset($request->from_funding) || isset($request->to_funding)) {
            $data = $data->orWhereBetween('employees', [$request->from_funding, $request->to_funding]);
        }

        if(isset($request->title)) {
            $data = $data->orWhereIn('title', $request->title);
        }

        if(isset($request->seniority)) {
            $data = $data->orWhereIn('seniority', $request->seniority);
        }

        if(isset($request->email_status)) {
            $data = $data->orWhereIn('email_status', $request->email_status);
        }

        if(isset($request->city)) {
            $data = $data->orWhereIn('city', $request->city);
        }

        if(isset($request->state)) {
            $data = $data->orWhereIn('state', $request->state);
        }

        if(isset($request->country)) {
            $data = $data->orWhereIn('country', $request->country);
        }

        if(isset($request->company_city)) {
            $data = $data->orWhereIn('company_city', $request->company_city);
        }

        if(isset($request->company_state)) {
            $data = $data->orWhereIn('company_state', $request->company_state);
        }

        if(isset($request->company_country)) {
            $data = $data->orWhereIn('company_country', $request->company_country);
        }

        if(isset($request->technologies)) {
            $data = $data->orWhereIn('technologies', $request->technologies);
        }

        if(isset($request->latest_funding_type)) {
            $data = $data->orWhereIn('latest_funding', $request->latest_funding_type);
        }

        if(isset($request->name)) {
            $data = $data->orWhere('first_name', 'LIKE', '%'.$request->name.'%')
                ->orWhere('last_name', 'LIKE', '%'.$request->name.'%');
        }

        if(isset($request->keywords)) {
            $data = $data->orWhereIn('keywords', explode($request->keywords, ','));
        }


        // if(isset($filters)) {

        //     // Apply filters dynamically
        //     foreach ($filters as $field => $value) {
        //         // Skip empty values
        //         if ($value !== null && $value !== '') {
        //             $data = $data->orWhere($field, 'LIKE', '%'.$value.'%');
        //         }
        //     }
        // }

        // if(isset($rangeFilters)) {

        // }

        $data = $data->orderBy('company')->orderBy('first_name')->orderBy('last_name')->paginate(50);

        return view('contacts.index',compact('data', 'groupOptions'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'title' => 'nullable',
            'email' => 'nullable|email',
            'mobile_phone' => 'nullable',
        ]);

        $data = [
            'status' => isset($request->status) ? $request->status : 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'title' => $request->title,
            'company' => $request->company,
            'company_name_for_emails' => $request->company_name_for_emails,
            'email' => $request->email,
            'email_status' => $request->email_status,
            'email_confidence' => $request->email_confidence,
            'seniority' => $request->seniority,
            'departments' => $request->departments,
            'contact_owner' => $request->contact_owner,
            'first_phone' => $request->first_phone,
            'work_direct_phone' => $request->work_direct_phone,
            'home_phone' => $request->home_phone,
            'mobile_phone' => $request->mobile_phone,
            'corporate_phone' => $request->corporate_phone,
            'other_phone' => $request->other_phone,
            'stage' => $request->stage,
            'lists' => $request->lists,
            'last_contacted' => $request->last_contacted,
            'account_owner' => $request->account_owner,
            'employees' => $request->employees,
            'industry' => $request->industry,
            'keywords' => $request->keywords,
            'person_linkedin' => $request->person_linkedin,
            'url' => $request->url,
            'website' => $request->website,
            'company_linkedin_url' => $request->company_linkedin_url,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'company_address' => $request->company_address,
            'company_city' => $request->company_city,
            'company_state' => $request->company_state,
            'company_country' => $request->company_country,
            'company_phone' => $request->company_phone,
            'seo_description' => $request->seo_description,
            'technologies' => $request->technologies,
            'annual_revenue' => $request->annual_revenue,
            'total_funding' => $request->total_funding,
            'latest_funding' => $request->latest_funding,
            'latest_funding_amount' => $request->latest_funding_amount,
            'last_raised_at' => $request->last_raised_at,
            'email_sent' => $request->email_sent,
            'email_open' => $request->email_open,
            'email_bounced' => $request->email_bounced,
            'replied' => $request->replied,
            'demoed' => $request->demoed,
            'number_of_retail_locations' => $request->number_of_retail_locations,
            'apollo_contact_id' => $request->apollo_contact_id,
            'apollo_account_id' => $request->apollo_account_id,
        ];

        Contacts::create($data);

        return redirect()->route('contacts.index')->with('success','Record created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacts $contacts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacts $contacts)
    {
        return view('contacts.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacts $contacts)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'title' => 'nullable',
            'email' => 'nullable|email',
            'mobile_phone' => 'nullable',
        ]);

        $data = [
            'status' => isset($request->status) ? $request->status : 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'title' => $request->title,
            'company' => $request->company,
            'company_name_for_emails' => $request->company_name_for_emails,
            'email' => $request->email,
            'email_status' => $request->email_status,
            'email_confidence' => $request->email_confidence,
            'seniority' => $request->seniority,
            'departments' => $request->departments,
            'contact_owner' => $request->contact_owner,
            'first_phone' => $request->first_phone,
            'work_direct_phone' => $request->work_direct_phone,
            'home_phone' => $request->home_phone,
            'mobile_phone' => $request->mobile_phone,
            'corporate_phone' => $request->corporate_phone,
            'other_phone' => $request->other_phone,
            'stage' => $request->stage,
            'lists' => $request->lists,
            'last_contacted' => $request->last_contacted,
            'account_owner' => $request->account_owner,
            'employees' => $request->employees,
            'industry' => $request->industry,
            'keywords' => $request->keywords,
            'person_linkedin' => $request->person_linkedin,
            'url' => $request->url,
            'website' => $request->website,
            'company_linkedin_url' => $request->company_linkedin_url,
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'company_address' => $request->company_address,
            'company_city' => $request->company_city,
            'company_state' => $request->company_state,
            'company_country' => $request->company_country,
            'company_phone' => $request->company_phone,
            'seo_description' => $request->seo_description,
            'technologies' => $request->technologies,
            'annual_revenue' => $request->annual_revenue,
            'total_funding' => $request->total_funding,
            'latest_funding' => $request->latest_funding,
            'latest_funding_amount' => $request->latest_funding_amount,
            'last_raised_at' => $request->last_raised_at,
            'email_sent' => $request->email_sent,
            'email_open' => $request->email_open,
            'email_bounced' => $request->email_bounced,
            'replied' => $request->replied,
            'demoed' => $request->demoed,
            'number_of_retail_locations' => $request->number_of_retail_locations,
            'apollo_contact_id' => $request->apollo_contact_id,
            'apollo_account_id' => $request->apollo_account_id,
        ];

        $updated = Contacts::find($contacts->id)->update($data);

        return redirect()->route('contacts.index')->with('success','Record created successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacts $contacts)
    {
        //
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new ContactsExport, 'contacts.csv');
        // return back()->with('success','Contacts exported successfully');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file', // |mimetypes:csv
        ]);

        $file = request()->file('file');

        if ($file->getClientOriginalExtension() !== 'csv') {
            return back()->withErrors(['file' => 'The uploaded file must be a CSV.']);
        }

        // Use the queue method to enable chunk importing
        Excel::queueImport(new ContactsImport, $file);
        // Excel::import(new ContactsImport, $file);

        return back()->with('success','Contacts imported successfully');
    }
}
