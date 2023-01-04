@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2>Advertisement Details</h2>
            </div>
        </div>
    </div>

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

    <div class="row justify-content-center">
        <div class="col">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    {{-- Back button --}}
                    <div class="text-left">
                        <a class="float-left btn btn-primary btn-sm" href="{{ route('trade') }}"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                
                {{-- Detailed Information about the Advertisement --}}
                <div class="card-body text-dark details-items">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="text-center">
                                {{-- Advertisement Image --}}
                                <figure>
                                    @if($advertisement->image)
                                        <img class="border border-dark hoverable" alt="image"src="{{ Storage::disk('s3')->url('images/' . $advertisement->image) }}" alt="image" width="350px" height="300px">
                                    @else
                                        <img class="border border-dark hoverable" alt="default image"src="{{ asset('images/placeholder.gif') }}" width="350px" height="300px">
                                    @endif
                                </figure>

                                {{-- Advertisement Time --}}
                                <p class="badge badge-pill badge-secondary">{{ \Carbon\Carbon::parse($advertisement->created_at)->diffForHumans() }}</p>

                                {{-- Advertisement Rating Form --}}
                                <form class="form-horizontal" action="{{route('advertisementRating', $advertisement->id)}}" id="addRating" method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-group required">
                                        <input id="input-1-ltr-star-sm" name="rate" class="kv-ltr-theme-svg-star rating-loading" data-min="0" data-max="5" data-step="1" value="{{ $advertisement->averageRating }}" dir="ltr" data-size="sm">
                                        <input type="hidden" name="advertisement_id" value="{{ $advertisement->id }}">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            {{-- Advertisement Title --}}
                            <div class="mb-2">
                                <h3>{{$advertisement->title}}</h3>
                                <hr class="my-0" />
                            </div>
                            
                            {{-- Advertisement Rating --}}
                            <div class="text-left mb-2">
                                <span><strong>Rating:</strong> Average {{ round($advertisement->averageRating, 2) }} / 5 ({{ $advertisement->timesRated() }} votes)</span>
                                <hr class="my-0" />
                            </div>

                            {{-- Skilled Trades --}}
                            <div class="text-left mb-2">
                                <strong>Skilled Trades:</strong>
                                {{$advertisement->skilled_trades}}
                                <hr class="my-0" />
                            </div>

                            {{-- User Name --}}
                            <div class="text-left mb-2">
                                <strong>Name:</strong>
                                {{ $advertisement->user->name }}
                                <hr class="my-0" />
                            </div>

                            {{-- Phone Number --}}
                            <div class="text-left mb-2">
                                <strong>Phone Number:</strong>
                                {{$advertisement->phone}}
                                <hr class="my-0" />
                            </div>

                            {{-- Email Address --}}
                            <div class="text-left mb-2">
                                <strong>Email:</strong>
                                {{ $advertisement->user->email }}
                                <hr class="my-0" />
                            </div>

                            {{-- Address --}}
                            <div class="text-left mb-2">
                                <strong>Address:</strong>
                                {{$advertisement->address}}
                                <hr class="my-0" />
                            </div>

                            {{-- Price --}}
                            <div class="text-left mb-2">
                                <strong>Price:</strong>
                                ${{$advertisement->price}}/hr
                                <hr class="my-0" />
                            </div>

                            {{-- Advertisement Description --}}
                            <div class="text-left mb-2">
                                <strong>Description:</strong>
                                <p>{{$advertisement->description}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="justify-content-center text-center pt-4">
                                {{-- Checking if the user is authenticated --}}
                                @auth
                                    {{-- Favourites --}}
                                    @if($exists)
                                        {{-- Favourites Remove --}}
                                        <form action="{{ route('my-favourites.destroy', $advertisement->id) }}" method="POST" class="d-inline"> 
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm mb-3" type="submit"><i class="fa fa-heart"></i> Remove From Favourites</button>
                                        </form>  
                                    @else
                                        {{-- Favourites Add --}}
                                        <form action="{{route('my-favourites.store')}}" id="favourite_form" method="POST" class="d-inline">
                                            {{csrf_field()}}
                                            <input name="user_id" type="hidden" value="{{Auth::user()->id}}" />
                                            <input name="advertisement_id" type="hidden" value="{{$advertisement->id}}" />

                                            <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa fa-heart"></i> Add to Favourites</button>
                                        </form>
                                    @endif
                                @endauth

                                {{-- Checking if the user is guest (Not Authenticated) --}}
                                @guest
                                    <a href="{{ route('login') }}" type="button" class="btn btn-primary btn-sm mb-3"><i class="fa fa-heart"></i> Add to Favourites</a>
                                @endguest

                                {{-- Appointment Booking --}}
                                <form action="{{route('bookings.create', ['advertisement'=> $advertisement->id, 'advertisement_user_id'=> $advertisement->user->id])}}" id="bookings_form" method="GET" class="d-inline">
                                    <input name="advertisement_id" type="hidden" value="{{$advertisement->id}}" />
                                    <input name="advertisement_user_id" type="hidden" value="{{$advertisement->user->id}}" />

                                    <button type="submit" class="btn btn-success btn-sm mb-3"><i class="fa fa-calendar"></i> Book an Appointment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    // Rating Submission
    $('#addRating').change('.kv-ltr-theme-svg-star', function(e) {
        $(this).submit();
    });
</script>
@endsection