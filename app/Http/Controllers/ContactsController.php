<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Exports\ContactsExport;
use App\Imports\ContactsImport;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessContactsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        // $date = '1/1/2028';
        // try {
        //     $carbonDate = Carbon::parse($date);
        //     dd($carbonDate->isValid());
        //     if($carbonDate->isValid()){
        //         $dateee = $carbonDate->format('Y-m-d');
        //     } else {
        //         $dateee = 'Invalid date';
        //     }
        // } catch (\Exception $e) {
        //     $dateee = null;
        // }
        // dd($dateee);

        $groupOptions = [
            // 'title' => Contacts::whereNotNull('title')->distinct()->pluck('title'),
            // 'company' => Contacts::whereNotNull('company')->distinct()->pluck('company'),
            // 'email_status' => Contacts::whereNotNull('email_status')->distinct()->pluck('email_status'),
            // 'seniority' => Contacts::whereNotNull('seniority')->distinct()->pluck('seniority'),
            // 'departments' => Contacts::whereNotNull('departments')->distinct()->pluck('departments'),
            // 'contact_owner' => Contacts::whereNotNull('contact_owner')->distinct()->pluck('contact_owner'),
            // 'stage' => Contacts::whereNotNull('stage')->distinct()->pluck('stage'),
            // 'employees' => Contacts::whereNotNull('employees')->distinct()->pluck('employees'),
            // 'industry' => Contacts::whereNotNull('industry')->distinct()->pluck('industry'),
            // 'keywords' => Contacts::whereNotNull('keywords')->distinct()->pluck('keywords'),
            // 'city' => Contacts::whereNotNull('city')->distinct()->pluck('city'),
            // 'state' => Contacts::whereNotNull('state')->distinct()->pluck('state'),
            // 'country' => Contacts::whereNotNull('country')->distinct()->pluck('country'),
            // 'company_city' => Contacts::whereNotNull('company_city')->distinct()->pluck('company_city'),
            // 'company_state' => Contacts::whereNotNull('company_state')->distinct()->pluck('company_state'),
            // 'company_country' => Contacts::whereNotNull('company_country')->distinct()->pluck('company_country'),
            // 'annual_revenue' => Contacts::whereNotNull('annual_revenue')->distinct()->pluck('annual_revenue'),
            // 'latest_funding_type' => Contacts::whereNotNull('latest_funding')->distinct()->pluck('latest_funding'),
            // 'technologies' => Contacts::whereNotNull('technologies')->distinct()->pluck('technologies'),
        ];


        // MIN MAX: employees, annual_revenue

        $columns = [
            'titles', 'companies', 'email_status', 'seniority', 'departments', 'contact_owner', 'stage',
            'industry', 'keywords', 'city', 'state', 'country', 'company_city', 'company_state',
            'company_country', 'technologies'
        ];
        // 'employees',  'annual_revenue', 'latest_funding',

        // // Fetch all required columns with distinct values
        // $results = Contacts::select($columns)
        //                    ->where(function ($query) use ($columns) {
        //                        foreach ($columns as $column) {
        //                            $query->orWhereNotNull($column);
        //                        }
        //                    })
        //                    ->distinct()
        //                    ->get();

        // // Process results to extract distinct values for each column
        // $groupOptions = [];
        // foreach ($columns as $column) {
        //     $groupOptions[$column] = $results->pluck($column)->filter()->unique()->values();
        // }

        // // $technologiesList = [];
        // // foreach ($groupOptions['technologies'] as $row) {
        // //     $technologies = explode(', ', $row);

        // //     $technologiesList = array_merge($technologiesList, $technologies);
        // // }
        // // $uniqueTechnologies = array_unique($technologiesList);
        // // $groupOptions['technologies'] = $uniqueTechnologies;

        // $technologiesSet = [];
        // foreach ($groupOptions['technologies'] as $row) {
        //     $technologies = explode(', ', $row);
        //     foreach ($technologies as $technology) {
        //         $technologiesSet[$technology] = true;
        //     }
        // }

        // $uniqueTechnologies = array_keys($technologiesSet);
        // $groupOptions['technologies'] = $uniqueTechnologies;

        // return $groupOptions;
        // foreach ($columns as $column) {
        //     $filePath = "filters/{$column}.json";
        //     $groupOptions[$column] = json_decode(Storage::get($filePath));
        // }

        // $columns = [
        //     'titles', 'companies', 'email_status', 'seniority', 'departments', 'contact_owner', 'stage',
        //     'industry', 'keywords', 'city', 'state', 'country', 'company_city', 'company_state',
        //     'company_country', 'technologies'
        // ];

        // $groupOptions['titles'] = json_decode(Storage::get("filters/titles.json"));
        // $groupOptions['companies'] = json_decode(Storage::get("filters/companies.json"));

        // return $groupOptions;

        return view('contacts.index');
    }
    public function data(Request $request)
    {
        $columns = array(
            'id',
            'company',
            'first_name',
            'title',
            'email',
            'mobile_phone',
            'industry',
            'employees',
            'annual_revenue',
            'city'
        );

        $limit               = $request->input('length');
        $start               = $request->input('start');
        $order               = $columns[$request->input('order.0.column')];
        $dir                 = $request->input('order.0.dir');

        $contacts   = Contacts::query();
        $filters    = @$request->filter;

        if(isset($filters)){
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
                $fromEmployees = 0;
                if($key == 'from_employees' && $filter != null) {
                    $fromEmployees = $filter;
                }
                $toEmployees = 1000000;
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
                $fromRevenue = 0;
                if($key == 'from_revenue' && $filter != null) {
                    $fromRevenue = $filter;
                }
                $toRevenue = 10000000000;
                if($key == 'to_revenue' && $filter != null) {
                    $toRevenue = $filter;
                }
                $fromFunding = 0;
                if($key == 'from_funding' && $filter != null) {
                    $fromFunding = $filter;
                }
                $toFunding = 10000000000;
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

            // if(isset($fromEmployees) && isset($toEmployees)) {
            //     $fromEmployees = $request->from_employees ? $request->from_employees : 0;
            //     $toEmployees = $request->to_employees ? $request->to_employees : 10000;
            //     $contacts = $contacts->orWhereBetween('employees', [$fromEmployees, $toEmployees]);
            // }

            // if(isset($request->from_revenue) || isset($request->to_revenue)){
            //     $fromRevenue = $request->from_revenue ? $request->from_revenue : 0;
            //     $toRevenue = $request->to_revenue ? $request->to_revenue : 10000;
            //     $contacts = $contacts->orWhereBetween('annual_revenue', [$fromRevenue, $toRevenue]);
            // }
            // if(isset($request->from_funding) || isset($request->to_funding)){
            //     $fromFunding = $request->from_funding ? $request->from_funding : 0;
            //     $toFunding = $request->to_funding ? $request->to_funding : 10000;
            //     $contacts = $contacts->orWhereBetween('latest_funding', [$fromFunding, $toFunding]);
            // }
        }

        // if(isset($request->name)) {
        //     $name = $request->input('name');
        //     $contacts->orWhere('first_name', 'LIKE', '%'.$name.'%');
        //     $contacts->orWhere('last_name', 'LIKE', '%'.$name.'%');
        // }

        // if(isset($request->title)) {
        //     logger('title' . $request->title);
        //     $contacts = $contacts->orWhere('title', $request->title);
        // }

        // if(isset($request->seniority)) {
        //     $contacts = $contacts->orWhereIn('seniority', $request->seniority);
        // }

        // if(isset($request->department)) {
        //     $contacts = $contacts->orWhereIn('departments', $request->department);
        // }

        // if(isset($request->company)) {
        //     $contacts = $contacts->orWhereIn('company', $request->company);
        // }

        // if(isset($request->exclude_company)) {
        //     $contacts = $contacts->orWhereNotIn('company', $request->exclude_company);
        // }

        // if(isset($request->company_city)) {
        //     $contacts = $contacts->orWhereIn('company_city', $request->company_city);
        // }

        // if(isset($request->company_state)) {
        //     $contacts = $contacts->orWhereIn('company_state', $request->company_state);
        // }

        // if(isset($request->company_country)) {
        //     $contacts = $contacts->orWhereIn('company_country', $request->company_country);
        // }

        // if(isset($request->city)) {
        //     $contacts = $contacts->orWhereIn('city', $request->city);
        // }

        // if(isset($request->state)) {
        //     $contacts = $contacts->orWhereIn('state', $request->state);
        // }

        // if(isset($request->country)) {
        //     $contacts = $contacts->orWhereIn('country', $request->country);
        // }

        // if(isset($request->company)) {
        //     $company = $request->input('company');
        //     $contacts->whereIn('company', $company);
        // }

        // logger('FROM: '.$request->all);
        // logger('To: ' .$request->to_employees);
        // if($request->input('from_employees')) {

            // logger($request->input('name') .'----'. $request->input('from_employees') .'----'. $request->input('to_employees'));
        //     $contacts = $contacts->orWhereBetween('employees', [$request->input('from_employees'), $request->input('to_employees')]);
        // }

        // if(isset($request->from_employees) || isset($request->to_employees)) {
        //     // logger('in');
        //     $contacts = $contacts->orWhereBetween('employees', [$request->from_employees, $request->to_employees]);
        // }

        // if(isset($request->keywords)) {
        //     $contacts = $contacts->orWhereIn('keywords', explode($request->keywords, ','));
        // }

        // if(isset($request->technologies)) {
        //     $contacts = $contacts->orWhereIn('technologies', $request->technologies);
        // }

        // if(isset($request->from_revenue) || isset($request->to_revenue)) {
        //     $contacts = $contacts->orWhereBetween('employees', [$request->from_revenue, $request->to_revenue]);
        // }

        // if(isset($request->latest_funding)) {
        //     $contacts = $contacts->orWhereIn('latest_funding', $request->latest_funding);
        // }

        // if(isset($request->email_status)) {
        //     $contacts = $contacts->orWhereIn('email_status', $request->email_status);
        // }
        $totalData  = $totalFiltered = clone $contacts;
        $contacts   = ($limit == -1) ? $contacts : $contacts->offset($start)->limit($limit);
        $contacts   = $contacts->orderBy($order, $dir)->get();
        $totalData  = $totalFiltered = $totalFiltered->count();

        $data = [];
        foreach ($contacts as $key =>  $contact) {
            $contactData['sr_no']           = ++$start;
            $contactData['company']         = $contact->company;
            $contactData['name']            = $contact->first_name.' '. $contact->last_name;
            $contactData['title']           = $contact->title;
            $contactData['email']           = $contact->email;
            $contactData['mobile_phone']    = $contact->mobile_phone;
            $contactData['industry']        = $contact->industry;
            $contactData['employees']       = number_format($contact->employees);
            $contactData['annual_revenue']  = number_format($contact->annual_revenue);
            $contactData['website']         = '<a href="'.$contact->website.'"><i class="bx bx-link"></i></a>';
            $contactData['person_linkedin'] = '<a href="'.$contact->person_linkedin.'"><i class="bx bx-link"></i></a>';
            $contactData['address']         = $contact->county .' '. $contact->country;
            $data[]                         = $contactData;
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        );

        return json_encode($json_data);

        // if(isset($request->industry)) {
        //     $data = $data->orWhereIn('industry', $request->industry);
        // }

        // if(isset($request->company)) {
        //     if(isset($request->exclude_companies)) {
        //         $data = $data->orWhereNotIn('company', $request->company);
        //     } else {
        //         $data = $data->orWhereIn('company', $request->company);
        //     }
        // }

        // if(isset($request->company)) {
        //     $data = $data->orWhereIn('company', $request->company);
        // }

        // if(isset($request->exclude_company)) {
        //     $data = $data->orWhereNotIn('company', $request->exclude_company);
        // }

        // if(isset($request->departments)) {
        //     $data = $data->orWhereIn('departments', $request->departments);
        // }

        // if(isset($request->from_employees) || isset($request->to_employees)) {
        //     $data = $data->orWhereBetween('employees', [$request->from_employees, $request->to_employees]);
        // }

        // if(isset($request->from_revenue) || isset($request->to_revenue)) {
        //     $data = $data->orWhereBetween('employees', [$request->from_revenue, $request->to_revenue]);
        // }

        // if(isset($request->from_funding) || isset($request->to_funding)) {
        //     $data = $data->orWhereBetween('employees', [$request->from_funding, $request->to_funding]);
        // }

        // if(isset($request->title)) {
        //     $data = $data->orWhereIn('title', $request->title);
        // }

        // if(isset($request->seniority)) {
        //     $data = $data->orWhereIn('seniority', $request->seniority);
        // }

        // if(isset($request->email_status)) {
        //     $data = $data->orWhereIn('email_status', $request->email_status);
        // }

        // if(isset($request->city)) {
        //     $data = $data->orWhereIn('city', $request->city);
        // }

        // if(isset($request->state)) {
        //     $data = $data->orWhereIn('state', $request->state);
        // }

        // if(isset($request->country)) {
        //     $data = $data->orWhereIn('country', $request->country);
        // }

        // if(isset($request->company_city)) {
        //     $data = $data->orWhereIn('company_city', $request->company_city);
        // }

        // if(isset($request->company_state)) {
        //     $data = $data->orWhereIn('company_state', $request->company_state);
        // }

        // if(isset($request->company_country)) {
        //     $data = $data->orWhereIn('company_country', $request->company_country);
        // }

        // if(isset($request->technologies)) {
        //     $data = $data->orWhereIn('technologies', $request->technologies);
        // }

        // if(isset($request->latest_funding_type)) {
        //     $data = $data->orWhereIn('latest_funding', $request->latest_funding_type);
        // }

        // if(isset($request->name)) {
        //     $data = $data->orWhere('first_name', 'LIKE', '%'.$request->name.'%')
        //         ->orWhere('last_name', 'LIKE', '%'.$request->name.'%');
        // }

        // if(isset($request->keywords)) {
        //     $data = $data->orWhereIn('keywords', explode($request->keywords, ','));
        // }


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

        // $data = $data->orderBy('company')->orderBy('first_name')->orderBy('last_name')->paginate(50);

        // return view('contacts.index',compact('data', 'groupOptions'))
        //     ->with('i', ($request->input('page', 1) - 1) * 20);
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
            'email_sent' => $request->email_sent ?? null,
            'email_open' => $request->email_open ?? null,
            'email_bounced' => $request->email_bounced ?? null,
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
        $filters = request()->filter;
        return Excel::download(new ContactsExport($filters), 'contacts.csv');
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
        // // Use the queue method to enable chunk importing
        // Excel::queueImport(new ContactsImport, $file);
        // // Excel::import(new ContactsImport, $file);

        // Dispatch the job

        $filePath = $request->file('file')->store('uploads'); // Store the file

        dispatch(new ProcessContactsImport($filePath)); // ->delay(3)

        // ProcessContactsImport::dispatch($filePath);

        return back()->with('success','Contacts importing process is in queue now.');
    }
}
