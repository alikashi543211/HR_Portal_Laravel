<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportMultipleSheets implements WithMultipleSheets
{

    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new PayRollExport();
        $sheets[] = new BankLetterExport();
        return $sheets;
    }
}
