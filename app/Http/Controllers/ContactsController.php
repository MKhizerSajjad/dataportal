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
        $columns = [
            'titles', 'companies', 'email_status', 'seniority', 'departments', 'contact_owner', 'stage',
            'industry', 'keywords', 'city', 'state', 'country', 'company_city', 'company_state',
            'company_country', 'technologies'
        ];
        return view('contacts.index');
    }

    public function removeFormatt($value) {
        $plainNumber = str_replace(',', '', $value);
        return (int) $plainNumber;
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

$contacts = Contacts::query();
$filters = $request->filter;

if ($filters) {
    foreach ($filters as $key => $filter) {
        if ($filter === null || $filter === '') {
            continue; // Skip empty filters
        }

        switch ($key) {
            case 'first_name':
                $contacts->where(function($query) use ($filter) {
                    $query->where('first_name', 'LIKE', '%' . $filter . '%');
                });
                break;
            case 'last_name':
                $contacts->where(function($query) use ($filter) {
                    $query->Where('last_name', 'LIKE', '%' . $filter . '%');
                });
                break;

                case 'title':
                    // $contacts->where(function($query) use ($filter) {
                    //     foreach ((array) $filter as $value) {
                    //         $words = explode(' ', $value);
                    //         // Build the query to find titles containing all words
                    //         foreach ($words as $word) {
                    //             // Ensure each word is present in the title
                    //             $query->where('title', 'LIKE', '%' . $word . '%');
                    //         }
                    //     }
                    // });
                    // break;
                    $contacts->where(function($query) use ($filter) {
                        $titleMappings = [
                            'CEO' => ['CEO', 'Chief Executive Officer'],
                            'CSO' => ['CSO', 'Chief Sales Officer'],
                            'CRO' => ['CRO', 'Chief Revenue Officer'],
                            'CCO' => ['CCO', 'Chief Commercial Officer'],
                            'Chief Operating Officer' => ['Chief Operating Officer', 'COO'],
                            'Managing Director' => ['Managing Director', 'MD'],
                            'VP Sales' => ['VP Sales', 'Vice President Sales'],
                            'Chief Marketing Officer' => ['Chief Marketing Officer', 'CMO'],
                            'CIO' => ['CIO', 'Chief Information Officer'],
                            'Chief Technology Officer' => ['Chief Technology Officer', 'CTO']
                        ];

                        foreach ((array) $filter as $value) {
                            // Check if the word exists in the mappings
                            $matchedTitles = [];
                            foreach ($titleMappings as $key => $synonyms) {
                                if (in_array($value, $synonyms)) {
                                    $matchedTitles = $synonyms;
                                }
                            }
                            if (!empty($matchedTitles)) {
                                // Build OR condition for matched titles
                                // $orQueries[] = function ($q) use ($matchedTitles) {
                                    foreach ($matchedTitles as $title) {
                                        $query->orWhere('title', 'LIKE', '%' . $title . '%');
                                        // logger('title : ' . $title);
                                    }
                                // };
                            }
                            $words = explode(' ', $value);
                            $normalized = '%' . implode('%', $words) . '%';
                            // Build the OR condition for each possible permutation
                            foreach ($words as $word) {
                                $query->where('title', 'LIKE', '%' . $word . '%');
                            }
                            // Allow different word order and additional strings
                            $query->orWhere('title', 'LIKE', $normalized);
                        }
                    });
                    // case 'title':
                    //     $contacts->where(function($query) use ($filter) {
                    //         foreach ((array) $filter as $value) {
                    //             $words = explode(' ', $value);
                    //             $normalized = '%' . implode('%', $words) . '%';
                    //             // Build the OR condition for each possible permutation
                    //             foreach ($words as $word) {
                    //                 $query->orWhere('title', 'LIKE', '%' . $word . '%');
                    //             }
                    //             // Allow different word order and additional strings
                    //             $query->orWhere('title', 'LIKE', $normalized);
                    //         }
                    //     });
                    break;
            case 'seniority':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->where('seniority', 'LIKE', $value);
                    }
                });
                break;
            // case 'seniority':
            //     $contacts->where(function($query) use ($filter) {
            //         foreach ((array) $filter as $value) {
            //             $words = explode(' ', $value);
            //             foreach ($words as $word) {
            //                 $query->orWhere('seniority', 'LIKE', '%' . $word . '%');
            //             }
            //         }
            //     });
            //     break;

            case 'department':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->Where('departments', 'LIKE', $value);
                    }
                });
                break;

            case 'company':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('company', 'LIKE', $value);
                    }
                });
                break;
            // case 'company':
            //     $contacts->where(function($query) use ($filter) {
            //         foreach ((array) $filter as $value) {
            //             $words = explode(' ', $value);
            //             foreach ($words as $word) {
            //                 $query->orWhere('company', 'LIKE', '%' . $word . '%');
            //             }
            //         }
            //     });
            //     break;
            case 'company_city':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('company_city', 'LIKE', $value);
                    }
                });
                break;
            case 'company_state':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('company_state', 'LIKE', $value);
                    }
                });
                break;
            case 'company_country':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('company_country', 'LIKE', $value);
                    }
                });
                break;
            case 'city':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('city', 'LIKE', $value);
                    }
                });
                break;
            case 'state':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('state', 'LIKE', $value);
                    }
                });
                break;
            case 'country':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('country', 'LIKE', $value);
                    }
                });
                break;

            case 'industry':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $words = explode(' ', $value);
                        // Build the query to find industry containing all words
                        foreach ($words as $word) {
                            // Ensure each word is present in the industry
                            $query->orWhere('industry', 'LIKE', '%' . $word . '%');
                        }
                    }
                });
                break;
            // case 'industry':
            //     $contacts->where(function($query) use ($filter) {
            //         foreach ((array) $filter as $value) {
            //             $words = explode(' ', $value);
            //             foreach ($words as $word) {
            //                 $query->orWhere('industry', 'LIKE', '%' . $word . '%');
            //             }
            //         }
            //     });
            //     break;
            case 'email_status':
                $contacts->whereIn($key, (array) $filter);
                break;
            case 'exclude_company':
                $contacts->whereNotIn('company', (array) $filter);
                break;
            case 'from_employees':
                $fromEmployees = $this->removeFormatt($filter);
                break;
            case 'to_employees':
                $toEmployees = $this->removeFormatt($filter);
                break;
            case 'from_revenue':
                $fromRevenue = $this->removeFormatt($filter);
                break;
            case 'to_revenue':
                $toRevenue = $this->removeFormatt($filter);
                break;
            case 'from_funding':
                $fromFunding = $this->removeFormatt($filter);
                break;
            case 'to_funding':
                $toFunding = $this->removeFormatt($filter);
                break;
            case 'keywords':
                $keywords = explode(',', $filter);
                $contacts->where(function($query) use ($keywords) {
                    foreach ($keywords as $value) {
                        $query->orWhere('keywords', 'LIKE', '%' . $value . '%');
                    }
                });
                break;

            case 'funding-cats':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhere('latest_funding', 'LIKE', $value);
                    }
                });
                break;

            case 'technologies':
                $contacts->where(function($query) use ($filter) {
                    foreach ((array) $filter as $value) {
                        $query->orWhereIn('technologies', [$value]);
                    }
                });
                break;
            // case 'technologies':
            //     $contacts->where(function($query) use ($filter) {
            //         foreach ((array) $filter as $value) {
            //             $words = explode(' ', $value);
            //             foreach ($words as $word) {
            //                 $query->orWhere('technologies', 'LIKE', '%' . $word . '%');
            //             }
            //         }
            //     });
            //     break;
        }
    }

    // Additional where clauses for range filters
    if(isset($fromEmployees) || isset($toEmployees)) {
        $contacts->whereBetween('employees', [$fromEmployees ?? 0, $toEmployees ?? 1000000]);
    }

    if(isset($fromRevenue) || isset($toRevenue)) {
        $contacts->whereBetween('annual_revenue', [$fromRevenue ?? 0, $toRevenue ?? 10000000000]);
    }

    if(isset($fromFunding) || isset($toFunding)) {
        $contacts->whereBetween('total_funding', [$fromFunding ?? 0, $toFunding ?? 10000000000]);
    }
}

$totalFiltered = clone $contacts;
$totalFiltered = $totalFiltered->count();

$contacts = ($limit == -1) ? $contacts : $contacts->offset($start)->limit($limit);
$contacts = $contacts->orderBy($order, $dir)->get();
$totalData = $totalFiltered;

        // $contacts   = Contacts::query();
        // $filters    = @$request->filter;

        // if(isset($filters)){
        //     foreach($filters as $key => $filter){
        //         if( $key == 'name' && $filter != null ){
        //             $contacts =  $contacts
        //                 ->orWhere('first_name', 'LIKE', '%'.$filter.'%')
        //                 ->orWhere('last_name', 'LIKE', '%'.$filter.'%');
        //         }
        //         if( $key == 'title' && $filter != null ){
        //             $contacts =  $contacts->orWhereIn('title', $filter);
        //         }
        //         if($key == 'seniority' && $filter != null){
        //             $contacts = $contacts->orWhereIn('seniority', $filter);
        //         }
        //         if($key == 'department' && $filter != null){
        //             $contacts = $contacts->orWhereIn('departments', $filter);
        //         }
        //         if($key == 'company' && $filter != null){
        //             $contacts = $contacts->orWhereIn('company', $filter);
        //         }
        //         if($key == 'exclude_company' && $filter != null){
        //             $contacts = $contacts->orWhereNotIn('company', $filter);
        //         }
        //         if($key == 'company_city' && $filter != null){
        //             $contacts = $contacts->orWhereIn('company_city', $filter);
        //         }
        //         if($key == 'company_state' && $filter != null){
        //             $contacts = $contacts->orWhereIn('company_state', $filter);
        //         }
        //         if($key == 'company_country' && $filter != null){
        //             $contacts = $contacts->orWhereIn('company_country', $filter);
        //         }
        //         if($key == 'city' && $filter != null){
        //             $contacts = $contacts->orWhereIn('city', $filter);
        //         }
        //         if($key == 'state' && $filter != null){
        //             $contacts = $contacts->orWhereIn('state', $filter);
        //         }
        //         if($key == 'country' && $filter != null){
        //             $contacts = $contacts->orWhereIn('country', $filter);
        //         }
        //         $fromEmployees = 0;
        //         if($key == 'from_employees' && $filter != null) {
        //             $fromEmployees = $filter;
        //         }
        //         $toEmployees = 1000000;
        //         if($key == 'to_employees' && $filter != null) {
        //             $toEmployees = $filter;
        //         }
        //         if($key == 'industry' && $filter != null){
        //             $contacts = $contacts->orWhereIn('industry', $filter);
        //         }
        //         if($key == 'keywords' && $filter != null){
        //             // VALIDATE THIS
        //             $contacts = $contacts->orWhereIn('keywords', $filter);
        //         }
        //         if($key == 'technologies' && $filter != null){
        //             // VALIDATE THIS
        //             $contacts = $contacts->orWhereIn('technologies', $filter);
        //         }
        //         $fromRevenue = 0;
        //         if($key == 'from_revenue' && $filter != null) {
        //             $fromRevenue = $filter;
        //         }
        //         $toRevenue = 10000000000;
        //         if($key == 'to_revenue' && $filter != null) {
        //             $toRevenue = $filter;
        //         }
        //         $fromFunding = 0;
        //         if($key == 'from_funding' && $filter != null) {
        //             $fromFunding = $filter;
        //         }
        //         $toFunding = 10000000000;
        //         if($key == 'to_funding' && $filter != null) {
        //             $toFunding = $filter;
        //         }
        //         if($key == 'email_status' && $filter != null){
        //             $contacts = $contacts->orWhereIn('email_status', $filter);
        //         }
        //     }
        //     $contacts = $contacts->orWhereBetween('employees', [$fromEmployees, $toEmployees]);
        //     $contacts = $contacts->orWhereBetween('annual_revenue', [$fromRevenue, $toRevenue]);
        //     $contacts = $contacts->orWhereBetween('latest_funding', [$fromFunding, $toFunding]);

        // }

        // $totalData  = $totalFiltered = clone $contacts;
        // $contacts   = ($limit == -1) ? $contacts : $contacts->offset($start)->limit($limit);
        // $contacts   = $contacts->orderBy($order, $dir)->get();
        // $totalData  = $totalFiltered = $totalFiltered->count();

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
    public function export(Request $request)
    {

        try {
            // get all filtered contacts
            $contacts = new ContactsExport($request->all());


            $fileName = 'contacts-'. Carbon::now()->timestamp .'.csv';
            $filePath = 'public/exports/'.$fileName;
            Excel::store($contacts, $filePath);
            // logger('PATH : ' . $filePath);


            // $filePath = storage_path($filePath);
            // logger('222' . $filePath);
            // logger('3333'  . Storage::download($filePath));
            // return Storage::download($filePath);
            // logger('3333 --- '  . 'storage/app/exports/'.$fileName);
            // return 'public/storage/exports/'.$fileName;
            return 'storage/'.$filePath;
                // return response()->json(['url' => Storage::url($filePath)]);

            // if (Storage::exists($filePath)) {
            //     // return 'storage/app/'.$filePath;
            //     // return public_path('storage/app/'.$filePath);

            //     // return response()->json(['url' => Storage::url($filePath)]);
            //     // return Storage::download($filePath);
            //     // return "storage/app/public/".$filePath;
            //     logger('----------in--------------');
            //     return $filePath;
            //     // return response()->download(storage_path('app/'.$filePath))->deleteFileAfterSend(true);
            // } else {
            //     return redirect()->route('contacts.index')->with('Oops!','We got some error.');
            // }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error storing Excel file: ' . $e->getMessage());
            // Return an error response
            return redirect()->route('contacts.index')->with('Oops!','We got some error.');
            // return response()->json(['error' => 'Error storing Excel file'], 500);
        }

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

        $filePath = $request->file('file')->store('uploads'); // Store the file

        dispatch(new ProcessContactsImport($filePath)); // ->delay(3)

        // ProcessContactsImport::dispatch($filePath);

        return back()->with('success','Contacts importing process is in queue now.');
    }
}
