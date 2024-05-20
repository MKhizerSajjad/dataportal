@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Contacts</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class=""><a href="javascript: void(0);">Contacts</a></li>
                                <li class="mx-1"><a href="javascript: void(0);"> > </a></li>
                                <li class="breadcrumb-item active">Contacts List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-border-left alert-dismissible fade show auto-colse-3" role="alert">
                    <i class="ri-check-double-line me-3 align-middle fs-16"></i><strong>Success! </strong>
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Upload Contacts</h4>
                            <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input id="file" name="file" type="file" class="form-control @error('file') is-invalid @enderror" placeholder="file" value="{{ old('file') }}">
                                            @error('file')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-success">  <i class="bx bx-import me-1"></i> Import Contacts</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Filters</h4>
                            <form action="{{ route('contacts.index') }}" method="GET">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100"> <i class="bx bx-filter-alt me-1"></i>Apply</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('contacts.index') }}" class="waves-effect waves-light btn btn-secondary w-100"> <i class="bx bx-crosshair me-1"></i>Remove</a>
                                    </div>
                                </div>
                                {{-- <label for="status">Status:</label>
                                <input type="number" name="status" max="127" class="form-control" value="{{ old('status') }}"><br> --}}

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request()->input('name') }}"><br>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Job Title</label>
                                    <select name="titles[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['title'] as $title)
                                            <option value="{{$title}}" @if(in_array($title, request()->input('titles', []))) selected @endif>{{$title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Seniority</label>
                                    <select name="seniority[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['seniority'] as $seniority)
                                            <option value="{{$seniority}}" @if(in_array($seniority, request()->input('seniority', []))) selected @endif>{{$seniority}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Department</label>
                                    <select name="departments[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['departments'] as $department)
                                            <option value="{{$department}}" @if(in_array($department, request()->input('departments', []))) selected @endif>{{$department}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Comapny</label>

                                    {{-- <p class="card-title-desc font-size-10 mb-0">
                                        <input type="checkbox" class="form-check-input" id="defaultCheck2" name="exclude_companies" {{ request()->has('exclude_companies') ? 'checked' : '' }}>
                                        <code><b>Include companies</b></code>
                                    </p> --}}
                                    <select name="company[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['company'] as $company)
                                            <option value="{{$company}}" @if(in_array($company, request()->input('company', []))) selected @endif>{{$company}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Comapny</label>

                                    <p class="card-title-desc font-size-10 mb-0">
                                        {{-- <input type="checkbox" class="form-check-input" id="defaultCheck2" name="exclude_companies" {{ request()->has('exclude_companies') ? 'checked' : '' }}> --}}
                                        <code><b>Exclude companies</b></code>
                                    </p>
                                    <select name="exclude_company[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['company'] as $company)
                                            <option value="{{$company}}" @if(in_array($company, request()->input('exclude_company', []))) selected @endif>{{$company}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-0">Person Address</label>
                                    <div class="mb-1">
                                        <select name="city[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select City">
                                            @foreach ($groupOptions['city'] as $city)
                                                <option value="{{$city}}" @if(in_array($city, request()->input('city', []))) selected @endif>{{$city}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="state[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select State">
                                            @foreach ($groupOptions['state'] as $state)
                                                <option value="{{$state}}" @if(in_array($state, request()->input('state', []))) selected @endif>{{$state}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="country[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select Country">
                                            @foreach ($groupOptions['country'] as $country)
                                                <option value="{{$country}}" @if(in_array($country, request()->input('country', []))) selected @endif>{{$country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Company Address</label>
                                    <div class="mb-1">
                                        <select name="company_city[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select City">
                                            @foreach ($groupOptions['company_city'] as $company_city)
                                                <option value="{{$company_city}}" @if(in_array($company_city, request()->input('company_city', []))) selected @endif>{{$company_city}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="company_state[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select State">
                                            @foreach ($groupOptions['company_state'] as $company_state)
                                                <option value="{{$company_state}}" @if(in_array($company_state, request()->input('company_state', []))) selected @endif>{{$company_state}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="company_country[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select Country">
                                            @foreach ($groupOptions['company_country'] as $company_country)
                                                <option value="{{$company_country}}" @if(in_array($company_country, request()->input('company_country', []))) selected @endif>{{$company_country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Employees</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_employees" class="form-control" value="{{ request()->input('from_employees') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_employees" class="form-control" value="{{ request()->input('to_employees') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Industry</label>
                                    <select name="industry[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['industry'] as $industry)
                                            <option value="{{$industry}}" @if(in_array($industry, request()->input('industry', []))) selected @endif>{{$industry}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="keywords">Keywords</label>
                                    <p class="card-title-desc font-size-10 mb-0">Each keyword should be comma seprated (<code><b>,</b></code>)</p>
                                    <textarea name="keywords" class="form-control">{{ request()->input('keywords') }}</textarea><br>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Technologies</label>
                                    <select name="technologies[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['technologies'] as $technology)
                                            <option value="{{$technology}}" @if(in_array($technology, request()->input('technologies', []))) selected @endif>{{$technology}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Annual Revenue</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_revenue" class="form-control" value="{{ request()->input('from_revenue') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_revenue" class="form-control" value="{{ request()->input('to_revenue') }}">
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Total Funding</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_funding" class="form-control" value="{{ request()->input('from_funding') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_funding" class="form-control" value="{{ request()->input('to_funding') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Lastest Funding Type</label>
                                    <select name="latest_funding_type[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['latest_funding_type'] as $latest_funding_type)
                                            <option value="{{$latest_funding_type}}" @if(in_array($latest_funding_type, request()->input('latest_funding', []))) selected @endif>{{$latest_funding_type}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Email Status</label>
                                    <select name="email_status[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['email_status'] as $status)
                                            <option value="{{$status}}" @if(in_array($status, request()->input('email_status', []))) selected @endif>{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                {{-- <label for="company_name_for_emails">Company Name for Emails:</label>
                                <input type="text" name="company_name_for_emails" class="form-control" value="{{ request()->input('company_name_for_emails') }}"><br>

                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" value="{{ request()->input('email') }}"><br>

                                <label for="email_confidence">Email Confidence:</label>
                                <input type="text" name="email_confidence" class="form-control" value="{{ request()->input('email_confidence') }}"><br>

                                <label for="contact_owner">Contact Owner:</label>
                                <input type="text" name="contact_owner" class="form-control" value="{{ request()->input('contact_owner') }}"><br>

                                <label for="first_phone">First Phone:</label>
                                <input type="tel" name="first_phone" class="form-control" value="{{ request()->input('first_phone') }}"><br>

                                <label for="work_direct_phone">Work Direct Phone:</label>
                                <input type="tel" name="work_direct_phone" class="form-control" value="{{ request()->input('work_direct_phone') }}"><br>

                                <label for="home_phone">Home Phone:</label>
                                <input type="tel" name="home_phone" class="form-control" value="{{ request()->input('home_phone') }}"><br>

                                <label for="mobile_phone">Mobile Phone:</label>
                                <input type="tel" name="mobile_phone" class="form-control" value="{{ request()->input('mobile_phone') }}"><br>

                                <label for="corporate_phone">Corporate Phone:</label>
                                <input type="tel" name="corporate_phone" class="form-control" value="{{ request()->input('corporate_phone') }}"><br>

                                <label for="other_phone">Other Phone:</label>
                                <input type="tel" name="other_phone" class="form-control" value="{{ request()->input('other_phone') }}"><br>

                                <label for="stage">Stage:</label>
                                <input type="text" name="stage" class="form-control" value="{{ request()->input('stage') }}"><br>

                                <label for="lists">Lists:</label>
                                <input type="text" name="lists" class="form-control" value="{{ request()->input('lists') }}"><br>

                                <label for="last_contacted">Last Contacted:</label>
                                <input type="datetime-local" name="last_contacted" class="form-control" value="{{ request()->input('last_contacted') }}"><br>

                                <label for="account_owner">Account Owner:</label>
                                <input type="text" name="account_owner" class="form-control" value="{{ request()->input('account_owner') }}"><br>

                                <label for="employees">Employees:</label>
                                <input type="number" name="employees" class="form-control" value="{{ request()->input('employees') }}"><br>

                                <label for="apollo_contact_id">Apollo Contact ID:</label>
                                <input type="text" name="apollo_contact_id" class="form-control" value="{{ request()->input('apollo_contact_id') }}"><br>

                                <label for="apollo_account_id">Apollo Account ID:</label>
                                <input type="text" name="apollo_account_id" class="form-control" value="{{ request()->input('apollo_account_id') }}"><br> --}}


                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-lg-9">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Contacts List</h4>
                            <div class="d-flex justify-content-end gap-2" bis_skin_checked="1">
                                <a class="btn btn-primary waves-effect waves-light" href="{{ route('contacts.export') }}"> <i class="bx bx-export me-1"></i> Export Contacts</a>

                                {{-- <a href="{{ route('contacts.create') }}" class="btn btn-primary waves-effect waves-light w-10"> <i class="bx bx-plus me-1"></i> Add New</a> --}}
                            </div>
                            {{-- <div class="card-title-desc card-subtitle" bis_skin_checked="1">Create responsive tables by wrapping any <code>.table</code> in <code>.table-responsive</code>to make them scroll horizontally on small devices (under 768px).</div> --}}

                            <ul class="nav nav-pills nav-justified mt-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#company" role="tab">
                                        <span class="d-none d-sm-block"><i class="bx bx-buildings"></i> Company</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#person" role="tab">
                                        <span class="d-block d-sm-none"></span>
                                        <span class="d-none d-sm-block"><i class="bx bx-user-circle me-1"></i>Person</span>
                                    </a>
                                </li>
                            </ul>

                            @if (count($data) > 0)
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="company" role="tabpanel">
                                        <p class="mb-0">
                                            <div class="table-responsive" bis_skin_checked="1">
                                                <table class="table mb-0 table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Company</th>
                                                            <th>Representative</th>
                                                            <th>Title</th>
                                                            <th>Person Email</th>
                                                            <th>Person Phone</th>
                                                            <th>Email</th>
                                                            <th>Phone</th>
                                                            <th>Industry</th>
                                                            <th>Employees</th>
                                                            <th>Revenue</th>
                                                            <th>Address</th>
                                                            <th class="text-center">Options</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $key => $contact)
                                                            <tr>
                                                                <td  class="text-center">{{ ++$key }}</td>
                                                                <td>{{ $contact->company }}</td>
                                                                <td>{{ $contact->first_name .' '. $contact->last_name}}</td>
                                                                <td>{{ $contact->title}}</td>
                                                                <td>{{ $contact->email}}</td>
                                                                <td>{{ $contact->mobile_phone}}</td>
                                                                <td>{{ $contact->email }}</td>
                                                                <td>{{ $contact->company_phone }}</td>
                                                                <td>{{ $contact->industry }}</td>
                                                                <td>{{ $contact->employees }}</td>
                                                                <td>{{ number_format($contact->annual_revenue) }}</td>
                                                                <td>{{ $contact->company_state .', '. $contact->company_country }}</td>
                                                                <td class="text-center">
                                                                    {{-- <a href="{{ route('contacts.edit', $contact->id) }}"><i class="bx bx-pencil"></i></a> --}}
                                                                    <a href="#" class="detail-modal-btn" data-toggle="modal" data-target="#detailModal{{ $contact->id }}"><i class="bx bx-info-circle"></i></a>
                                                                </td>
                                                            </tr>

                                                            {{-- Detail Modal --}}
                                                            <div class="modal fade" id="detailModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 50%;">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="detailModalLabel">Contact Details</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label for="first_name">First Name:</label>
                                                                                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $contact->first_name }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="last_name">Last Name:</label>
                                                                                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $contact->last_name }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="title">Title:</label>
                                                                                    <input type="text" id="title" name="title" class="form-control" value="{{ $contact->title }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company">Company:</label>
                                                                                    <input type="text" id="company" name="company" class="form-control" value="{{ $contact->company }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_name_for_emails">Company Name for Emails:</label>
                                                                                    <input type="text" id="company_name_for_emails" name="company_name_for_emails" class="form-control" value="{{ $contact->company_name_for_emails }}" disabled>
                                                                                </div><div class="col-md-6">
                                                                                    <label for="email">Email:</label>
                                                                                    <input type="text" id="email" name="email" class="form-control" value="{{ $contact->email }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_status">Email Status:</label>
                                                                                    <input type="text" id="email_status" name="email_status" class="form-control" value="{{ $contact->email_status }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_confidence">Email Confidence:</label>
                                                                                    <input type="text" id="email_confidence" name="email_confidence" class="form-control" value="{{ $contact->email_confidence }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="seniority">Seniority:</label>
                                                                                    <input type="text" id="seniority" name="seniority" class="form-control" value="{{ $contact->seniority }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="departments">Departments:</label>
                                                                                    <input type="text" id="departments" name="departments" class="form-control" value="{{ $contact->departments }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="contact_owner">Contact Owner:</label>
                                                                                    <input type="text" id="contact_owner" name="contact_owner" class="form-control" value="{{ $contact->contact_owner }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="first_phone">First Phone:</label>
                                                                                    <input type="text" id="first_phone" name="first_phone" class="form-control" value="{{ $contact->first_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="work_direct_phone">Work Direct Phone:</label>
                                                                                    <input type="text" id="work_direct_phone" name="work_direct_phone" class="form-control" value="{{ $contact->work_direct_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="home_phone">Home Phone:</label>
                                                                                    <input type="text" id="home_phone" name="home_phone" class="form-control" value="{{ $contact->home_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="mobile_phone">Mobile Phone:</label>
                                                                                    <input type="text" id="mobile_phone" name="mobile_phone" class="form-control" value="{{ $contact->mobile_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="corporate_phone">Corporate Phone:</label>
                                                                                    <input type="text" id="corporate_phone" name="corporate_phone" class="form-control" value="{{ $contact->corporate_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="other_phone">Other Phone:</label>
                                                                                    <input type="text" id="other_phone" name="other_phone" class="form-control" value="{{ $contact->other_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="stage">Stage:</label>
                                                                                    <input type="text" id="stage" name="stage" class="form-control" value="{{ $contact->stage }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="lists">Lists:</label>
                                                                                    <input type="text" id="lists" name="lists" class="form-control" value="{{ $contact->lists }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="last_contacted">Last Contacted:</label>
                                                                                    <input type="text" id="last_contacted" name="last_contacted" class="form-control" value="{{ $contact->last_contacted }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="account_owner">Account Owner:</label>
                                                                                    <input type="text" id="account_owner" name="account_owner" class="form-control" value="{{ $contact->account_owner }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="employees">Employees:</label>
                                                                                    <input type="text" id="employees" name="employees" class="form-control" value="{{ $contact->employees }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="industry">Industry:</label>
                                                                                    <input type="text" id="industry" name="industry" class="form-control" value="{{ $contact->industry }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="keywords">Keywords:</label>
                                                                                    <input type="text" id="keywords" name="keywords" class="form-control" value="{{ $contact->keywords }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="person_linkedin">Person LinkedIn:</label>
                                                                                    <input type="text" id="person_linkedin" name="person_linkedin" class="form-control" value="{{ $contact->person_linkedin }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="url">URL:</label>
                                                                                    <input type="text" id="url" name="url" class="form-control" value="{{ $contact->url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="website">Website:</label>
                                                                                    <input type="text" id="website" name="website" class="form-control" value="{{ $contact->website }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_linkedin_url">Company LinkedIn URL:</label>
                                                                                    <input type="text" id="company_linkedin_url" name="company_linkedin_url" class="form-control" value="{{ $contact->company_linkedin_url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="facebook_url">Facebook URL:</label>
                                                                                    <input type="text" id="facebook_url" name="facebook_url" class="form-control" value="{{ $contact->facebook_url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="twitter_url">Twitter URL:</label>
                                                                                    <input type="text" id="twitter_url" name="twitter_url" class="form-control" value="{{ $contact->twitter_url }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="city">City:</label>
                                                                                    <input type="text" id="city" name="city" class="form-control" value="{{ $contact->city }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="state">State:</label>
                                                                                    <input type="text" id="state" name="state" class="form-control" value="{{ $contact->state }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="country">Country:</label>
                                                                                    <input type="text" id="country" name="country" class="form-control" value="{{ $contact->country }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_address">Company Address:</label>
                                                                                    <input type="text" id="company_address" name="company_address" class="form-control" value="{{ $contact->company_address }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_city">Company City:</label>
                                                                                    <input type="text" id="company_city" name="company_city" class="form-control" value="{{ $contact->company_city }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_state">Company State:</label>
                                                                                    <input type="text" id="company_state" name="company_state" class="form-control" value="{{ $contact->company_state }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_country">Company Country:</label>
                                                                                    <input type="text" id="company_country" name="company_country" class="form-control" value="{{ $contact->company_country }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_phone">Company Phone:</label>
                                                                                    <input type="text" id="company_phone" name="company_phone" class="form-control" value="{{ $contact->company_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="seo_description">SEO Description:</label>
                                                                                    <input type="text" id="seo_description" name="seo_description" class="form-control" value="{{ $contact->seo_description }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="technologies">Technologies:</label>
                                                                                    <input type="text" id="technologies" name="technologies" class="form-control" value="{{ $contact->technologies }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="annual_revenue">Annual Revenue:</label>
                                                                                    <input type="text" id="annual_revenue" name="annual_revenue" class="form-control" value="{{ $contact->annual_revenue }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="total_funding">Total Funding:</label>
                                                                                    <input type="text" id="total_funding" name="total_funding" class="form-control" value="{{ $contact->total_funding }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="latest_funding">Latest Funding:</label>
                                                                                    <input type="text" id="latest_funding" name="latest_funding" class="form-control" value="{{ $contact->latest_funding }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="latest_funding_amount">Latest Funding Amount:</label>
                                                                                    <input type="text" id="latest_funding_amount" name="latest_funding_amount" class="form-control" value="{{ $contact->latest_funding_amount }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="last_raised_at">Last Raised At:</label>
                                                                                    <input type="text" id="last_raised_at" name="last_raised_at" class="form-control" value="{{ $contact->last_raised_at }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_sent">Email Sent:</label>
                                                                                    <input type="text" id="email_sent" name="email_sent" class="form-control" value="{{ $contact->email_sent }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_open">Email Open:</label>
                                                                                    <input type="text" id="email_open" name="email_open" class="form-control" value="{{ $contact->email_open }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_bounced">Email Bounced:</label>
                                                                                    <input type="text" id="email_bounced" name="email_bounced" class="form-control" value="{{ $contact->email_bounced }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="replied">Replied:</label>
                                                                                    <input type="text" id="replied" name="replied" class="form-control" value="{{ $contact->replied }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="demoed">Demoed:</label>
                                                                                    <input type="text" id="demoed" name="demoed" class="form-control" value="{{ $contact->demoed }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="number_of_retail_locations">Number of Retail Locations:</label>
                                                                                    <input type="text" id="number_of_retail_locations" name="number_of_retail_locations" class="form-control" value="{{ $contact->number_of_retail_locations }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="apollo_contact_id">Apollo Contact ID:</label>
                                                                                    <input type="text" id="apollo_contact_id" name="apollo_contact_id" class="form-control" value="{{ $contact->apollo_contact_id }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="apollo_account_id">Apollo Account ID:</label>
                                                                                    <input type="text" id="apollo_account_id" name="apollo_account_id" class="form-control" value="{{ $contact->apollo_account_id }}" disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $data->links() }}
                                            </div>
                                        </p>
                                    </div>
                                    <div class="tab-pane" id="person" role="tabpanel">
                                        <p class="mb-0">
                                            <div class="table-responsive" bis_skin_checked="1">
                                                <table class="table mb-0 table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>Name</th>
                                                            <th>Title</th>
                                                            <th>Company</th>
                                                            <th>Email</th>
                                                            <th>Mobile</th>
                                                            <th>Employees</th>
                                                            <th>Revenue</th>
                                                            <th>Address</th>
                                                            <th>Industry</th>
                                                            <th>Keywords</th>
                                                            <th>Website</th>
                                                            <th>Personal LinkedIn</th>
                                                            <th class="text-center">Options</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $key => $contact)
                                                            <tr>
                                                                <td  class="text-center">{{ ++$key }}</td>
                                                                <td>{{ $contact->first_name .' '. $contact->last_name }}</td>
                                                                <td>{{ $contact->title }}</td>
                                                                <td>{{ $contact->company }}</td>
                                                                <td>{{ $contact->email }}</td>
                                                                <td>{{ $contact->mobile_phone }}</td>
                                                                <td>{{ $contact->employees }}</td>
                                                                <td>{{ number_format($contact->annual_revenue) }}</td>
                                                                <td>{{ $contact->country }}</td>
                                                                <td>{{ $contact->industry }}</td>
                                                                <td>{{ $contact->keywords }}</td>
                                                                <td>{{ $contact->website }}</td>
                                                                <td>{{ $contact->person_linkedin }}</td>
                                                                <td class="text-center">
                                                                    {{-- <a href="{{ route('contacts.edit', $contact->id) }}"><i class="bx bx-pencil"></i></a> --}}
                                                                    <a href="#" class="detail-modal-btn" data-toggle="modal" data-target="#detailModal{{ $contact->id }}"><i class="bx bx-info-circle"></i></a>
                                                                </td>
                                                            </tr>

                                                            {{-- Detail Modal --}}
                                                            <div class="modal fade" id="detailModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 50%;">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="detailModalLabel">Contact Details</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label for="first_name">First Name:</label>
                                                                                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $contact->first_name }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="last_name">Last Name:</label>
                                                                                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $contact->last_name }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="title">Title:</label>
                                                                                    <input type="text" id="title" name="title" class="form-control" value="{{ $contact->title }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company">Company:</label>
                                                                                    <input type="text" id="company" name="company" class="form-control" value="{{ $contact->company }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_name_for_emails">Company Name for Emails:</label>
                                                                                    <input type="text" id="company_name_for_emails" name="company_name_for_emails" class="form-control" value="{{ $contact->company_name_for_emails }}" disabled>
                                                                                </div><div class="col-md-6">
                                                                                    <label for="email">Email:</label>
                                                                                    <input type="text" id="email" name="email" class="form-control" value="{{ $contact->email }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_status">Email Status:</label>
                                                                                    <input type="text" id="email_status" name="email_status" class="form-control" value="{{ $contact->email_status }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_confidence">Email Confidence:</label>
                                                                                    <input type="text" id="email_confidence" name="email_confidence" class="form-control" value="{{ $contact->email_confidence }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="seniority">Seniority:</label>
                                                                                    <input type="text" id="seniority" name="seniority" class="form-control" value="{{ $contact->seniority }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="departments">Departments:</label>
                                                                                    <input type="text" id="departments" name="departments" class="form-control" value="{{ $contact->departments }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="contact_owner">Contact Owner:</label>
                                                                                    <input type="text" id="contact_owner" name="contact_owner" class="form-control" value="{{ $contact->contact_owner }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="first_phone">First Phone:</label>
                                                                                    <input type="text" id="first_phone" name="first_phone" class="form-control" value="{{ $contact->first_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="work_direct_phone">Work Direct Phone:</label>
                                                                                    <input type="text" id="work_direct_phone" name="work_direct_phone" class="form-control" value="{{ $contact->work_direct_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="home_phone">Home Phone:</label>
                                                                                    <input type="text" id="home_phone" name="home_phone" class="form-control" value="{{ $contact->home_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="mobile_phone">Mobile Phone:</label>
                                                                                    <input type="text" id="mobile_phone" name="mobile_phone" class="form-control" value="{{ $contact->mobile_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="corporate_phone">Corporate Phone:</label>
                                                                                    <input type="text" id="corporate_phone" name="corporate_phone" class="form-control" value="{{ $contact->corporate_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="other_phone">Other Phone:</label>
                                                                                    <input type="text" id="other_phone" name="other_phone" class="form-control" value="{{ $contact->other_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="stage">Stage:</label>
                                                                                    <input type="text" id="stage" name="stage" class="form-control" value="{{ $contact->stage }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="lists">Lists:</label>
                                                                                    <input type="text" id="lists" name="lists" class="form-control" value="{{ $contact->lists }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="last_contacted">Last Contacted:</label>
                                                                                    <input type="text" id="last_contacted" name="last_contacted" class="form-control" value="{{ $contact->last_contacted }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="account_owner">Account Owner:</label>
                                                                                    <input type="text" id="account_owner" name="account_owner" class="form-control" value="{{ $contact->account_owner }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="employees">Employees:</label>
                                                                                    <input type="text" id="employees" name="employees" class="form-control" value="{{ $contact->employees }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="industry">Industry:</label>
                                                                                    <input type="text" id="industry" name="industry" class="form-control" value="{{ $contact->industry }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="keywords">Keywords:</label>
                                                                                    <input type="text" id="keywords" name="keywords" class="form-control" value="{{ $contact->keywords }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="person_linkedin">Person LinkedIn:</label>
                                                                                    <input type="text" id="person_linkedin" name="person_linkedin" class="form-control" value="{{ $contact->person_linkedin }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="url">URL:</label>
                                                                                    <input type="text" id="url" name="url" class="form-control" value="{{ $contact->url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="website">Website:</label>
                                                                                    <input type="text" id="website" name="website" class="form-control" value="{{ $contact->website }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_linkedin_url">Company LinkedIn URL:</label>
                                                                                    <input type="text" id="company_linkedin_url" name="company_linkedin_url" class="form-control" value="{{ $contact->company_linkedin_url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="facebook_url">Facebook URL:</label>
                                                                                    <input type="text" id="facebook_url" name="facebook_url" class="form-control" value="{{ $contact->facebook_url }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="twitter_url">Twitter URL:</label>
                                                                                    <input type="text" id="twitter_url" name="twitter_url" class="form-control" value="{{ $contact->twitter_url }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="city">City:</label>
                                                                                    <input type="text" id="city" name="city" class="form-control" value="{{ $contact->city }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="state">State:</label>
                                                                                    <input type="text" id="state" name="state" class="form-control" value="{{ $contact->state }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="country">Country:</label>
                                                                                    <input type="text" id="country" name="country" class="form-control" value="{{ $contact->country }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_address">Company Address:</label>
                                                                                    <input type="text" id="company_address" name="company_address" class="form-control" value="{{ $contact->company_address }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_city">Company City:</label>
                                                                                    <input type="text" id="company_city" name="company_city" class="form-control" value="{{ $contact->company_city }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_state">Company State:</label>
                                                                                    <input type="text" id="company_state" name="company_state" class="form-control" value="{{ $contact->company_state }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_country">Company Country:</label>
                                                                                    <input type="text" id="company_country" name="company_country" class="form-control" value="{{ $contact->company_country }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="company_phone">Company Phone:</label>
                                                                                    <input type="text" id="company_phone" name="company_phone" class="form-control" value="{{ $contact->company_phone }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="seo_description">SEO Description:</label>
                                                                                    <input type="text" id="seo_description" name="seo_description" class="form-control" value="{{ $contact->seo_description }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="technologies">Technologies:</label>
                                                                                    <input type="text" id="technologies" name="technologies" class="form-control" value="{{ $contact->technologies }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="annual_revenue">Annual Revenue:</label>
                                                                                    <input type="text" id="annual_revenue" name="annual_revenue" class="form-control" value="{{ $contact->annual_revenue }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="total_funding">Total Funding:</label>
                                                                                    <input type="text" id="total_funding" name="total_funding" class="form-control" value="{{ $contact->total_funding }}" disabled>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="latest_funding">Latest Funding:</label>
                                                                                    <input type="text" id="latest_funding" name="latest_funding" class="form-control" value="{{ $contact->latest_funding }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="latest_funding_amount">Latest Funding Amount:</label>
                                                                                    <input type="text" id="latest_funding_amount" name="latest_funding_amount" class="form-control" value="{{ $contact->latest_funding_amount }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="last_raised_at">Last Raised At:</label>
                                                                                    <input type="text" id="last_raised_at" name="last_raised_at" class="form-control" value="{{ $contact->last_raised_at }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_sent">Email Sent:</label>
                                                                                    <input type="text" id="email_sent" name="email_sent" class="form-control" value="{{ $contact->email_sent }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_open">Email Open:</label>
                                                                                    <input type="text" id="email_open" name="email_open" class="form-control" value="{{ $contact->email_open }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="email_bounced">Email Bounced:</label>
                                                                                    <input type="text" id="email_bounced" name="email_bounced" class="form-control" value="{{ $contact->email_bounced }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="replied">Replied:</label>
                                                                                    <input type="text" id="replied" name="replied" class="form-control" value="{{ $contact->replied }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="demoed">Demoed:</label>
                                                                                    <input type="text" id="demoed" name="demoed" class="form-control" value="{{ $contact->demoed }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="number_of_retail_locations">Number of Retail Locations:</label>
                                                                                    <input type="text" id="number_of_retail_locations" name="number_of_retail_locations" class="form-control" value="{{ $contact->number_of_retail_locations }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="apollo_contact_id">Apollo Contact ID:</label>
                                                                                    <input type="text" id="apollo_contact_id" name="apollo_contact_id" class="form-control" value="{{ $contact->apollo_contact_id }}" disabled>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <label for="apollo_account_id">Apollo Account ID:</label>
                                                                                    <input type="text" id="apollo_account_id" name="apollo_account_id" class="form-control" value="{{ $contact->apollo_account_id }}" disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $data->links() }}
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="noresult">
                                    <div class="text-center">
                                        <h4 class="mt-2 text-danger">Oops! No Record Found</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .w-5 {
        width: 10px !important;
    }
    .h-5 {
        height: 10px !important;
    }
    .flex.justify-between.flex-1
    {
        display: none !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

