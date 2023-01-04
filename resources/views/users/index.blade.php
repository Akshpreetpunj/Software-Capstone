@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('Users') }}</h2>

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

    {{-- Search --}}
    <div class="my-3 pull-right">
        {{-- Search form for searching the data --}}
        <form class="input-group" action="{{ route('users') }}" method="GET">
            {{ csrf_field() }}

            <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-dark" type="submit">Search</button>

            @if(Request::query('search'))
                <a class="btn btn-danger ml-2" href="{{ route('users') }}"><i class="fa fa-refresh"></i></a>
            @endif
        </form>
    </div>
    
    {{-- Users table for displaying the users data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID
                        <a href="{{ route('users', ['sort' => 'id', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'id' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('users', ['sort' => 'id', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'id' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Name
                        <a href="{{ route('users', ['sort' => 'name', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'name' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('users', ['sort' => 'name', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'name' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Email
                        <a href="{{ route('users', ['sort' => 'email', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'email' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('users', ['sort' => 'email', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'email' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Created at
                        <a href="{{ route('users', ['sort' => 'created_at', 'direction' => 'ASC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-up {{ request('sort') === 'created_at' && request('direction') === 'ASC' ? '' : 'text-muted' }}"></i>
                        </a>
                        <a href="{{ route('users', ['sort' => 'created_at', 'direction' => 'DESC']) }}" class="sort-link btn btn-xs btn-link p-0 m-0 cursor-pointer">
                            <i class="fa fa-arrow-down {{ request('sort') === 'created_at' && request('direction') === 'DESC' ? '' : 'text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                {{-- Checking if there is any users data --}}
                @if(count($users) > 0)
                    @foreach($users as $user)
                        {{-- Displaying the data --}}
                        <tr>
                            <td data-label="ID">{{$user->id}}</td>
                            <td data-label="Name">{{$user->name}}</td>
                            <td data-label="Email">{{$user->email}}</td>
                            <td data-label="Created at">@if($user->created_at != "") {{date('Y-m-d', strtotime($user->created_at))}} @endif</td>
                            
                            @if($user->is_admin == 1)
                                <td data-label="Role"><span class="badge badge-pill badge-warning">Admin</span></td>
                            @else
                                <td data-label="Role"><span class="badge badge-pill badge-primary">User</span></td>
                            @endif

                            <td data-label="Actions">
                                {{-- Delete --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Data?')" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="no-data text-center" colspan="5">No Users Found <i class="fa fa-times-circle"></i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center my-5">
        {!! $users->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection