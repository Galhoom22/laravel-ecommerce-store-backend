@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Checkout</h1>

        <div class="row">
            <div class="col-md-7 mb-4">
                <h4 class="mb-3">Order Summary</h4>
                @if ($items->isEmpty())
                    <p>Your cart is empty.</p>
                @else
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-end">Price</th>
                                <th scope="col" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->product->price, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->product->price * $item->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="col-md-5">
                <h4 class="mb-3">Shipping Details</h4>

                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" name="address" id="address"
                            class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}"
                            required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- City --}}
                    <div class="mb-3">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" name="city" id="city"
                            class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone *</label>
                        <input type="text" name="phone" id="phone"
                            class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Total --}}
                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control" value="${{ number_format($total, 2) }}" readonly>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary w-100">Place Order</button>
                </form>
            </div>
        </div>
    </div>
@endsection
