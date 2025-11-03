@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4 text-center">Your Shopping Cart</h1>

        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        {{-- Empty Cart --}}
        @if ($items->isEmpty())
            <div class="text-center py-5">
                <img src="{{ asset('images/empty-cart.png') }}" alt="Empty Cart" class="img-fluid mb-4"
                    style="max-width: 200px;">
                <h4>Your cart is empty</h4>
                <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
        @else
            {{-- Cart Table --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col" class="text-center">Price</th>
                            <th scope="col" class="text-center">Quantity</th>
                            <th scope="col" class="text-center">Subtotal</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            @if ($item->product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image_url ?? asset('images/placeholder.png') }}"
                                                alt="{{ $item->product->name }}" class="img-thumbnail me-3"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                            <span>{{ $item->product->name }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        ${{ number_format($item->product->price, 2) }}
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $item->product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                min="1" class="form-control d-inline w-50 text-center" />
                                            <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">
                                                Update
                                            </button>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        ${{ number_format($item->quantity * $item->product->price, 2) }}
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('cart.destroy', $item->product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Cart Summary --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        Clear Cart
                    </button>
                </form>

                <h4 class="fw-bold">
                    Total: <span class="text-success">${{ number_format($total, 2) }}</span>
                </h4>
            </div>

            {{-- Checkout Button --}}
            <div class="text-end mt-4">
                <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                    Proceed to Checkout
                </a>
            </div>
        @endif
    </div>
@endsection
