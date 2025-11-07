{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Order Details</h1>

        {{-- Customer Info --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Phone:</strong> {{ $order->user->phone ?? '—' }}</p>
                <p><strong>Address:</strong> {{ $order->shipping_address ?? '—' }}</p>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Ordered Products</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
                <p><strong>Status:</strong>
                    @if ($order->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($order->status === 'shipped')
                        <span class="badge bg-info text-dark">Shipped</span>
                    @elseif($order->status === 'delivered')
                        <span class="badge bg-success">Delivered</span>
                    @elseif($order->status === 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-secondary">Unknown</span>
                    @endif
                </p>
                <p><strong>Placed On:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                ← Back to Orders
            </a>
        </div>
    </div>
@endsection
