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
     * Data for export.
     *
     * @var data[]
     */
    public $data;

    /**
     * Spreadsheet object.
     *
     * @var properties[]
     */
    public $properties;

    /**
     * Spreadsheet object.
     *
     * @var Spreadsheet
     */
    public $spreadsheet;

    /**
     * Worksheet index
     *
     * @var worksheetIndex
     */
    private $worksheetIndex = 0;

    /**
     * Create a new spreadsheet with survey data
     *
     * @param array $data
     * @param array $propertiess
     */
    public function __construct(array $data, array $properties = [])
    {
        $properties = array_merge([
            'creator' => 'Nadezhda Marchenko',
            'lastModified' => 'Nadezhda Marchenko',
            'title' => 'Survey Data for Diploma',
            'subject' => 'Survey Data for Diploma',
            'description' => 'Файл сожержит результаты исследования по дипломной работе Надежды Марченко',
            'keywords' => 'research survey',
            'category' => 'data'
        ], $properties);

        // Set a properties
        $this->properties = $properties;

        // Set a data
        $this->data = $data;
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
        ->setCreator($properties['creator'])
        ->setLastModifiedBy($properties['lastModified'])
        ->setTitle($properties['title'])
        ->setSubject($properties['subject'])
        ->setDescription($properties['description'])
        ->setKeywords($properties['keywords'])
        ->setCategory($properties['category']);

        $spreadsheet->getDefaultStyle()
        ->getFont()
        ->setName('Arial')
        ->setSize(10);

        $this->spreadsheet = $spreadsheet;
    }

    /**
     * Create a new spreadsheet with survey data
     *
     * @param string $path
     */
    public function writeDocument($path = 'exported/result.xlsx')
    {
        
        $columnsMeth1 = [];
        $columnsMeth2 = [];
        $columnsMeth4 = [];
        $columnsMeth5 = [];
        $columnsMeth6 = [];

        for ($i = 0; $i < 45; $i++) {
            $current = $i + 1;
            $columnsMeth1[] = "№{$current}";
        }
        for ($i = 0; $i < 22; $i++) {
            $current = $i + 1;
            $columnsMeth2[] = "№{$current}";
        }
        for ($i = 0; $i < 21; $i++) {
            $current = $i + 1;
            $columnsMeth4[] = "№{$current}";
        }
        for ($i = 0; $i < 20; $i++) {
            $current = $i + 1;
            $columnsMeth5[] = "№{$current} отн";
            $columnsMeth5[] = "№{$current}";
        }
        for ($i = 0; $i < 21 / 3; $i++) {
            $current = $i + 1;
            $s = $i + 2;
            $a = $i + 3;
            $columnsMeth6[] = "№{$current} О";
            $columnsMeth6[] = "№{$s} C";
            $columnsMeth6[] = "№{$a} A";
        }
        $columnsMeth5[] = "Вывод";

        $columns = [
            $columnsMeth1,
            $columnsMeth2,
            ['Оценка Кинси'],
            $columnsMeth4,
            $columnsMeth5,
            $columnsMeth6,
        ];

        $this->createSheet(
            'Methodic 1',
            'Методика 1 Бойко',
            'Clear data of Methodic 1 (Boyko) Exported from hope-survey.kz',
            $columns[0]
        );
        $this->createSheet(
            'Methodic 2',
            'Методика 2 Толерантность',
            'Clear data of Methodic 2 Exported from hope-survey.kz',
            $columns[1]
        );
        $this->createSheet(
            'Methodic 3',
            'Методика 3 Кинси',
            'Clear data of Methodic 3 Exported from hope-survey.kz',
            $columns[2]
        );
        $this->createSheet(
            'Methodic 4',
            'Методика 4 Клейн',
            'Clear data of Methodic 4 Exported from hope-survey.kz',
            $columns[3]
        );
        $this->createSheet(
            'Methodic 5',
            'Методика 5 Индивидуальности',
            'Clear data of Methodic 5 Exported from hope-survey.kz',
            $columns[4]
        );
        $this->createSheet(
            'Methodic 6',
            'Методика 6 Качества',
            'Clear data of Methodic 6 Exported from hope-survey.kz',
            $columns[5]
        );

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($path);
    }

    /**
     * Create a new sheet with methodic
     *
     * @param string $methodic
     * @param string $titles
     * @param string $descr
     * @param array  $aTitles
     */
    private function createSheet(string $methodic, string $title = "New title", string $descr = "", array $aTitles = [])
    {
        //$spreadsheet->getWorksheetIterator()
        if ($this->worksheetIndex > 0) {
            $this->spreadsheet->createSheet();
        }

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->setCellValue('A2', $title)
        ->setCellValue('A3', $descr)
        ->mergeCells('A3:A6');

        $this->spreadsheet->getActiveSheet()
        ->getStyle('A2')->getFont()->setBold(true);
        $this->spreadsheet->getActiveSheet()
        ->getStyle('A3:A6')->getAlignment()->setWrapText(true);

        $headersColumns = ['Номер:', 'Дата создания:', 'Пол:', 'Возраст:', 'Ориентация:'];
        $columns = array_merge($headersColumns, $aTitles);
        // $excelLetters = ['F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        //     'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO',
        //     'AP','AQ','AR','AS','AT','AU','AV','AW','AX']; // 45 letters

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns,       // The data to set
            null,           // Array values with this value will not be set
            "B1"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $this->spreadsheet->getActiveSheet()
        ->setTitle($title);

        foreach (array_values(array_reverse($this->data)) as $key => $result) {
            $this->formResult($key + 1, $result, $methodic);
        }
        $this->spreadsheet->getActiveSheet()->setAutoFilter(
            'B1:F1'
            // $this->spreadsheet->getActiveSheet()->calculateWorksheetDimension()
        );
        $this->spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $this->worksheetIndex = $this->worksheetIndex + 1;
    }

    /**
     * Set a result in the sheet
     *
     * @param int $number
     * @param array $result
     * @param string $methodic
     */
    private function formResult(int $number, array $result, string $methodic)
    {
        $position = $number + 1;
        $date = substr($result['date'], 0, 10);
        $this->spreadsheet->getActiveSheet()
        ->setCellValue("B{$position}", $number) // Result Number
        ->setCellValue("C{$position}", Date::PHPToExcel($date))
        ->setCellValue("D{$position}", $result['sex'])
        ->setCellValue("E{$position}", $result['data']['answers']['final']['age'])
        ->setCellValue("F{$position}", $result['orientation']);
        $answers = $result['data']['answers'][$methodic];
        unset($answers['name']);

        if ($methodic === "Methodic 5") {
            $answers = array_map(function ($el) {
                return "\"{$el}\"";
            }, $answers);
        }

        $this->spreadsheet->getActiveSheet()->fromArray(
            $answers,       // The data to set
            null,           // Array values with this value will not be set
            "G{$position}"  // Top left coordinate of the worksheet range where
        );
        
        $this->spreadsheet->getActiveSheet()
            ->getStyle("C{$position}")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_DATE_XLSX14);
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
