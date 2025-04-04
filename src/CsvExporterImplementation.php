<?php

namespace App;

/**
 * Class CsvExporterImplementation
 *
 * This class implements the CsvExporter interface and provides the functionality
 * to export an order to a CSV file.
 */
class CsvExporterImplementation implements CsvExporter
{
    /**
     * @var string The directory where the CSV files will be saved.
     */
    private string $outputDir;

    /**
     * CsvExporterImplementation constructor.
     *
     * @param string $outputDir The directory where the CSV files will be saved.
     */
    public function __construct(string $outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * Exports the given order to a CSV file with the specified date.
     *
     * @param Order $order The order to be exported.
     * @param string $date The date to be included in the filename.
     * @return bool True if the export was successful, false otherwise.
     */
    public function exportOrder(Order $order, string $date): bool
    {
        $filename = sprintf(
            '%s/orders_type_A_%d_%s.csv',
            $this->outputDir,
            $order->id,
            $date
        );

        $fileHandle = fopen($filename, 'w');
        if ($fileHandle === false) {
            return false;
        }

        fputcsv($fileHandle, ['ID', 'Type', 'Amount', 'Flag', 'Status', 'Priority']);
        fputcsv($fileHandle, [
            $order->id,
            $order->type,
            $order->amount,
            $order->flag ? 'true' : 'false',
            $order->status,
            $order->priority
        ]);

        if ($order->amount > 150) {
            fputcsv($fileHandle, ['Note', 'High value order']);
        }

        fclose($fileHandle);
        return true;
    }
}