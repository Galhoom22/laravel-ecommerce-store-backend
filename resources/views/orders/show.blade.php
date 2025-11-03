@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Order #{{ $order->id }}</h1>

        {{-- Order Info --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        <p>
                            <strong>Status:</strong>
                            <span
                                class="badge
                            @if ($order->status === 'pending') bg-warning
                            @elseif($order->status === 'completed') bg-success
                            @elseif($order->status === 'cancelled') bg-danger
                            @else bg-secondary @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                        <p><strong>City:</strong> {{ $order->shipping_city }}</p>
                        <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <h4 class="mb-3">Order Items</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                            <td class="text-end">${{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">
            Back to Orders
        </a>
    </div>
@endsection
