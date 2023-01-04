@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('Trade Advertisements') }}</h2>

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

    <div class="row">
        {{-- Category Filter --}}
        <div class="col-lg-3">
            <div class="my-3">
                <form class="form-inline" action="{{ route('trade') }}" id="category_filter_form" method="GET">
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

        {{-- Sort Filter --}}
        <div class="col-lg-2">
            <div class="my-3">
                <form class="form-inline" action="{{ route('trade') }}" id="sort_filter_form" method="GET">
                    {{ csrf_field() }}

                    <select class="form-select" id="sortby" name="sortby">
                        <option disabled selected value="">Sort By</option>
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="title_asc">A-Z</option>
                        <option value="title_desc">Z-A</option>
                        <option value="rating_asc">Rating - Low to High</option>
                        <option value="rating_desc">Rating - High to Low</option>
                        <option value="price_asc">Price - Low to High</option>
                        <option value="price_desc">Price - High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Search --}}
        <div class="col-lg-7">
            <div class="my-3 pull-right">
                {{-- Search form for searching the data --}}
                <form class="input-group" action="{{ route('trade') }}" method="GET">
                    {{ csrf_field() }}

                    <input class="form-control mr-lg-2" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-dark" type="submit">Search</button>

                    @if(Request::query('search'))
                        <a class="btn btn-danger ml-2" href="{{ route('trade') }}"><i class="fa fa-refresh"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Displaying the Advertisement data --}}
    <div class="mt-5">
        {{-- Checking if there is any advertisement data --}}
        @if(count($advertisements) > 0)
            @foreach($advertisements as $advertisement)
                {{-- Displaying the data --}}
                <div class="d-flex flex-column justify-content-center">
                    <div class="row">
                        <div class="col">
                            <div class="trade-items card card-body mb-3 shadow py-2 mb-2 rounded">
                                <div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
                                
                                    {{-- Advertisement Image --}}
                                    <div class="col-sm">
                                        <div class="mr-2 mb-3 mb-lg-0">
                                            @if($advertisement->image)
                                                <img class="border border-dark" alt="image" src="{{ Storage::disk('s3')->url('images/' . $advertisement->image) }}" width="120" height="120">
                                            @else
                                                <img class="border border-dark" alt="default image" src="{{ asset('images/placeholder.gif') }}" width="120" height="120">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="media-body">
                                            {{-- Advertisement Title --}}
                                            <h3 class="media-title font-weight-semibold">
                                                <a href="{{ route('trade.show', $advertisement->id) }}">{{$advertisement->title}}</a>
                                            </h3>

                                            {{-- Advertisement Time --}}
                                            <p class="badge badge-pill badge-secondary">{{ \Carbon\Carbon::parse($advertisement->created_at)->diffForHumans() }}</p>

                                            {{-- Advertisement Description --}}
                                            <p class="mb-3">{{ Str::limit($advertisement->description, 100) }}</p>
                                        </div>
                                    </div>

                                    <div class="col-sm">
                                        <div class="mt-3 mt-lg-0 ml-lg-3">
                                            {{-- Advertisement Price --}}
                                            <p class="font-weight-bold">${{$advertisement->price}}/hr</p>

                                            {{-- Advertisement Rating --}}
                                            <div>
                                                <input id="input-1-ltr-star-xs" name="rate" class="kv-ltr-theme-svg-star rating-loading" value="{{ $advertisement->averageRating }}" dir="ltr" data-size="xs" data-disabled="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center">
                <figure>
                    <img class="img-fluid" alt="search image" src="{{ asset('images/search-result.jpg') }}" >
                </figure>

                <figcaption>
                    <span class="text-center">No Results Found <i class="fa fa-times-circle"></i></p>
                </figcaption>
            </div>
        @endif

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            <span class="lead">Showing {{ $advertisements->firstItem() }} - {{ $advertisements->lastItem() }} of total {{ $advertisements->total() }} entries</span>
        </div>

        <div class="d-flex justify-content-center mt-2">
            {!! $advertisements->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.kv-ltr-theme-svg-star').rating({
            hoverOnClear: false,
            theme: 'krajee-svg'
        });
    });

    // Category Filter Form Submission
    $('#category_filter_form').change('#category_filter', function(e) {
        $(this).submit();
    });

    // Sort Filter Form Submission
    $('#sort_filter_form').change('#sortby', function(e) {
        $(this).submit();
    });
</script>

@endsection