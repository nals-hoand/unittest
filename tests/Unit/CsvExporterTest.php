<?php

namespace Tests\Unit;

use App\CsvExporterImplementation;
use App\Order;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
    private CsvExporterImplementation $exporter;
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir();
        $this->exporter = new CsvExporterImplementation($this->tempDir);
    }

    protected function tearDown(): void
    {
        // Clean up temporary files
        $files = glob($this->tempDir . '/orders_type_A_*.csv');
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        parent::tearDown();
    }
} 