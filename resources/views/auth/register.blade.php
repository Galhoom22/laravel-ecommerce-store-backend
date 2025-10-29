@extends('layouts.app')

@section('content')
    <!-- Start Header Section -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Create an Account</h1>
            <p>Please fill in the form below to create your account.</p>
        </div>
    </div>
    <!-- End Header Section -->

    <!-- Start Register Form -->
    <div class="container py-5">
        <div class="row py-5">
            <form class="col-md-6 m-auto" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Success Message --}}
                @if (session('status'))
                    <div class="alert alert-success small text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" name="name" class="form-control mt-1"
                        placeholder="Enter your full name" value="{{ old('name') }}" autofocus>
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" class="form-control mt-1"
                        placeholder="Enter your email" value="{{ old('email') }}" aria-label="Email Address">
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control mt-1"
                        placeholder="Enter your password" required autocomplete="new-password">
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control mt-1"
                        placeholder="Re-enter your password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col text-end mt-2">
                        <button type="submit" class="btn btn-success btn-lg px-3">Register</button>
                    </div>
                </div>

                <!-- Link to Login -->
                <div class="mt-3 text-center">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>.
                </div>
            </form>
        </div>
    </div>
    <!-- End Register Form -->
@endsection
