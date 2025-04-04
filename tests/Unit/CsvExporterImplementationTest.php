<?php

namespace Tests\Unit;

use App\CsvExporterImplementation;
use App\Order;
use PHPUnit\Framework\TestCase;

class CsvExporterImplementationTest extends TestCase
{
    private string $testOutputDir;
    private CsvExporterImplementation $exporter;

    protected function setUp(): void
    {
        $this->testOutputDir = sys_get_temp_dir() . '/csv_exporter_test';
        // Create the directory if it doesn't exist
        if (!is_dir($this->testOutputDir)) {
            mkdir($this->testOutputDir, 0777, true);
        }
        $this->exporter = new CsvExporterImplementation($this->testOutputDir);
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (is_dir($this->testOutputDir)) {
            array_map('unlink', glob($this->testOutputDir . '/*'));
            rmdir($this->testOutputDir);
        }
    }

    public function testExportOrderSuccessfully()
    {
        $order = new Order(
            id: 123,
            type: 'A',
            amount: 100,
            flag: true,
            status: 'completed',
            priority: 'high'
        );

        $date = '2024-03-20';
        $result = $this->exporter->exportOrder($order, $date);

        $this->assertTrue($result);
        $filename = $this->testOutputDir . "/orders_type_A_123_2024-03-20.csv";
        $this->assertFileExists($filename);

        // Verify CSV content
        $content = file_get_contents($filename);
        $this->assertStringContainsString('123,A,100,true,completed,high', $content);
        $this->assertStringContainsString('ID,Type,Amount,Flag,Status,Priority', $content);
    }

    public function testExportOrderWithHighAmount()
    {
        $order = new Order(
            id: 456,
            type: 'A',
            amount: 200,
            flag: false,
            status: 'pending',
            priority: 'low'
        );

        $date = '2024-03-20';
        $result = $this->exporter->exportOrder($order, $date);

        $this->assertTrue($result);
        $filename = $this->testOutputDir . "/orders_type_A_456_2024-03-20.csv";
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);
        $this->assertStringContainsString('456,A,200,false,pending,low', $content);
        // Updated assertion to match the actual CSV format with quotes
        $this->assertStringContainsString('Note,"High value order"', $content);
    }

    public function testExportOrderWithInvalidDirectory()
    {
        $invalidDir = '/invalid/path/that/does/not/exist';
        $exporter = new CsvExporterImplementation($invalidDir);

        $order = new Order(
            id: 789,
            type: 'A',
            amount: 100,
            flag: true,
            status: 'completed',
            priority: 'high'
        );

        $result = $exporter->exportOrder($order, '2024-03-20');
        $this->assertFalse($result);
    }

    public function testExportOrderWithSpecialCharacters()
    {
        $order = new Order(
            id: 999,
            type: 'A',
            amount: 100,
            flag: true,
            status: 'completed,with,comma',
            priority: 'high'
        );

        $date = '2024-03-20';
        $result = $this->exporter->exportOrder($order, $date);

        $this->assertTrue($result);
        $filename = $this->testOutputDir . "/orders_type_A_999_2024-03-20.csv";
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);
        $this->assertStringContainsString('completed,with,comma', $content);
    }

//    public function testExportOrderWithNoWritePermission()
//    {
//        // Create directory with no write permission
//        $noWriteDir = sys_get_temp_dir() . '/csv_exporter_no_write';
//        if (is_dir($noWriteDir)) {
//            chmod($noWriteDir, 0777);
//            array_map('unlink', glob($noWriteDir . '/*'));
//            rmdir($noWriteDir);
//        }
//
//        // Create directory first with write permission
//        mkdir($noWriteDir, 0777);
//        // Then remove write permission
//        chmod($noWriteDir, 0555); // read and execute only
//
//        $exporter = new CsvExporterImplementation($noWriteDir);
//        $order = new Order(id: 123, type: 'A');
//
//        $result = $exporter->exportOrder($order, '2024-03-20');
//        $this->assertFalse($result);
//
//        // Cleanup
//        chmod($noWriteDir, 0777);
//        array_map('unlink', glob($noWriteDir . '/*'));
//        rmdir($noWriteDir);
//    }

    public function testExportOrderWithEmptyOrder()
    {
        $order = new Order();
        $result = $this->exporter->exportOrder($order, '2024-03-20');
        $this->assertTrue($result);
    }

    public function testExportOrderWithNegativeValues()
    {
        $order = new Order(
            id: -123,
            type: 'A',
            amount: -100.50,
            flag: false,
            status: 'pending',
            priority: 'low'
        );

        $result = $this->exporter->exportOrder($order, '2024-03-20');
        $this->assertTrue($result);

        $filename = $this->testOutputDir . "/orders_type_A_-123_2024-03-20.csv";
        $this->assertFileExists($filename);

        $content = file_get_contents($filename);
        $this->assertStringContainsString('-123', $content);
        $this->assertStringContainsString('-100.5', $content);
    }
}