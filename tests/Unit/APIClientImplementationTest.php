<?php

namespace Tests\Unit;

use App\APIClientImplementation;
use App\APIException;
use App\APIResponse;
use App\Order;
use PHPUnit\Framework\TestCase;

class APIClientImplementationTest extends TestCase
{
    private APIClientImplementation $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new APIClientImplementation();
    }

    public function testCallAPISuccess()
    {
        $response = $this->client->callAPI(1);
        
        $this->assertInstanceOf(APIResponse::class, $response);
        $this->assertEquals('success', $response->status);
        $this->assertInstanceOf(Order::class, $response->data);
    }

    public function testCallAPIWithInvalidId()
    {
        $this->expectException(APIException::class);
        $this->client->callAPI(-1);
    }

    public function testCallAPIWithZeroId()
    {
        $this->expectException(APIException::class);
        $this->client->callAPI(0);
    }
} 