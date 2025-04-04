<?php

namespace App;

/**
 * Class PriorityCalculator
 *
 * This class provides a method to calculate the priority of an order based on its amount.
 */
class PriorityCalculator
{
    /**
     * Calculates the priority of the given order.
     *
     * @param Order $order The order for which the priority is to be calculated.
     * @return string The priority of the order ('high' if the amount is greater than 200, otherwise 'low').
     */
    public function calculatePriority(Order $order): string
    {
        return $order->amount > 200 ? 'high' : 'low';
    }
}