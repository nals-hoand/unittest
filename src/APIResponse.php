<?php

namespace App;

/**
 * Class APIResponse
 *
 * This class represents the response from an API call.
 */
class APIResponse
{
    /**
     * @var string The status of the API response.
     */
    public string $status;

    /**
     * @var Order The data returned in the API response.
     */
    public Order $data;

    /**
     * APIResponse constructor.
     *
     * @param string $status The status of the API response.
     * @param Order $data The data returned in the API response.
     */
    public function __construct(string $status, Order $data)
    {
        $this->status = $status;
        $this->data = $data;
    }
}