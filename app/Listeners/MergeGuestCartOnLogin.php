<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Contracts\CartServiceInterface;
use Illuminate\Auth\Events\Login;
use App\Models\User;

/**
 * Class MergeGuestCartOnLogin
 *
 * Listens to the Login event and merges any existing
 * guest cart into the authenticated user's cart.
 */
final class MergeGuestCartOnLogin
{
    protected CartServiceInterface $cartService;

    /**
     * Create the event listener.
     *
     * @param CartServiceInterface $cartService
     */
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Handle the event.
     *
     * @param Login $event The login event instance containing the authenticated user.
     * @return void
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $this->cartService->mergeGuestCartToUser($user->id);
    }
}
