<?php
namespace App;

/**
 * Class APIClientImplementation
 *
 * This class implements the APIClient interface and provides the functionality
 * to make an API call with a given order ID.
 */
class APIClientImplementation implements APIClient
{
    /**
     * Makes an API call with the given order ID.
     *
     * @param int $orderId The ID of the order to be used in the API call.
     * @return APIResponse The response from the API call.
     * @throws APIException If the order ID is invalid (less than or equal to 0).
     */
    public function callAPI($orderId): APIResponse
    {
        if ($orderId <= 0) {
            throw new APIException("Invalid order ID");
        }

        // Simulate API call
        $responseOrder = new Order($orderId, 'B', 60.0, false);
        return new APIResponse('success', $responseOrder);
    }
}