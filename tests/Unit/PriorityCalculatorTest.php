<?php

namespace Tests\Unit;

use App\Order;
use App\PriorityCalculator;
use PHPUnit\Framework\TestCase;

class PriorityCalculatorTest extends TestCase
{
    private PriorityCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PriorityCalculator();
    }

    public function testCalculatePriorityForLowAmount()
    {
        $order = new Order(1, 'A', 100.0, false);
        $priority = $this->calculator->calculatePriority($order);
        
        $this->assertEquals('low', $priority);
    }

    public function testCalculatePriorityForHighAmount()
    {
        $order = new Order(1, 'A', 201.0, false);
        $priority = $this->calculator->calculatePriority($order);
        
        $this->assertEquals('high', $priority);
    }

    public function testCalculatePriorityForBorderlineAmount()
    {
        $order = new Order(1, 'A', 200.0, false);
        $priority = $this->calculator->calculatePriority($order);
        
        $this->assertEquals('low', $priority);
    }

    public function testCalculatePriorityForNegativeAmount()
    {
        $order = new Order(1, 'A', -100.0, false);
        $priority = $this->calculator->calculatePriority($order);
        
        $this->assertEquals('low', $priority);
    }

    public function testCalculatePriorityForZeroAmount()
    {
        $order = new Order(1, 'A', 0.0, false);
        $priority = $this->calculator->calculatePriority($order);
        
        $this->assertEquals('low', $priority);
    }
} 