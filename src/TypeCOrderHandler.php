<?php

namespace App;

/**
 * Class TypeCOrderHandler
 *
 * This class handles orders of type C by updating their status based on a flag.
 */
class TypeCOrderHandler implements OrderHandlerInterface
{
    /**
     * Handles the given order by updating its status based on a flag.
     *
     * @param Order $order The order to be handled.
     * @return void
     */
    public function handle(Order $order): void
    {
        if ($order->flag) {
            $order->status = 'completed';
        } else {
            $order->status = 'in_progress';
        }
    }
}