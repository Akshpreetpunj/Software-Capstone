@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('My Advertisements') }}</h2>

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
                <form class="form-inline" action="{{ route('my-ads') }}" id="category_filter_form" method="GET">
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
                <form class="form-inline" action="{{ route('my-ads') }}" id="sort_filter_form" method="GET">
                    {{ csrf_field() }}
                    <select class="form-select" id="sortby" name="sortby">
                        <option disabled selected value="">Sort By</option>
                        <option value="latest">Latest</option>
                        <option value="oldest">Oldest</option>
                        <option value="title_asc">A-Z</option>
                        <option value="title_desc">Z-A</option>
                        <option value="price_asc">Price - Low to High</option>
                        <option value="price_desc">Price - High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Search --}}
        <div class="col-lg-7">
            {{-- Search form for searching the data --}}
            <div class="my-3 pull-right">
                <form class="input-group" action="{{ route('my-ads') }}" method="GET">
                    {{ csrf_field() }}

                    <input class="form-control mr-lg-2" name="search" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-dark" type="submit">Search</button>

                    @if(Request::query('search'))
                        <a class="btn btn-danger ml-2" href="{{ route('my-ads') }}"><i class="fa fa-refresh"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Advertisement table for displaying the Advertisement data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Image</th>
                    <th>Category
                        <a href="{{ route('my-ads', ['sort' => 'skilled_trades', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'skilled_trades' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'skilled_trades', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'skilled_trades' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Ad Title
                        <a href="{{ route('my-ads', ['sort' => 'title', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'title' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'title', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'title' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Description
                        <a href="{{ route('my-ads', ['sort' => 'description', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'description' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'description', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'description' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Price/hr
                        <a href="{{ route('my-ads', ['sort' => 'price', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'price' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'price', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'price' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Address
                        <a href="{{ route('my-ads', ['sort' => 'address', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'address' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'address', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'address' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Phone Number
                        <a href="{{ route('my-ads', ['sort' => 'phone', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'phone' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'phone', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'phone' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Views
                        <a href="{{ route('my-ads', ['sort' => 'views', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'views' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('my-ads', ['sort' => 'views', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'views' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                {{-- Checking if there is any advertisement data --}}
                @if(count($advertisements) > 0)
                    @foreach($advertisements as $advertisement)
                        {{-- Displaying the data --}}
                        <tr>
                            <td data-label="Image">
                                @if($advertisement->image)
                                    <img src="{{ Storage::disk('s3')->url('images/' . $advertisement->image) }}" width="100px" height="80px">
                                @else
                                    <img src="{{ asset('images/placeholder.gif') }}" width="100px" heigth="80px">
                                @endif
                            </td>
                            <td data-label="Category">{{$advertisement->skilled_trades}}</td>
                            <td data-label="Ad Title"><a href="{{ route('my-ads.show', $advertisement->id) }}">{{$advertisement->title}}</a></td>
                            <td data-label="Description">{{ Str::limit($advertisement->description, 50) }}</td>
                            <td data-label="Price/hr">${{$advertisement->price}}</td>
                            <td data-label="Address">{{$advertisement->address}}</td>
                            <td data-label="Phone Number">{{$advertisement->phone}}</td>
                            <td data-label="Views">{{$advertisement->views}}</td>
                            
                            <td data-label="Actions">
                                {{-- Show --}}
                                <a href="{{ route('my-ads.show', $advertisement->id) }}" type="button" class="btn btn-info btn-sm d-inline mb-1"><i class="fa fa-eye"></i> View</a>
                                
                                {{-- Edit --}}
                                <a href="{{ route('my-ads.edit', $advertisement->id) }}" type="button" class="btn btn-primary btn-sm d-inline mb-1"><i class="fa fa-edit"></i> Edit</a>
    
                                {{-- Delete --}}
                                <form action="{{ route('my-ads.destroy', $advertisement->id) }}" method="POST" class="d-inline mb-1">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td class="no-data text-center" colspan="9">No Advertisements Found <i class="fa fa-times-circle"></i></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center my-5">
        {!! $advertisements->appends(\Request::except('page'))->render() !!}
    </div>
</div>

<script>
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