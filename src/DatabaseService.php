<?php
namespace App;

/**
 * Interface DatabaseService
 *
 * This interface defines the contract for database operations related to orders.
 */
interface DatabaseService
{
    /**
     * Retrieves the orders associated with a specific user.
     *
     * @param int $userId The ID of the user whose orders are to be retrieved.
     * @return array An array of orders associated with the specified user.
     */
    public function getOrdersByUser(int $userId): array;

    /**
     * Updates the status and priority of a specific order.
     *
     * @param int $orderId The ID of the order to be updated.
     * @param string $status The new status of the order.
     * @param string $priority The new priority of the order.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateOrderStatus(int $orderId, string $status, string $priority): bool;
}