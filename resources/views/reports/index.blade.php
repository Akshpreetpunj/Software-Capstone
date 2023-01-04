@extends('layouts.app')

@section('content')
<div class="container my-4">
    {{-- Displaying success message --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Displaying error message --}}
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Displaying regular message --}}
    @if(session()->has('message'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Checking if the authenticated user is Admin --}}
    @if(Auth::user()->is_admin)
        <h2 class="text-center">{{ __('Report Charts') }}</h2>

        <div class="row justify-content-center">
            {{-- User Registration Overview Chart --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">User Registration Overview</div>

                    <div class="card-body">
                        <canvas id="chart" height="150px" width="auto"></canvas>

                        {{-- Chart Js --}}
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
                        <script type="text/javascript">
                        
                            var datas =  <?php echo json_encode($datas) ?>;

                            console.log(datas);
                        
                            const data = {
                                labels: ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                label: 'New Users',
                                backgroundColor: 'rgb(0, 191, 255)',
                                borderColor: 'rgb(0, 64, 255)',
                                data: datas,
                                }]
                            };
                        
                            const config = {
                                type: 'bar',
                                data: data,
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            title: {
                                                display:true,
                                                text: 'Months'
                                            },
                                            ticks: {
                                                beginsAtZero: true
                                            }
                                        },
                                        y: {
                                            title: {
                                                display:true,
                                                text: 'User Count'
                                            },
                                            ticks: {
                                                beginsAtZero: true,
                                                stepSize: 1,
                                            },
                                            suggestedMax: 10,
                                        }
                                    },
                                    elements: {
                                        bar: {
                                            borderWidth: 3,
                                        }
                                    },
                                    plugins:{
                                        title: {
                                            display: true,
                                            text: 'Registered Users Month Wise, 2022'
                                        }
                                    }
                                }
                            };
                        
                            const myChart = new Chart(
                                document.getElementById('chart'),
                                config
                            );
                        </script>
                    </div>
                </div>
            </div>

            {{-- Advertisement Overview Chart --}}
            <div class="col-md-8">
                <div class="card my-4">
                    <div class="card-header">Advertisement Overview</div>

                    <div class="card-body">
                        <canvas id="chart1" height="150px" width="auto"></canvas>

                        <script type="text/javascript">
                        
                            var advertisement_datas =  <?php echo json_encode($advertisement_datas) ?>;

                            console.log(advertisement_datas);
                        
                            const advertisement_data = {
                                labels: ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                label: 'New Advertisements',
                                backgroundColor: 'rgb(255, 0, 128)',
                                borderColor: 'rgb(255, 0, 0)',
                                data: advertisement_datas,
                                }]
                            };
                        
                            const advertisement_config = {
                                type: 'line',
                                data: advertisement_data,
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            title: {
                                                display:true,
                                                text: 'Months'
                                            },
                                            ticks: {
                                                beginsAtZero: true
                                            }
                                        },
                                        y: {
                                            title: {
                                                display:true,
                                                text: 'Advertisement Count'
                                            },
                                            ticks: {
                                                beginsAtZero: true,
                                                stepSize: 1,
                                            },
                                            suggestedMax: 10,
                                        }
                                    },
                                    plugins:{
                                        title: {
                                            display: true,
                                            text: 'New Advertisement Count Month Wise, 2022'
                                        }
                                    }
                                }
                            };
                        
                            const myChart1 = new Chart(
                                document.getElementById('chart1'),
                                advertisement_config
                            );
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @else
        <h2 class="text-center">{{ __('Bookings Report') }}</h2>

        {{-- Date Filter --}}
        <div class="row mb-3">
            <form class="input-group" action="{{ route('reports') }}" method="GET">
                {{ csrf_field() }}

                {{-- From Date --}}
                <label for="from_date" class="col-form-label col-md text-md-end">{{ __('From Date') }} </label>
                <div class="col-md-4">
                    <input id="from_date" type="date" class="form-control input-md" name="from_date" placeholder="From Date" pattern="\d{4}-\d{2}-\d{2}" required autofocus>
                </div>
                
                {{-- To Date --}}
                <label for="to_date" class="col-form-label col-md text-md-end">{{ __('To Date') }} </label>
                <div class="col-md-4">
                    <input id="to_date" type="date" class="form-control input-md" name="to_date" placeholder="To Date" pattern="\d{4}-\d{2}-\d{2}" required autofocus>
                </div>

                <button class="btn btn-success" type="submit">Filter</button>

                @if(Request::query('from_date') && Request::query('to_date'))
                    <a class="btn btn-danger ml-2" href="{{ route('reports') }}"><i class="fa fa-refresh"></i></a>
                @endif
            </form>
        </div>

        {{-- Search --}}
        <div class="my-3 pull-right">
            {{-- Search form for searching the data --}}
            <form class="input-group" action="{{ route('reports') }}" method="GET">
                {{ csrf_field() }}
                <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">

                <button class="btn btn-dark" type="submit">Search</button>

                @if(Request::query('search'))
                    <a class="btn btn-danger ml-2" href="{{ route('reports') }}"><i class="fa fa-refresh"></i></a>
                @endif
            </form>
        </div>

        {{-- Reports table for displaying the bookings report Data --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Booking Advertisement</th>
                        <th>Client Name</th>
                        <th>Booking Date</th>
                        <th>Service Rate</th>
                        <th>Service Time</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Checking if there is any bookings report data --}}
                    @if(count($bookings) > 0)
                        @foreach($bookings as $booking)
                            {{-- Displaying the data --}}
                            <tr>
                                <td data-label="No">{{$loop->iteration}}</td>
                                <td data-label="Booking Advertisement"><a href="{{ route('trade.show', $booking->advertisement->id) }}">{{$booking->advertisement->title}}</a></td>
                                <td data-label="Client Name">{{$booking->name}}</td>
                                <td data-label="Booking Date">{{$booking->date}}</td>
                                <td data-label="Service Rate">{{$booking->advertisement->price}} /hr</td>
                                <td data-label="Service Time">{{\Carbon\Carbon::parse($booking->end_time)->diffInMinutes(\Carbon\Carbon::parse($booking->start_time)) / 60}} hours </td>
                                <td data-label="Total Income">${{$booking->advertisement->price * \Carbon\Carbon::parse($booking->end_time)->diffInMinutes(\Carbon\Carbon::parse($booking->start_time)) / 60}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="no-data text-center" colspan="7">No Reports Found <i class="fa fa-times-circle"></i></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection