<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\OrderServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Admin\UpdateOrderRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}

    public function index()
    {
        $orders = $this->orderService->getPaginated();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = $this->orderService->findById($id);

        if (!$order) {
            abort(404, 'Order not found.');
        }

        return view('admin.orders.show', compact('order'));
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $validated = $request->validated();

        $this->orderService->update($id, ['status' => $validated['status']]);

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully.');
    }
}
