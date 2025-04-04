<?php

namespace Tests\Unit;

use App\DatabaseException;
use App\DatabaseServiceImplementation;
use App\Order;
use PHPUnit\Framework\TestCase;

class DatabaseServiceImplementationTest extends TestCase
{
    private DatabaseServiceImplementation $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DatabaseServiceImplementation();
    }

    public function testGetOrdersByUserSuccess()
    {
        $orders = $this->service->getOrdersByUser(1);
        
        $this->assertIsArray($orders);
        $this->assertNotEmpty($orders);
        foreach ($orders as $order) {
            $this->assertInstanceOf(Order::class, $order);
        }
    }

    public function testGetOrdersByUserWithInvalidId()
    {
        $this->expectException(DatabaseException::class);
        $this->service->getOrdersByUser(-1);
    }

    public function testUpdateOrderStatusSuccess()
    {
        $result = $this->service->updateOrderStatus(1, 'processed', 'high');
        $this->assertTrue($result);
    }

    public function testUpdateOrderStatusWithInvalidId()
    {
        $this->expectException(DatabaseException::class);
        $this->service->updateOrderStatus(-1, 'processed', 'high');
    }

    public function testUpdateOrderStatusWithInvalidStatus()
    {
        $this->expectException(DatabaseException::class);
        $this->service->updateOrderStatus(1, '', 'high');
    }

    public function testUpdateOrderStatusWithInvalidPriority()
    {
        $this->expectException(DatabaseException::class);
        $this->service->updateOrderStatus(1, 'processed', '');
    }
} 