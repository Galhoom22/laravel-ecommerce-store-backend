@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">My Orders</h1>

        @if ($orders->isEmpty())
            <div class="alert alert-info">
                You haven't placed any orders yet.
                <a href="{{ route('shop.index') }}" class="alert-link">Start shopping</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>${{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span
                                        class="badge
                                    @if ($order->status === 'pending') bg-warning
                                    @elseif($order->status === 'completed') bg-success
                                    @elseif($order->status === 'cancelled') bg-danger
                                    @else bg-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
