@extends('layouts.app')

@section('content')
    <!-- Start Header Section -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Login to Your Account</h1>
            <p>Please enter your email and password to continue.</p>
        </div>
    </div>
    <!-- End Header Section -->

    <!-- Start Login Form -->
    <div class="container py-5">
        <div class="row py-5">
            <form class="col-md-6 m-auto" method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Login Error Message --}}
                @if (session('error'))
                    <div class="alert alert-danger small text-center">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('status'))
                    <div class="alert alert-success small text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" class="form-control mt-1"
                        placeholder="Enter your email" value="{{ old('email') }}" aria-label="Email Address" required>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control mt-1"
                        placeholder="Enter your password" required autocomplete="current-password">
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col text-end mt-2">
                        <button type="submit" class="btn btn-success btn-lg px-3">Login</button>
                    </div>
                </div>

                <!-- Link to Register -->
                <div class="mt-3 text-center">
                    Don’t have an account? <a href="{{ route('register') }}">Create one here</a>.
                </div>
            </form>
        </div>
    </div>
    <!-- End Login Form -->
@endsection
