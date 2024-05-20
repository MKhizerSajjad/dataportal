@extends('layouts.app')

@section('content')
{{-- @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif --}}

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">User Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Layouts</a></li>
                                <li class="px-1"> > </li>
                                <li class="breadcrumb-item active">User Dashboard</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-3">
                                        <h5 class="text-primary">Welcome Back <b>{{ Auth::user()->first_name }}!</b></h5>
                                        <p>Data Portal Dashboard</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('images/profile-img.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <img src="{{ asset('images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                                    </div>
                                    {{-- <h5 class="font-size-15 text-truncate">{{ Auth::user()->first_name .' '. Auth::user()->last_name }}</h5> --}}
                                    {{-- <p class="text-muted mb-0 text-truncate">UI/UX Designer</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Usage</h4>
                            <div id="tab3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="row">

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Realtime Monitor</h4>
                                    <div class="text-muted text-center">
                                        <h4 class="mb-3 mt-4">INTERNET SPEED</h4>
                                        <p class="mb-3">
                                            <span class="badge badge-soft-primary font-size-11 me-2"> {{$data['upload_speed']}} MB <i class="mdi mdi-arrow-up"></i> </span> Uploading
                                        </p>
                                        <p class="mb-2">
                                            <span class="badge badge-soft-success font-size-11 me-2"> {{$data['download_speed']}} MB <i class="mdi mdi-arrow-down"></i> </span> Downloading
                                        </p>
                                    </div>

                                    <div class="table-responsive mt-4">
                                        <table class="table align-middle mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h1 class="font-size-14 mb-1">Memory</h1>
                                                        <!-- <p class="text-muted mb-0">7.9% used</p> -->
                                                    </td>

                                                    <td>
                                                        <div id="radialchart-1" data-colors='["--bs-primary"]' class="apex-charts"></div>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">Available</p>
                                                        <h5 class="mb-0">{{$data['memory_info']['memory_percent']}} %</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h1 class="font-size-14 mb-1">Disk</h1>
                                                        <!-- <p class="text-muted mb-0">24.9% used</p> -->
                                                    </td>

                                                    <td>
                                                        <div id="radialchart-2" data-colors='["--bs-success"]' class="apex-charts"></div>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">Available</p>
                                                        <h5 class="mb-0">{{$data['memory_info']['disk_percent']}} %</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h1 class="font-size-14 mb-1">CPU</h1>
                                                        <!-- <p class="text-muted mb-0">10.5% used</p> -->
                                                    </td>
                                                    <td>
                                                        <div id="radialchart-3" data-colors='["--bs-success"]' class="apex-charts"></div>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">Available</p>
                                                        <h5 class="mb-0">{{$data['cpu_info']['cpu_percent']}} %</h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="me-2">
                                            <h5 class="card-title mb-4">Packages History</h5>
                                        </div>
                                        <div class="dropdown ms-auto">
                                            {{-- <a class="text-muted font-size-16" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a> --}}

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Separated link</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-simplebar class="mt-2" style="max-height: 280px;">
                                        <ul class="verti-timeline list-unstyled">
                                            <li class="event-list active">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bxs-right-arrow-circle font-size-18 bx-fade-right"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">01 May <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Renew Package
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">02 March <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Package upgrade
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">02 Feb <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Register a new pakage
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="text-center mt-4"><a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // var options = {
        //     series: [92.1],
        //     chart: {
        //         height: 100,
        //         type: 'radialBar',
        //     },
        //     plotOptions: {
        //         radialBar: {
        //             hollow: {
        //                 size: '35%',
        //             }
        //         },
        //     },
        //     colors: ['#FF5733'],
        //     labels: [''],
        // };

        // // Initialize the chart
        // var chart = new ApexCharts(document.querySelector("#chartTesting"), options);
        // chart.render();
        var available = {{$data['cpu_info']['cpu_percent']}};
        var used = {{100 - $data['cpu_info']['cpu_percent']}};
        var options = {
        series: [ available, used],
        chart: {
        width: 305,
        type: 'donut',
        },
        plotOptions: {
        pie: {
            // startAngle: -90,
            // endAngle: 270
        }
        },
        dataLabels: {
        enabled: false
        },
        fill: {
        //   type: 'gradient',
        },
        legend: {
        formatter: function(val, opts) {
            return val + " - " + opts.w.globals.series[opts.seriesIndex]
        }
        },
        responsive: [{
        breakpoint: 480,
        options: {
            chart: {
            width: 200
            },
            legend: {
            position: 'bottom'
            }
        }
        }],
        colors: ['#86ea7e', '#f17f7f'],
        labels: ['Available', 'Used']
        };

        var chart = new ApexCharts(document.querySelector("#tab3"), options);
        chart.render();

    </script>
@endsection
