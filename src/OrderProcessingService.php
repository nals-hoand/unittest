<?php

namespace App;

/**
 * Class OrderProcessingService
 *
 * This class handles the processing of orders, including retrieving orders from the database,
 * handling them based on their type, and updating their status and priority.
 */
class OrderProcessingService
{
    /**
     * @var DatabaseService The service for database operations.
     */
    private DatabaseService $dbService;

    /**
     * @var APIClient The client for API operations.
     */
    private APIClient $apiClient;

    /**
     * @var CsvExporter The exporter for CSV operations.
     */
    private CsvExporter $csvExporter;

    /**
     * @var TypeAOrderHandler The handler for Type A orders.
     */
    private TypeAOrderHandler $typeAHandler;

    /**
     * @var TypeBOrderHandler The handler for Type B orders.
     */
    private TypeBOrderHandler $typeBHandler;

    /**
     * @var TypeCOrderHandler The handler for Type C orders.
     */
    private TypeCOrderHandler $typeCHandler;

    /**
     * OrderProcessingService constructor.
     *
     * @param DatabaseService $dbService The service for database operations.
     * @param APIClient $apiClient The client for API operations.
     * @param CsvExporter $csvExporter The exporter for CSV operations.
     * @param TypeAOrderHandler $typeAHandler The handler for Type A orders.
     * @param TypeBOrderHandler $typeBHandler The handler for Type B orders.
     * @param TypeCOrderHandler $typeCHandler The handler for Type C orders.
     */
    public function __construct(
        DatabaseService $dbService,
        APIClient $apiClient,
        CsvExporter $csvExporter,
        TypeAOrderHandler $typeAHandler,
        TypeBOrderHandler $typeBHandler,
        TypeCOrderHandler $typeCHandler
    ) {
        $this->dbService = $dbService;
        $this->apiClient = $apiClient;
        $this->csvExporter = $csvExporter;
        $this->typeAHandler = $typeAHandler;
        $this->typeBHandler = $typeBHandler;
        $this->typeCHandler = $typeCHandler;
    }

    /**
     * Processes the orders for a specific user.
     *
     * @param int $userId The ID of the user whose orders are to be processed.
     * @return array|bool An array of processed orders or false if an error occurs.
     */
    public function processOrders(int $userId)
    {
        try {
            $orders = $this->dbService->getOrdersByUser($userId);
            if (empty($orders)) {
                return [];
            }

            foreach ($orders as $order) {
                try {
                    switch ($order->type) {
                        case 'A':
                            $this->typeAHandler->handle($order);
                            break;
                        case 'B':
                            $this->typeBHandler->handle($order);
                            break;
                        case 'C':
                            $this->typeCHandler->handle($order);
                            break;
                        default:
                            $order->status = 'unknown_type';
                            break;
                    }

                    if ($order->amount > 200) {
                        $order->priority = 'high';
                    } else {
                        $order->priority = 'low';
                    }

                    $this->dbService->updateOrderStatus($order->id, $order->status, $order->priority);
                } catch (DatabaseException $e) {
                    $order->status = 'db_error';
                    return false;
                } catch (\Exception $e) {
                    return false;
                }
            }

            return $orders;
        } catch (\Exception $e) {
            return false;
        }
    }
}