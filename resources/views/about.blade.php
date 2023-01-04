@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2 class="text-center">{{ __('About Us') }}</h2>

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

    {{-- Detailed Information about the Web Application --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $title }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <p><strong>Hello!</strong> We are Mohawk College Students.</p>
                        <p>
                            We are trying to build a website that will provide skilled trade workers to 
                            advertize about themselves. We realized that there is no proper platform for skilled
                            trade wokers to showcase their skills currently. This website will also help those people
                            who are looking for the skilled trade workers for their businesses and everyday situations.
                            We want to give an opportunity to skilled trade workers and want to deliver their skills to 
                            the people in need on time.
                        </p>

                        <span class="my-2">For more inquiries or questions, please contact: <a href="mailto:akshpreet.punj@mohawkcollege.ca">akshpreet.punj@mohawkcollege.ca</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection