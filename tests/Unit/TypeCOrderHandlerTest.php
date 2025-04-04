<?php

namespace Tests\Unit;

use App\Order;
use App\TypeCOrderHandler;
use PHPUnit\Framework\TestCase;

class TypeCOrderHandlerTest extends TestCase
{
    private TypeCOrderHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new TypeCOrderHandler();
    }

    public function testHandleOrderWithFlag()
    {
        $order = new Order(1, 'C', 100.0, true);
        $this->handler->handle($order);

        $this->assertEquals('completed', $order->status);
    }

    public function testHandleOrderWithoutFlag()
    {
        $order = new Order(1, 'C', 100.0, false);
        $this->handler->handle($order);

        $this->assertEquals('in_progress', $order->status);
    }
} 