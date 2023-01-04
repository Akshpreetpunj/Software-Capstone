@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Post New Advertisement') }}
                    <a href="{{ route('my-ads') }}" type="button" class="float-right btn btn-info btn-sm">View All</a>
                </div>

                <div class="card-body">
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
                    
                    {{-- Advertisement Create Form --}}
                    <form method="POST" action="{{ url('/my-ads') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Skilled Trades --}}
                        <div class="row mb-3">
                            <label for="skilled_trades" class="col-md-4 col-form-label text-md-end">{{ __('Skilled Trades') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <select id="skilled_trades" name="skilled_trades" class="form-select" required autofocus>
                                    <option disabled selected value="">Select Category</option>
                                    <option value="carpenter" @if (old('skilled_trades') == "carpenter") {{'selected'}} @endif>Carpenter</option>
                                    <option value="electrician" @if (old('skilled_trades') == "electrician") {{'selected'}} @endif>Electrician</option>
                                    <option value="plumber" @if (old('skilled_trades') == "plumber") {{'selected'}} @endif>Plumber</option>
                                    <option value="other" @if (old('skilled_trades') == "other") {{'selected'}} @endif>Other</option>
                                </select>

                                @error('skilled_trades')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Advertisement Title --}}
                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Ad title') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Advertisement Description --}}
                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" required rows="5" cols="50">{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="123 Main St" required autofocus>

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price/hr') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" min="0" required>

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Advertisement Image --}}
                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Select Image') }}</label>

                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control-file @error('image') is-invalid @enderror" name="image">

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small id="imageHelp" class="form-text text-muted">One file only (3 MB limit, Allowed types: jpeg, png, jpg, gif, svg)</small>
                            </div>
                        </div>

                        {{-- Phone Number --}}
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="(123)-456-7890" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-8 offset-md-4">
                                <p><span class="text-danger">*</span>Required field</p>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-paper-plane"></i> {{ __('Post Your Ad') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection