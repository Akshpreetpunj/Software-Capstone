@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('My Favourites') }}</h2>

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

    {{-- Category Filter --}}
    <div class="row">
        <div class="col-lg-3">
            <div class="my-3">
                <form class="form-inline" action="{{ route('my-favourites') }}" id="category_filter_form" method="GET">
                    {{ csrf_field() }}

                    <select class="form-select" id="category_filter" name="category_filter">
                        <option disabled selected value="">Select Category</option>
                        <option value="carpenter">Carpenter</option>
                        <option value="electrician">Electrician</option>
                        <option value="plumber">Plumber</option>
                        <option value="other">Other</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Search --}}
        <div class="col-lg-9">
            <div class="my-3 pull-right">
                {{-- Search form for searching the data --}}
                <form class="input-group" action="{{ route('my-favourites') }}" method="GET">
                    {{ csrf_field() }}

                    <input class="form-control mr-lg-2" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-dark" type="submit">Search</button>

                    @if(Request::query('search'))
                        <a class="btn btn-danger ml-2" href="{{ route('my-favourites') }}"><i class="fa fa-refresh"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Favourites table for displaying the Favourites data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Image</th>
                    <th>Category
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.skilled_trades', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'advertisements.skilled_trades' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.skilled_trades', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'advertisements.skilled_trades' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Ad Title
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.title', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'advertisements.title' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.title', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'advertisements.title' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Price/hr
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.price', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'advertisements.price' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.price', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'advertisements.price' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Posted
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.created_at', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'advertisements.created_at' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-favourites', ['sort' => 'advertisements.created_at', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'advertisements.created_at' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Date Added
                        <a href="{{ route('my-favourites', ['sort' => 'favourites.created_at', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'favourites.created_at' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-favourites', ['sort' => 'favourites.created_at', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'favourites.created_at' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            
            <tbody>
                {{-- Checking if there is any favourites data --}}
                @if(count($favourites) > 0)
                    @foreach($favourites as $favourite)
                        {{-- Displaying the data --}}
                        <tr>
                            <td data-label="Image">
                                @if($favourite->advertisement->image)
                                    <img src="{{ Storage::disk('s3')->url('images/' . $favourite->advertisement->image) }}" width="100px" height="80px">
                                @else
                                    <img src="{{ asset('images/placeholder.gif') }}" width="100px" height="80px">
                                @endif
                            </td>
                            <td data-label="Category">{{$favourite->advertisement->skilled_trades}}</td>
                            <td data-label="Ad Title"><a href="{{ route('trade.show', $favourite->advertisement->id) }}">{{$favourite->advertisement->title}}</a></td>
                            <td data-label="Price/hr">${{$favourite->advertisement->price}}</td>
                            <td data-label="Posted">@if($favourite->advertisement->created_at != "") {{date('Y-m-d', strtotime($favourite->advertisement->created_at))}} @endif</td>
                            <td data-label="Date Added">@if($favourite->created_at != "") {{date('Y-m-d', strtotime($favourite->created_at))}} @endif</td>
                            
                            <td data-label="Actions">
                                {{-- Delete --}}
                                <form action="{{ route('my-favourites.destroy', $favourite->advertisement->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash d-inline"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="no-data text-center" colspan="7">No Favourites Ads <i class="fa fa-times-circle"></i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center my-5">
        {!! $favourites->appends(\Request::except('page'))->render() !!}
    </div>
</div>

<script>
    // Category Filter Form Submission
    $('#category_filter_form').change('#category_filter', function(e) {
        $(this).submit();
    });
</script>

@endsection
