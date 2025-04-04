<?php

namespace Tests\Unit;

use App\APIResponse;
use App\Order;
use PHPUnit\Framework\TestCase;

class APIResponseTest extends TestCase
{
    public function testAPIResponseCreation()
    {
        $order = new Order(1, 'A', 100.0, false);
        $response = new APIResponse('success', $order);
        
        $this->assertEquals('success', $response->status);
        $this->assertInstanceOf(Order::class, $response->data);
        $this->assertEquals(1, $response->data->id);
    }

    public function testAPIResponseWithDifferentStatus()
    {
        $order = new Order(2, 'B', 200.0, true);
        $response = new APIResponse('error', $order);
        
        $this->assertEquals('error', $response->status);
        $this->assertInstanceOf(Order::class, $response->data);
        $this->assertEquals(2, $response->data->id);
    }
} 