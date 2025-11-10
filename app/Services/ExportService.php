<?php

namespace App\Services;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public function exportProducts(string $fileName = null): BinaryFileResponse
    {
        $fileName = $fileName ?: 'products_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ProductsExport, $fileName);
    }

    public function generateExportFileName(string $prefix = 'products'): string
    {
        return $prefix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
    }
}
