<?php

namespace Tests\Unit;

use App\CsvExporter;
use App\Order;
use App\TypeAOrderHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TypeAOrderHandlerTest extends TestCase
{
    private TypeAOrderHandler $handler;
    /** @var CsvExporter&MockObject */
    private $csvExporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->csvExporter = $this->createMock(CsvExporter::class);
        $this->handler = new TypeAOrderHandler($this->csvExporter);
    }

    public function testHandleOrderSuccess()
    {
        $order = new Order(1, 'A', 100.0, false);
        $this->csvExporter->expects($this->once())
            ->method('exportOrder')
            ->with($order, date('Y-m-d'))
            ->willReturn(true);

        $this->handler->handle($order);

        $this->assertEquals('exported', $order->status);
    }

    public function testHandleOrderFailure()
    {
        $order = new Order(1, 'A', 100.0, false);
        $this->csvExporter->expects($this->once())
            ->method('exportOrder')
            ->with($order, date('Y-m-d'))
            ->willReturn(false);

        $this->handler->handle($order);

        $this->assertEquals('export_failed', $order->status);
    }
} 