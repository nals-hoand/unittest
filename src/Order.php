<?php

namespace App;

/**
 * Class Order
 *
 * This class represents an order with various attributes such as ID, type, amount, flag, status, and priority.
 */
class Order
{
    /**
     * @var int The ID of the order.
     */
    public int $id;

    /**
     * @var string The type of the order.
     */
    public string $type;

    /**
     * @var float The amount of the order.
     */
    public float $amount;

    /**
     * @var bool The flag indicating a specific condition of the order.
     */
    public bool $flag;

    /**
     * @var string The status of the order.
     */
    public string $status;

    /**
     * @var string The priority of the order.
     */
    public string $priority;

    /**
     * Order constructor.
     *
     * @param int $id The ID of the order.
     * @param string $type The type of the order.
     * @param float $amount The amount of the order.
     * @param bool $flag The flag indicating a specific condition of the order.
     * @param string $status The status of the order.
     * @param string $priority The priority of the order.
     */
    public function __construct(
        int $id = 0,
        string $type = '',
        float $amount = 0.0,
        bool $flag = false,
        string $status = '',
        string $priority = ''
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->amount = $amount;
        $this->flag = $flag;
        $this->status = $status;
        $this->priority = $priority;
    }
}