@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('Settings') }}</h2>

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
    
    {{-- Add Banner & Announcement button --}}
    <div class="text-right">
        <a href="/settings/create" type="button" class="btn btn-success btn-sm mb-3"><i class="fa fa-plus"></i> Add Banners & Announcements</a>
    </div>

    {{-- Search --}}
    <div class="my-3 pull-right">
        {{-- Search form for searching the data --}}
        <form class="input-group" action="{{ route('settings') }}" method="GET">
            {{ csrf_field() }}

            <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-dark" type="submit">Search</button>

            @if(Request::query('search'))
                <a class="btn btn-danger ml-2" href="{{ route('settings') }}"><i class="fa fa-refresh"></i></a>
            @endif
        </form>
    </div>

    {{-- Settings Table for displaying the Banner image & Announcement --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Banner Image</th>
                    <th>Announcement
                        <a href="{{ route('settings', ['sort' => 'announcement', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'announcement' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('settings', ['sort' => 'announcement', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'announcement' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Publish Status
                        <a href="{{ route('settings', ['sort' => 'publish_status', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'publish_status' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('settings', ['sort' => 'publish_status', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'publish_status' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Checking if there is any settings data --}}
                @if(count($settings) > 0)
                    @foreach($settings as $setting)
                        {{-- Displaying the data --}}
                        <tr>
                            <td data-label="Banner Image">
                                @if($setting->banner_image)
                                    <img src="{{ Storage::disk('s3')->url('banner_images/' . $setting->banner_image) }}" width="100px" height="50px">
                                @else
                                    <img src="{{ asset('images/placeholder.gif') }}" width="100px" height="50px">
                                @endif
                            </td>
                            <td data-label="Announcement">{{$setting->announcement}}</td>
                            <td data-label="Publish Status">{{$setting->publish_status}}</td>
                            <td data-label="Actions">
                                <a href="{{ route('settings.edit', $setting->id) }}" type="button" class="btn btn-primary btn-sm d-inline mb-1"><i class="fa fa-edit"></i> Edit</a>
                                
                                <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm mb-1" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="no-data text-center" colspan="4">No Data Found <i class="fa fa-times-circle"></i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center my-5">
        {!! $settings->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection