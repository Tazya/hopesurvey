<?php

declare(strict_types=1);

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportData
{
    /**
     * @param array $data
     *
     * Create a new spreadsheet with survey data
     */
    public function create(array $data)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
        ->setCreator('Nadezhda Marchenko')
        ->setLastModifiedBy('Nadezhda Marchenko')
        ->setTitle('PhpSpreadsheet Test Document')
        ->setSubject('PhpSpreadsheet Test Document')
        ->setDescription('Test document for PhpSpreadsheet, generated using PHP classes.')
        ->setKeywords('office PhpSpreadsheet php')
        ->setCategory('Test result file');

        $spreadsheet->getDefaultStyle()
        ->getFont()
        ->setName('Arial')
        ->setSize(10);


        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A2', 'Clear data of Methodic 1 (Boyko) Exported from hope-survey.kz');

        $columns = ['Номер:', 'Дата создания:', 'Пол:', 'Возраст:', 'Ориентация:'];
        // $excelLetters = ['F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        //     'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO',
        //     'AP','AQ','AR','AS','AT','AU','AV','AW','AX']; // 45 letters

        for ($i = 0; $i < 45; $i++) {
            $current = $i + 1;
            $columns[] = "№{$current}";
        }

        $spreadsheet->getActiveSheet()->fromArray(
            $columns,       // The data to set
            null,           // Array values with this value will not be set
            "B1" // Top left coordinate of the worksheet range where
        );

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $spreadsheet->getActiveSheet()
        ->setTitle('Methodic 1');

        foreach (array_values(array_reverse($data)) as $key => $result) {
            $this->formResultMeth1($spreadsheet, $key + 1, $result);
        }
        $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('exported/result.xlsx');
    }

    private function formResultMeth1(Spreadsheet $spreadsheet, int $number, array $result): Spreadsheet
    {
        $position = $number + 1;
        $date = substr($result['date'], 0, 10);
        $spreadsheet->getActiveSheet()
        ->setCellValue("B{$position}", $number) // Result Number
        ->setCellValue("C{$position}", Date::PHPToExcel($date))
        ->setCellValue("D{$position}", $result['sex'])
        ->setCellValue("E{$position}", $result['data']['answers']['final']['age'])
        ->setCellValue("F{$position}", $result['orientation']);

        $answers = $result['data']['answers']['Methodic 1'];
        unset($answers['name']);
        
        // foreach (array_values($answers) as $key => $value) {
        //     $spreadsheet->getActiveSheet()->setCellValue("{$excelLetters[$key]}{$position}", $value);
        // }

        $spreadsheet->getActiveSheet()->fromArray(
            $answers,       // The data to set
            null,           // Array values with this value will not be set
            "G{$position}" // Top left coordinate of the worksheet range where
        );
        
        $spreadsheet->getActiveSheet()
            ->getStyle("C{$position}")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_DATE_XLSX14);
        return $spreadsheet;
    }

    public function testFile()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
        ->setCreator('Nadezhda Marchenko')
        ->setLastModifiedBy('Nadezhda Marchenko')
        ->setTitle('PhpSpreadsheet Test Document')
        ->setSubject('PhpSpreadsheet Test Document')
        ->setDescription('Test document for PhpSpreadsheet, generated using PHP classes.')
        ->setKeywords('office PhpSpreadsheet php')
        ->setCategory('Test result file');

        $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('B2', 'Hope Survey Spreadsheet');

        $spreadsheet->getActiveSheet()
        ->setTitle('Methodic 1');

        $writer = new Xlsx($spreadsheet);
        $writer->save('exported/hello world.xlsx');
    }
}
