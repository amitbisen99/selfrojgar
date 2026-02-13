@extends('layouts.app')

@section('title')
    Login
@endsection

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    
                    <!-- Login basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            
                            <a href="#" class="brand-logo">
                                <h2 class="brand-text text-primary ms-1 primary-color">{{ isset($webSetting['web_title']) ? $webSetting['web_title'] : 'Selfrojgar' }}</h2>
                            </a>

                            @if($error = Session::get('error'))
                              <div class="alert alert-danger error alert-danger-cum" style="padding:10px !important;">
                                  {{ $error }}
                              </div>
                            @endif

                            <h4 class="card-title mb-1">hello, users 👋</h4>

                            <p class="card-text mb-2">welcome to dashboard CRM</p>

                            {!! Form::open(array('route' => 'login','method'=>'POST')) !!}
                                <div class="mb-1">
                                    <label for="login-email" class="form-label custom-label">{{ __('Email Address') }}:</label>
                                    {!! Form::text('email', old('email'), array('placeholder' => 'Email','class' => 'form-control', 'id' => 'login-email', 'autofocus' => 'autofocus')) !!}
                                    
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label custom-label" for="login-password">{{ __('Password') }}:</label>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        {!! Form::password('password', array('placeholder' => '********','class' => 'form-control form-control-merge', 'id' => 'login-password', 'autocomplete' => 'off')) !!}
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>

                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between float-end">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">
                                                <small>{{ __('Forgot Your Password?') }}</small>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mt-1" tabindex="4">{{ __('Login') }}</button>
                            
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /Login basic -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
