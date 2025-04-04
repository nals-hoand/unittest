<?php

namespace App;

/**
 * Interface CsvExporter
 *
 * This interface defines a contract for exporting an order to a CSV file.
 */
interface CsvExporter
{
    /**
     * Exports the given order to a CSV file with the specified filename.
     *
     * @param Order $order The order to be exported.
     * @param string $filename The name of the CSV file to export to.
     * @return bool True if the export was successful, false otherwise.
     */
    public function exportOrder(Order $order, string $filename): bool;
}