<?php

namespace Tests\Unit;

use App\APIClient;
use App\APIException;
use App\APIResponse;
use App\Order;
use App\TypeBOrderHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TypeBOrderHandlerTest extends TestCase
{
    private TypeBOrderHandler $handler;
    /** @var APIClient&MockObject */
    private $apiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiClient = $this->createMock(APIClient::class);
        $this->handler = new TypeBOrderHandler($this->apiClient);
    }

    public function testHandleOrderSuccessWithAmountGreaterThan50()
    {
        $order = new Order(1, 'B', 80.0, false);
        $apiOrder = new Order(1, 'B', 60.0, false);
        $response = new APIResponse('success', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('processed', $order->status);
    }

    public function testHandleOrderSuccessWithAmountLessThan50()
    {
        $order = new Order(1, 'B', 80.0, false);
        $apiOrder = new Order(1, 'B', 40.0, false);
        $response = new APIResponse('success', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('pending', $order->status);
    }

    public function testHandleOrderWithFlag()
    {
        $order = new Order(1, 'B', 80.0, true);
        $apiOrder = new Order(1, 'B', 60.0, false);
        $response = new APIResponse('success', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('pending', $order->status);
    }

    public function testHandleOrderWithAPIError()
    {
        $order = new Order(1, 'B', 80.0, false);
        $apiOrder = new Order(1, 'B', 60.0, false);
        $response = new APIResponse('error', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('api_error', $order->status);
    }

    public function testHandleOrderWithAPIException()
    {
        $order = new Order(1, 'B', 80.0, false);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willThrowException(new APIException());

        $this->handler->handle($order);

        $this->assertEquals('api_failure', $order->status);
    }

    public function testHandleOrderWithHighAmountAndLowAPIResponse()
    {
        $order = new Order(1, 'B', 120.0, false);
        $apiOrder = new Order(1, 'B', 40.0, false);
        $response = new APIResponse('success', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('error', $order->status);
    }

    public function testHandleOrderWithHighAmountAndHighAPIResponse()
    {
        // Test case for the else condition:
        // - response status is 'success'
        // - order amount >= 100
        // - response data amount >= 50
        // - not matching previous conditions
        $order = new Order(1, 'B', 120.0, false);
        $apiOrder = new Order(1, 'B', 60.0, false);
        $response = new APIResponse('success', $apiOrder);

        $this->apiClient->expects($this->once())
            ->method('callAPI')
            ->with(1)
            ->willReturn($response);

        $this->handler->handle($order);

        $this->assertEquals('error', $order->status);
    }
} 