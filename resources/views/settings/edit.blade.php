@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Banner & Announcements') }}
                    <a href="{{ route('settings') }}" type="button" class="float-right btn btn-info btn-sm">View All</a>
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
                    
                    {{-- Banner & Announcement Edit Form --}}
                    <form method="POST" action="{{ route('settings.update', $setting->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Banner Image --}}
                        <div class="row mb-3">
                            <label for="banner_image" class="col-md-4 col-form-label text-md-end">{{ __('Banner Image') }} <span class="text-danger">*</span></label>

                            <div class="col-md-6">
                                <input id="banner_image" type="file" class="form-control-file @error('banner_image') is-invalid @enderror" name="banner_image" value="{{ $setting->banner_image }} required">

                                
                                @if($setting->banner_image)
                                    <img alt="image" class="mt-2" src="{{ Storage::disk('s3')->url('banner_images/' . $setting->banner_image) }}" width="100px">
                                @else
                                    <img alt="default image" class="mt-2" src="{{ asset('images/placeholder.gif') }}" width="100px">
                                @endif

                                @error('banner_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small id="imageHelp" class="form-text text-muted">One file only (3 MB limit, Allowed types: jpeg, png, jpg, gif, svg)</small>
                            </div>
                        </div>

                        {{-- Announcement (News) --}}
                        <div class="row mb-3">
                            <label for="announcement" class="col-md-4 col-form-label text-md-end">{{ __('Announcements') }}</label>

                            <div class="col-md-6">
                                <textarea name="announcement" id="announcement" class="form-control @error('announcement') is-invalid @enderror" rows="5" cols="50">{{ $setting->announcement }}</textarea>

                                @error('announcement')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Publish Status --}}
                        <div class="row mb-3">
                            <label for="publish_status" class="col-md-4 col-form-check-label text-md-end">{{ __('Publish Status') }}</label>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input @if($setting->publish_status=='on') checked @endif id="publish_status" type="checkbox" class="form-check-input @error('publish_status') is-invalid @enderror" name="publish_status">

                                    @error('publish_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
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
                                    <i class="fa fa-save"></i> {{ __('Save') }}
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