@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">All Orders (Admin Panel)</h1>

        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @break

                                @case('processing')
                                    <span class="badge bg-info text-dark">Processing</span>
                                @break

                                @case('completed')
                                    <span class="badge bg-success">Completed</span>
                                @break

                                @case('cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">Unknown</span>
                            @endswitch
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary">View Details</a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    @endsection
