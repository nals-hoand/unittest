<?php

namespace App;

/**
 * Class TypeAOrderHandler
 *
 * This class handles orders of type A by exporting them to a CSV file and updating their status.
 */
class TypeAOrderHandler implements OrderHandlerInterface
{
    /**
     * @var CsvExporter The exporter for CSV operations.
     */
    private CsvExporter $exporter;

    /**
     * TypeAOrderHandler constructor.
     *
     * @param CsvExporter $exporter The exporter for CSV operations.
     */
    public function __construct(CsvExporter $exporter)
    {
        $this->exporter = $exporter;
    }

    /**
     * Handles the given order by exporting it to a CSV file and updating its status.
     *
     * @param Order $order The order to be handled.
     * @return void
     */
    public function handle(Order $order): void
    {
        $date = date('Y-m-d');
        if ($this->exporter->exportOrder($order, $date)) {
            $order->status = 'exported';
        } else {
            $order->status = 'export_failed';
        }
    }
}