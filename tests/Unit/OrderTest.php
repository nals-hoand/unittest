<?php

namespace Tests\Unit;

use App\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrderCreation()
    {
        $order = new Order(1, 'A', 100.0, false);
        
        $this->assertEquals(1, $order->id);
        $this->assertEquals('A', $order->type);
        $this->assertEquals(100.0, $order->amount);
        $this->assertFalse($order->flag);
    }
} 