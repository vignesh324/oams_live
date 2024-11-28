<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Export dynamic data to Excel with custom headers and data.
 *
 * @param array $headers An array defining headers and their properties.
 * @param array $data The actual data to be populated in the Excel.
 * @param string $filename The filename for the download.
 */
function exportDynamicReportToExcel(array $headers, array $data, string $filename)
{
    // Create a new Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers dynamically
    $currentColumn = 'A';
    foreach ($headers as $header) {
        $columnSpan = $header['colspan'] ?? 1;
        $rowSpan = $header['rowspan'] ?? 1;

        // Merge cells if colspan or rowspan is specified
        if ($columnSpan > 1 || $rowSpan > 1) {
            $endColumn = chr(ord($currentColumn) + $columnSpan - 1);
            $sheet->mergeCells("{$currentColumn}1:{$endColumn}{$rowSpan}");
        }

        // Set header title
        $sheet->setCellValue("{$currentColumn}1", $header['title']);
        $sheet->getStyle("{$currentColumn}1")->getFont()->setBold(true);
        $sheet->getStyle("{$currentColumn}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $currentColumn++;
    }

    // Populate data rows dynamically
    $row = 2; // Start after headers
    foreach ($data as $dataRow) {
        $currentColumn = 'A';
        foreach ($dataRow as $cellData) {
            $sheet->setCellValue("{$currentColumn}{$row}", $cellData);
            $currentColumn++;
        }
        $row++;
    }

    // Set borders for all cells
    $sheet->getStyle("A1:{$currentColumn}{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Output the file to download
    $writer = new Xlsx($spreadsheet);
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header("Cache-Control: max-age=0");
    $writer->save("php://output");
    exit;
}
