<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\OrderServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Response;

/**
 * Class OrderController
 *
 * Handles displaying user orders and individual order details.
 */
final class OrderController extends Controller
{
    /**
     * OrderController constructor.
     *
     * @param OrderServiceInterface $orderService
     */
    public function __construct(
        private readonly OrderServiceInterface $orderService
    ) {}

    /**
     * Display a list of the authenticated user's orders.
     *
     * @return View
     */
    public function index(): View
    {
        $orders = $this->orderService->getUserOrders(Auth::id());

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a specific order by ID for the authenticated user.
     *
     * @param int $order
     * @return View|Response
     */
    public function show(int $order): View|Response
    {
        $order = $this->orderService->getOrderById($order, Auth::id());

        if (!$order) {
            abort(404);
        }

        return view('orders.show', compact('order'));
    }
}
