@extends('layouts.app')

@section('content')

<div class="container my-4">

    {{-- Slider (Banner) --}}
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @if(count($settings) > 0)
                @foreach($settings as $index => $setting)
                    <div class="carousel-item @if($index==0) active @endif" height="300px">
                        <img src="{{ Storage::disk('s3')->url('banner_images/' . $setting->banner_image) }}" class="d-block w-100" alt="banner image">
                    </div>
                @endforeach
            @else
                <div class="carousel-item active" height="400px">
                    <img src="{{ asset('images/banner_default.jpg') }}" class="d-block w-100" alt="banner default image" width="1024px" height="400px">
                </div>
            @endif
        </div>

        {{-- Previous --}}
        <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </button>

        {{-- Next --}}
        <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </button>
    </div>

    {{-- News (Announcement) --}}
    @if(count($settings) > 0)
        @foreach($settings as $setting)
            @isset($setting->announcement)
            <div class="alert alert-info alert-dismissible fade show text-center my-3" role="alert">
                <strong>{{$setting->announcement}}</strong>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endisset
        @endforeach
    @else
        {{-- Default Announcement --}}
        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <strong>There is no announcement currently.</strong>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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

    {{-- Information about the Web Application --}}
    <h1 class="text-center my-3">Skilled Trades Business Management System</h1>
    
    <p class="text-center">Welcome to the skilled trades business management web application!</p>
    <p class="text-center">By using this app, you can search and post Advertisements related to the skilled trade jobs.</p>

</div>
@endsection