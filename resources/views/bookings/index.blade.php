@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('Appointment Bookings') }}</h2>

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

    {{-- Post Advertisement button --}}
    <div class="text-right">
        <a href="/my-ads/create" type="button" class="btn btn-success btn-sm mb-3"><i class="fa fa-plus"></i> Post ad</a>
    </div>

    {{-- Booking Status Filter --}}
    <div class="row">
        <div class="col-lg-3">
            <div class="my-3">
                <form class="form-inline" action="{{ route('my-bookings') }}" id="status_filter_form" method="GET">
                    {{ csrf_field() }}

                    <select class="form-select" id="status_filter" name="status_filter">
                        <option disabled selected value="">Select Booking Status</option>
                        <option value="Approved">Approved</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="In progress">Pending/In Progress</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Search --}}
        <div class="col-lg-9">
            <div class="my-3 pull-right">
                {{-- Search form for searching the data --}}
                <form class="input-group" action="{{ route('my-bookings') }}" method="GET">
                    {{ csrf_field() }}

                    <input class="form-control mr-lg-2" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-dark" type="submit">Search</button>

                    @if(Request::query('search'))
                        <a class="btn btn-danger ml-2" href="{{ route('my-bookings') }}"><i class="fa fa-refresh"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Bookings table for displaying the Appointment Booking data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th><div class="break">Client</div>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.name', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'bookings.name' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.name', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'bookings.name' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Employee</div>
                        <a href="{{ route('my-bookings', ['sort' => 'users.name', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'users.name' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'users.name', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'users.name' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Address</div>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.address', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'bookings.address' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.address', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'bookings.address' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Phone Number</div>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.phone', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'bookings.phone' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'bookings.phone', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'bookings.phone' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Booking Advertisement</div>
                        <a href="{{ route('my-bookings', ['sort' => 'title', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'title' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'title', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'title' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Date</div>
                        <a href="{{ route('my-bookings', ['sort' => 'date', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'date' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'date', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'date' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Start Time</div>
                        <a href="{{ route('my-bookings', ['sort' => 'start_time', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'start_time' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'start_time', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'start_time' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">End Time</div>
                        <a href="{{ route('my-bookings', ['sort' => 'end_time', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'end_time' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'end_time', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'end_time' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Message</div>
                        <a href="{{ route('my-bookings', ['sort' => 'message', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'message' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'message', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'message' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Status</div>
                        <a href="{{ route('my-bookings', ['sort' => 'status', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'status' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-bookings', ['sort' => 'status', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'status' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th><div class="break">Actions</div></th>
                </tr>
            </thead>
            <tbody>
                {{-- Checking if there is any bookings data --}}
                @if(count($client_bookings) > 0 || count($skilled_trade_bookings) > 0)
                    @if(count($client_bookings) > 0)
                        @foreach($client_bookings as $client_booking)
                            {{-- Displaying the data to the Client --}}
                            <tr>
                                <td data-label="Client">{{$client_booking->name}}</td>
                                <td data-label="Employee">{{$client_booking->advertisement->user->name}}</td>
                                <td data-label="Address">{{$client_booking->address}}</td>
                                <td data-label="Phone Number">{{$client_booking->phone}}</td>
                                <td data-label="Booking Advertisement"><a href="{{ route('trade.show', $client_booking->advertisement->id) }}">{{$client_booking->advertisement->title}}</a></td>
                                <td data-label="Date">{{$client_booking->date}}</td>
                                <td data-label="Start Time">{{date('g:i a', strtotime($client_booking->start_time))}}</td>
                                <td data-label="End Time">{{date('g:i a', strtotime($client_booking->end_time))}}</td>
                                <td data-label="Message">{{$client_booking->message}}</td>
                                <td data-label="Status">{{$client_booking->status}}</td>

                                <td data-label="Actions">
                                    {{-- Booking Cancel --}}
                                    @if($client_booking->status !== 'Cancelled')
                                        <a href="{{ route('my-bookings.cancel', $client_booking->id) }}" type="button" class="btn btn-warning btn-sm d-inline mb-1"><i class="fa fa-times"></i> Cancel</a>
                                    @endif

                                    {{-- Booking Delete --}}
                                    <form action="{{ route('bookings.destroy', $client_booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm mb-1" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if(count($skilled_trade_bookings) > 0)
                        @foreach($skilled_trade_bookings as $skilled_trade_booking)
                            {{-- Displaying the data to the Employee (Skilled Trades Worker) --}}
                            <tr>
                                <td data-label="Client">{{$skilled_trade_booking->name}}</td>
                                <td data-label="Employee">{{$skilled_trade_booking->advertisement->user->name}}</td>
                                <td data-label="Address">{{$skilled_trade_booking->address}}</td>
                                <td data-label="Phone Number">{{$skilled_trade_booking->phone}}</td>
                                <td data-label="Booking Advertisement"><a href="{{ route('trade.show', $skilled_trade_booking->advertisement->id) }}">{{$skilled_trade_booking->advertisement->title}}</a></td>
                                <td data-label="Date">{{$skilled_trade_booking->date}}</td>
                                <td data-label="Start Time">{{date('g:i a', strtotime($skilled_trade_booking->start_time))}}</td>
                                <td data-label="End Time">{{date('g:i a', strtotime($skilled_trade_booking->end_time))}}</td>
                                <td data-label="Message">{{$skilled_trade_booking->message}}</td>
                                <td data-label="Status">{{$skilled_trade_booking->status}}</td>

                                <td data-label="Actions">
                                    {{-- Booking Approve --}}
                                    @if($skilled_trade_booking->status == 'In progress')
                                        <a href="{{ route('my-bookings.approve', $skilled_trade_booking->id) }}" type="button" class="btn btn-success btn-sm d-inline mb-1"><i class="fa fa-check"></i> Approve</a>
                                    @endif    

                                    {{-- Booking Cancel --}}
                                    @if($skilled_trade_booking->status !== 'Cancelled')
                                        <a href="{{ route('my-bookings.cancel', $skilled_trade_booking->id) }}" type="button" class="btn btn-warning btn-sm d-inline mb-1"><i class="fa fa-times"></i> Cancel</a>
                                    @endif

                                    {{-- Booking Delete --}}
                                    <form action="{{ route('bookings.destroy', $skilled_trade_booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm mb-1" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td class="no-data text-center" colspan="11">No Bookings Found <i class="fa fa-times-circle"></i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(count($client_bookings) > 0)
        <div class="d-flex justify-content-center my-5">
            {!! $client_bookings->appends(\Request::except('page'))->render() !!}
        </div>
    @endif

    @if(count($skilled_trade_bookings) > 0)
        <div class="d-flex justify-content-center my-5">
            {!! $skilled_trade_bookings->appends(\Request::except('page'))->render() !!}
        </div>
    @endif
</div>

<script>
    // Status Filter Form Submission
    $('#status_filter_form').change('#status_filter', function(e) {
        $(this).submit();
    });
</script>
@endsection