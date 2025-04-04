<?php

namespace App;

/**
 * Interface OrderHandlerInterface
 *
 * This interface defines the contract for handling orders.
 */
interface OrderHandlerInterface
{
    /**
     * Handles the given order.
     *
     * @param Order $order The order to be handled.
     * @return void
     */
    public function handle(Order $order): void;
}