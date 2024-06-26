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
                                {{-- <div class="row mb-3">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100"> <i class="bx bx-filter-alt me-1"></i>Apply</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('contacts.index') }}" class="waves-effect waves-light btn btn-secondary w-100"> <i class="bx bx-crosshair me-1"></i>Remove</a>
                                    </div>
                                </div> --}}

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Name</label>
                                    <input type="text" name="name" id="name" input-filter="name" class="input form-control" value="{{ request()->input('name') }}"><br>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Job Title</label>
                                    <select name="title[]" id="title" data-filter="title" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getTitles() as $title)
                                            <option value="{{$title}}" @if(in_array($title, request()->input('titles', []))) selected @endif>{{$title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Seniority</label>
                                    <select name="seniority[]" id="seniority" data-filter="seniority" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getSeniority() as $seniority)
                                            <option value="{{$seniority}}" @if(in_array($seniority, request()->input('seniority', []))) selected @endif>{{$seniority}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Department</label>
                                    <select name="department[]" id="department" data-filter="department" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getDepartments() as $department)
                                            <option value="{{$department}}" @if(in_array($department, request()->input('departments', []))) selected @endif>{{$department}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Comapny</label>
                                    <select name="company[]" id="company" data-filter="company" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getCompanies() as $company)
                                            <option value="{{$company}}" @if(in_array($company, request()->input('company', []))) selected @endif>{{$company}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Comapny</label>

                                    <p class="card-title-desc font-size-10 mb-0">
                                        <code><b>Exclude companies</b></code>
                                    </p>
                                    <select name="exclude_company[]" id="exclude_company" data-filter="exclude_company" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getCompanies() as $company)
                                            <option value="{{$company}}" @if(in_array($company, request()->input('exclude_company', []))) selected @endif>{{$company}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-0">Person Address</label>
                                    <div class="mb-1">
                                        <select name="city[]" id="city" data-filter="city" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select City">
                                            @foreach (getCities() as $city)
                                                <option value="{{$city}}" @if(in_array($city, request()->input('city', []))) selected @endif>{{$city}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="state[]" id="state" data-filter="state" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select State">
                                            @foreach (getStates() as $state)
                                                <option value="{{$state}}" @if(in_array($state, request()->input('state', []))) selected @endif>{{$state}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="country[]" id="country" data-filter="country" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select Country">
                                            @foreach (getcountries() as $country)
                                                <option value="{{$country}}" @if(in_array($country, request()->input('country', []))) selected @endif>{{$country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Company Address</label>
                                    <div class="mb-1">
                                        <select name="company_city[]" id="company_city" data-filter="company_city" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select City">
                                            @foreach (getCompanyCities() as $company_city)
                                                <option value="{{$company_city}}" @if(in_array($company_city, request()->input('company_city', []))) selected @endif>{{$company_city}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="company_state[]" id="company_state" data-filter="company_state" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select State">
                                            @foreach (getCompanyStates() as $company_state)
                                                <option value="{{$company_state}}" @if(in_array($company_state, request()->input('company_state', []))) selected @endif>{{$company_state}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-1">
                                        <select name="company_country[]" id="company_country" data-filter="company_country" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Select Country">
                                            @foreach (getCompanyCountries() as $company_country)
                                                <option value="{{$company_country}}" @if(in_array($company_country, request()->input('company_country', []))) selected @endif>{{$company_country}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Employees</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_employees" id="from_employees" input-filter="from_employees" class="input form-control" value="{{ request()->input('from_employees') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_employees" id="to_employees" input-filter="to_employees" class="input form-control" value="{{ request()->input('to_employees') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Industry</label>
                                    <select name="industry[]" id="industry" data-filter="industry" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getIndustries() as $industry)
                                            <option value="{{$industry}}" @if(in_array($industry, request()->input('industry', []))) selected @endif>{{$industry}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="keywords">Keywords</label>
                                    <p class="card-title-desc font-size-10 mb-0">Each keyword should be comma seprated (<code><b>,</b></code>)</p>
                                    <textarea name="keywords" id="keywords" input-filter="keywords" class="input form-control">{{ request()->input('keywords') }}</textarea><br>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-0">Technologies</label>
                                    <select name="technologies[]" id="technologies" data-filter="technologies" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getTechnologies() as $technology)
                                            <option value="{{$technology}}" @if(in_array($technology, request()->input('technologies', []))) selected @endif>{{$technology}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Annual Revenue</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_revenue" id="from_revenue" input-filter="from_revenue" class="input form-control" value="{{ request()->input('from_revenue') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_revenue" id="to_revenue" input-filter="to_revenue" class="input form-control" value="{{ request()->input('to_revenue') }}">
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label for="name" class="mb-0">Total Funding</label>
                                    <div class="btn-group btn-group-example mb-3" role="group" bis_skin_checked="1">
                                        <input type="number" name="from_funding" id="from_funding" input-filter="from_funding" class="input form-control" value="{{ request()->input('from_funding') }}">
                                        <span class="bg bg-primary text-light p-2">To</span>
                                        <input type="number" name="to_funding" id="to_funding" input-filter="to_funding" class="input form-control" value="{{ request()->input('to_funding') }}">
                                    </div>
                                </div>

                                {{-- <div class="mb-3">
                                    <label class="form-label mb-0">Lastest Funding Type</label>
                                    <select name="latest_funding[]" data-filter="title" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach ($groupOptions['latest_funding'] as $latest_funding)
                                            <option value="{{$latest_funding}}" @if(in_array($latest_funding, request()->input('latest_funding', []))) selected @endif>{{$latest_funding}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="mb-3">
                                    <label class="form-label mb-0">Email Status</label>
                                    <select name="email_status[]" id="email_status" data-filter="email_status" class="select2 form-control select2-multiple filter" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach (getEmailStatus() as $status)
                                            <option value="{{$status}}" @if(in_array($status, request()->input('email_status', []))) selected @endif>{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Contacts List</h4>
                            <div class="d-flex justify-content-end gap-2" bis_skin_checked="1">
                                <a class="btn btn-primary waves-effect waves-light" id="export-data" href="#"> <i class="bx bx-export me-1"></i> Export Contacts</a>

                                {{-- <a href="{{ route('contacts.create') }}" class="btn btn-primary waves-effect waves-light w-10"> <i class="bx bx-plus me-1"></i> Add New</a> --}}
                            </div>
                            {{-- <div class="card-title-desc card-subtitle" bis_skin_checked="1">Create responsive tables by wrapping any <code>.table</code> in <code>.table-responsive</code>to make them scroll horizontally on small devices (under 768px).</div> --}}

                            <ul class="nav nav-pills nav-justified mt-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#companyTab" role="tab">
                                        <span class="d-none d-sm-block"><i class="bx bx-buildings"></i> Company</span>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#personTab" role="tab">
                                        <span class="d-block d-sm-none"></span>
                                        <span class="d-none d-sm-block"><i class="bx bx-user-circle me-1"></i>Person</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content p-3 text-muted">
                                <div class="tab-pane active" id="companyTab" role="tabpanel">
                                    <div class="table-responsive" bis_skin_checked="1">
                                        <table class="table mb-0 table" id="comapny-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th width="150">Company</th>
                                                    <th>Name</th>
                                                    <th>Title</th>
                                                    {{-- <th>Person Email</th>
                                                    <th>Person Phone</th> --}}
                                                    <th>Email</th>
                                                    <th>Mobile</th>
                                                    <th>Industry</th>
                                                    <th>Employees</th>
                                                    <th>Revenue</th>
                                                    <th>Address</th>
                                                    <th>Website</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="personTab" role="tabpanel">
                                    <div class="table-responsive" bis_skin_checked="1">
                                        <table class="table mb-0 table" id="person-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Name</th>
                                                    <th>Title</th>
                                                    <th width="150">Company</th>
                                                    <th>Email</th>
                                                    <th>Mobile</th>
                                                    <th>Employees</th>
                                                    <th>Revenue</th>
                                                    <th>Address</th>
                                                    <th>Industry</th>
                                                    <th>Website</th>
                                                    <th>Personal LinkedIn</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
<script src="https://cdn.jsdelivr.net/npm/debounce@2.1.0/index.min.js"></script>
<script>

    $(document).ready(function(){
        initDatatable()

        name =null;
        title =null;

        let selectedFilters = {}

        // $(document).on('keyup', '#name', function(){
        //     let name = $(this).val()
        //     console.log('name');
        //     initDatatable(name)
        // })

        $('.input').on('keyup', /*debounce(*/function(e) {
            let value = $(this).val();
            let type  = $(this).attr('input-filter');
            selectedFilters[type] = value;
            console.log(selectedFilters);
            initDatatable(selectedFilters)
        })/*, 400)*/;

        $('.filter').on('select2:select', function(e) {
            let value = $(this).val();
            let type  = $(this).attr('data-filter');
            selectedFilters[type] = value;
            console.log(selectedFilters);
            initDatatable(selectedFilters)
        });

        $(document).on('click','#export-data',function(e) {

            selectedFilters = {};

            $('.input').each(function(i, v) {
                let value = $(v).val();
                let type = $(v).attr('input-filter');
                selectedFilters[type] = value;
            });

            $('.filter').each(function(i, v) {
                let value = $(v).val(); // Use $(v) instead of $(this)
                let type = $(v).attr('data-filter');
                selectedFilters[type] = value;
            });
            // Move the function call outside the loop
            // initDatatable(selectedFilters, 'export');
            $.ajax({
                "url": "{{ route('contacts.export') }}",
                "type": "POST",
                "data":{
                    _token: "{{ csrf_token() }}",
                    filter:selectedFilters
                }
            });

        });

        // $(document).on('keyup', '#name', function(){
        //     let name = $(this).val()
        //     console.log('name');
        //     initDatatable(name)
        // })

        // $(document).on('change', '#title', function(){

        //     let title = $(this).val()
        //     console.log(title);
        //     initDatatable(title)
        // })

        // $('#seniority').on('select2:select', function(e) {
        //     let title = $(this).val();
        //     console.log(title);
        //     initDatatable(title);
        // });
        // $('#seniority').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     console.log(data);
        // });

        // var dropdown = document.getElementById('seniority');
        // $(document).on('change', '#seniority', function(){
        //     let seniority = $(this).val()
        //     initDatatable(seniority)
        // })

        // $(document).on('keyup', '#department', function(){
        //     let department = $(this).val()
        //     initDatatable(department)
        // })

        // $(document).on('keyup', '#company', function(){
        //     let company = $(this).val()
        //     initDatatable(company)
        // })

        // $(document).on('keyup', '#exclude_company', function(){
        //     let exclude_company = $(this).val()
        //     initDatatable(exclude_company)
        // })

        // $(document).on('keyup', '#city', function(){
        //     let city = $(this).val()
        //     initDatatable(city)
        // })

        // $(document).on('keyup', '#state', function(){
        //     let state = $(this).val()
        //     initDatatable(state)
        // })

        // $(document).on('keyup', '#country', function(){
        //     let country = $(this).val()
        //     initDatatable(country)
        // })

        // $(document).on('keyup', '#company_city', function(){
        //     let company_city = $(this).val()
        //     initDatatable(company_city)
        // })

        // $(document).on('keyup', '#company_state', function(){
        //     let company_state = $(this).val()
        //     initDatatable(company_state)
        // })

        // $(document).on('keyup', '#company_country', function(){
        //     let company_country = $(this).val()
        //     console.log(company_country);
        //     initDatatable(company_country)
        // })

        // $(document).on('change', '#company_country', function(){
        //     let company_country = $(this).val()
        //     console.log(company_country);
        //     initDatatable(company_country)
        // })

        // $(document).on('select', '#company_country', function(){
        //     let company_country = $(this).val()
        //     console.log(company_country);
        //     initDatatable(company_country)
        // })

        // $(document).on('keyup', '#from_employees', function(){
        //     let from_employees = $(this).val()
        //     console.log('keyup'+from_employees);
        //     initDatatable(from_employees)
        // })

        // $(document).on('keyup', '#to_employees', function(){
        //     let to_employees = $(this).val()
        //     console.log(to_employees)
        //     initDatatable(to_employees)
        // })

        // $(document).on('keyup', '#industry', function(){
        //     let industry = $(this).val()
        //     initDatatable(industry)
        // })

        // $(document).on('keyup', '#keywords', function(){
        //     let keywords = $(this).val()
        //     initDatatable(keywords)
        // })

        // $(document).on('keyup', '#technologies', function(){
        //     let technologies = $(this).val()
        //     initDatatable(technologies)
        // })

        // $(document).on('keyup', '#from_revenue', function(){
        //     let from_revenue = $(this).val()
        //     initDatatable(from_revenue)
        // })

        // $(document).on('keyup', '#to_revenue', function(){
        //     let to_revenue = $(this).val()
        //     initDatatable(to_revenue)
        // })

        // $(document).on('keyup', '#from_funding', function(){
        //     let from_funding = $(this).val()
        //     initDatatable(from_funding)
        // })

        // $(document).on('keyup', '#to_funding', function(){
        //     let to_funding = $(this).val()
        //     initDatatable(to_funding)
        // })

        // $(document).on('keyup', '#email_status', function(){
        //     let email_status = $(this).val()
        //     initDatatable(email_status)
        // })
    })

    function initDatatable(
        filter=null
        // name=null,title=null, seniority=[], department=[], company=[], exclude_company=[], city=[], state=[], country=[],
        // company_city=[], company_state=[], company_country=[], from_employees=null, to_employees=null, industry=[], keywords=[],
        // technologies=[], from_revenue=null, to_revenue=null, from_funding=null, to_funding=null, email_status=[]
    ){
        $('#comapny-table').DataTable({
            serverSide: true,
            processing: true,
            pageLength: 10,
            searching: false,
            order: [[0, "desc"]],
            ajax: {
                "url": "{{ route('contacts.data') }}",
                "type": "POST",
                "data":{
                    _token: "{{ csrf_token() }}",
                    filter:filter,
                    // name:name,
                    // title:title,
                    // seniority:seniority,
                    // department:department,
                    // company:company,
                    // exclude_company:exclude_company,
                    // city:city,
                    // state:state,
                    // country:country,
                    // company_city:company_city,
                    // company_state:company_state,
                    // company_country:company_country,
                    // from_employees:from_employees,
                    // to_employees:to_employees,
                    // industry:industry,
                    // technologies:technologies,
                    // from_revenue:from_revenue,
                    // to_revenue:to_revenue,
                    // from_funding:from_funding,
                    // to_funding:to_funding,
                    // email_status:email_status
                }
            },
            bDestroy: true,
            columns: [
                { "data": "sr_no" , "orderable": false},
                { "data": "company"},
                { "data": "name"},
                { "data": "title" },
                // { "data": "person_email" },
                // { "data": "person_phone" },
                { "data": "email" },
                { "data": "mobile_phone" },
                { "data": "industry" },
                { "data": "employees" },
                { "data": "annual_revenue" },
                { "data": "address" },
                { "data": "website" , "orderable": false},
            ],
        });
        $('#person-table').DataTable({
            serverSide: true,
            processing: true,
            searching: false,
            pageLength: 10,
            order: [[0, "desc"]],
            ajax: {
                "url": "{{ route('contacts.data') }}",
                "type": "POST",
                "data":{
                    _token: "{{ csrf_token() }}",
                    filter:filter,
                    // name:name,
                    // title:title,
                    // department:department,
                    // company:company,
                    // exclude_company:exclude_company,
                    // city:city,
                    // state:state,
                    // country:country,
                    // company_city:company_city,
                    // company_state:company_state,
                    // company_country:company_country,
                    // from_employees:from_employees,
                    // to_employees:to_employees,
                    // industry:industry,
                    // technologies:technologies,
                    // from_revenue:from_revenue,
                    // to_revenue:to_revenue,
                    // from_funding:from_funding,
                    // to_funding:to_funding,
                    // email_status:email_status
                }
            },
            bDestroy: true,
            columns: [
                { "data": "sr_no", "orderable": false, className: 'text-center'},
                { "data": "name"},
                { "data": "title" },
                { "data": "company"},
                // { "data": "person_email" },
                // { "data": "person_phone" },
                { "data": "email" },
                { "data": "mobile_phone" },
                { "data": "employees" },
                { "data": "annual_revenue" },
                { "data": "address" },
                { "data": "industry" },
                { "data": "website" , "orderable": false},
                { "data": "person_linkedin" , "orderable": false},
            ],
        });
    }
</script>

@endpush

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

