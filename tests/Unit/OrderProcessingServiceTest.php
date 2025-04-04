<?php

namespace Tests\Unit;

use App\APIClient;
use App\CsvExporter;
use App\DatabaseException;
use App\DatabaseService;
use App\Order;
use App\OrderProcessingService;
use App\TypeAOrderHandler;
use App\TypeBOrderHandler;
use App\TypeCOrderHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class OrderProcessingServiceTest extends TestCase
{
    private OrderProcessingService $service;
    /** @var DatabaseService&MockObject */
    private $dbService;
    /** @var APIClient&MockObject */
    private $apiClient;
    /** @var CsvExporter&MockObject */
    private $csvExporter;
    /** @var TypeAOrderHandler&MockObject */
    private $typeAHandler;
    /** @var TypeBOrderHandler&MockObject */
    private $typeBHandler;
    /** @var TypeCOrderHandler&MockObject */
    private $typeCHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dbService = $this->createMock(DatabaseService::class);
        $this->apiClient = $this->createMock(APIClient::class);
        $this->csvExporter = $this->createMock(CsvExporter::class);
        $this->typeAHandler = $this->createMock(TypeAOrderHandler::class);
        $this->typeBHandler = $this->createMock(TypeBOrderHandler::class);
        $this->typeCHandler = $this->createMock(TypeCOrderHandler::class);

        $this->service = new OrderProcessingService(
            $this->dbService,
            $this->apiClient,
            $this->csvExporter,
            $this->typeAHandler,
            $this->typeBHandler,
            $this->typeCHandler
        );
    }

    public function testProcessOrdersSuccess()
    {
        $orders = [
            new Order(1, 'A', 100.0, false),
            new Order(2, 'B', 200.0, true),
            new Order(3, 'C', 300.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->typeAHandler->expects($this->once())
            ->method('handle')
            ->with($orders[0]);

        $this->typeBHandler->expects($this->once())
            ->method('handle')
            ->with($orders[1]);

        $this->typeCHandler->expects($this->once())
            ->method('handle')
            ->with($orders[2]);

        $this->dbService->expects($this->exactly(3))
            ->method('updateOrderStatus')
            ->willReturn(true);

        $result = $this->service->processOrders(1);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertInstanceOf(Order::class, $result[0]);
    }

    public function testProcessOrdersWithDatabaseError()
    {
        $orders = [
            new Order(1, 'A', 100.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->typeAHandler->expects($this->once())
            ->method('handle')
            ->with($orders[0]);

        $this->dbService->expects($this->once())
            ->method('updateOrderStatus')
            ->willThrowException(new DatabaseException());

        $result = $this->service->processOrders(1);

        $this->assertFalse($result);
    }

    public function testProcessOrdersWithUnknownType()
    {
        $orders = [
            new Order(1, 'D', 100.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->dbService->expects($this->once())
            ->method('updateOrderStatus')
            ->willReturn(true);

        $result = $this->service->processOrders(1);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('unknown_type', $result[0]->status);
    }

    public function testProcessOrdersWithHighAmount()
    {
        $orders = [
            new Order(1, 'A', 250.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->typeAHandler->expects($this->once())
            ->method('handle')
            ->with($orders[0]);

        $this->dbService->expects($this->once())
            ->method('updateOrderStatus')
            ->with($orders[0]->id, $orders[0]->status, 'high')
            ->willReturn(true);

        $result = $this->service->processOrders(1);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('high', $result[0]->priority);
    }

    public function testProcessOrdersWithGetOrdersException()
    {
        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willThrowException(new DatabaseException());

        $result = $this->service->processOrders(1);

        $this->assertFalse($result);
    }

    public function testProcessOrdersWithEmptyOrders()
    {
        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn([]);

        $result = $this->service->processOrders(1);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testProcessOrdersWithHandlerException()
    {
        $orders = [
            new Order(1, 'A', 100.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->typeAHandler->expects($this->once())
            ->method('handle')
            ->with($orders[0])
            ->willThrowException(new \Exception('Handler error'));

        $result = $this->service->processOrders(1);

        $this->assertFalse($result);
    }

    public function testProcessOrdersWithMultipleHighAmounts()
    {
        $orders = [
            new Order(1, 'A', 250.0, false),
            new Order(2, 'B', 300.0, false),
            new Order(3, 'C', 350.0, false)
        ];

        $this->dbService->expects($this->once())
            ->method('getOrdersByUser')
            ->with(1)
            ->willReturn($orders);

        $this->typeAHandler->expects($this->once())
            ->method('handle')
            ->with($orders[0]);

        $this->typeBHandler->expects($this->once())
            ->method('handle')
            ->with($orders[1]);

        $this->typeCHandler->expects($this->once())
            ->method('handle')
            ->with($orders[2]);

        $this->dbService->expects($this->exactly(3))
            ->method('updateOrderStatus')
            ->willReturn(true);

        $result = $this->service->processOrders(1);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        foreach ($result as $order) {
            $this->assertEquals('high', $order->priority);
        }
    }
} 