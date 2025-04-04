<?php

namespace App;

/**
 * Interface APIClient
 *
 * This interface defines a contract for an API client that can make API calls.
 */
interface APIClient
{
    /**
     * Makes an API call with the given order ID.
     *
     * @param int $orderId The ID of the order to be used in the API call.
     * @return APIResponse The response from the API call.
     */
    public function callAPI(int $orderId): APIResponse;
}