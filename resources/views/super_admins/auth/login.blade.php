@extends('super_admins.auth.auth_master')

@section('auth_title', 'Super Admin Login')

@section('auth_content')
<div class="auth-form-wrapper px-4 py-5">
    <a href="#" class="noble-ui-logo d-block mb-2 text-center"><img src="{{ asset('public/default/favicon.png') }}" alt="" width="100px"></a>
    <a href="#" class="noble-ui-logo d-block mb-2 text-center">Booking<span>Advisor</span></a>
    <h5 class="text-muted fw-normal mb-4 text-center">Welcome Back! Login to Super Admin Account.</h5>
    <hr>
    <h4 class="text-muted mb-2 lead">Super Admin Login</h4>
    <form method="POST" action="{{ route('super_admins.login') }}">
        @csrf
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class='fas fa-envelope text-muted'></i></span>
            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail" placeholder="Email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class='fas fa-lock text-muted'></i></span>
            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="userPassword" autocomplete="current-password" placeholder="Password" required>
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div>
            <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0 text-white w-100">Login</button>
        </div>
    </form>
</div>
@endsection