<?php

namespace App;

/**
 * Class TypeBOrderHandler
 *
 * This class handles orders of type B by calling an external API and updating the order status based on the response.
 */
class TypeBOrderHandler implements OrderHandlerInterface
{
    /**
     * @var APIClient The client for API operations.
     */
    private APIClient $apiClient;

    /**
     * TypeBOrderHandler constructor.
     *
     * @param APIClient $apiClient The client for API operations.
     */
    public function __construct(APIClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Handles the given order by calling an external API and updating its status based on the response.
     *
     * @param Order $order The order to be handled.
     * @return void
     */
    public function handle(Order $order): void
    {
        try {
            $response = $this->apiClient->callAPI($order->id);

            if ($response->status === 'success') {
                if ($response->data->amount >= 50 && $order->amount < 100 && !$order->flag) {
                    $order->status = 'processed';
                } elseif ($order->amount >= 100 && $response->data->amount < 50) {
                    $order->status = 'error';
                } elseif ($response->data->amount < 50 || $order->flag) {
                    $order->status = 'pending';
                } else {
                    $order->status = 'error';
                }
            } else {
                $order->status = 'api_error';
            }
        } catch (APIException $e) {
            $order->status = 'api_failure';
        }
    }
}