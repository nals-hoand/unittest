<?php
namespace App;

/**
 * Class DatabaseServiceImplementation
 *
 * This class implements the DatabaseService interface and provides the functionality
 * to retrieve and update orders in the database.
 */
class DatabaseServiceImplementation implements DatabaseService
{
    /**
     * Retrieves the orders associated with a specific user.
     *
     * @param int $userId The ID of the user whose orders are to be retrieved.
     * @return array An array of orders associated with the specified user.
     * @throws DatabaseException If the user ID is invalid.
     */
    public function getOrdersByUser($userId): array
    {
        if ($userId <= 0) {
            throw new DatabaseException("Invalid user ID");
        }

        // Simulate database query
        return [
            new Order(1, 'A', 100.0, false),
            new Order(2, 'B', 200.0, true),
            new Order(3, 'C', 300.0, false)
        ];
    }

    /**
     * Updates the status and priority of a specific order.
     *
     * @param int $orderId The ID of the order to be updated.
     * @param string $status The new status of the order.
     * @param string $priority The new priority of the order.
     * @return bool True if the update was successful, false otherwise.
     * @throws DatabaseException If the order ID, status, or priority is invalid.
     */
    public function updateOrderStatus($orderId, $status, $priority): bool
    {
        if ($orderId <= 0) {
            throw new DatabaseException("Invalid order ID");
        }

        if (empty($status)) {
            throw new DatabaseException("Invalid status");
        }

        if (empty($priority)) {
            throw new DatabaseException("Invalid priority");
        }

        // Simulate database update
        return true;
    }
}