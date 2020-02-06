<?php

declare(strict_types=1);

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Statistic;

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

        $this->createStatSheet();
        $this->createKunSheet();

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

        foreach (array_values(array_reverse($this->data['all'])) as $key => $result) {
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

    /**
     * Create a new sheet with methodic
     *
     * @param string $methodic
     * @param string $titles
     * @param string $descr
     * @param array  $aTitles
     */
    private function createStatSheet(string $title = "Средние результаты по группам")
    {
        //$spreadsheet->getWorksheetIterator()
        if ($this->worksheetIndex > 0) {
            $this->spreadsheet->createSheet();
        }

        $statistic = new Statistic();
        $data = $this->data;
        $countResults = $statistic->countAllResults($data);
        $middleResults = $statistic->getAllMiddleResults($data);

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->mergeCells("A4:A5");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A6:A7");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A8:A9");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A10:A11");

        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    // 'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $this->spreadsheet->getActiveSheet()->getStyle('A3:C11')->applyFromArray($styleArray);
        $this->spreadsheet->getActiveSheet()->getStyle('A14:F22')->applyFromArray($styleArray);
        $this->spreadsheet->getActiveSheet()->getStyle('A25:C33')->applyFromArray($styleArray);
        $this->spreadsheet->getActiveSheet()->getStyle('A36:C44')->applyFromArray($styleArray);
        $this->spreadsheet->getActiveSheet()->getStyle('A47:F55')->applyFromArray($styleArray);

        $this->spreadsheet->getActiveSheet()
        ->getStyle('A3:A100')->getAlignment()->setWrapText(true);

        $columns1 = [
            ['Средние баллы респондентов', $title, ''],
            ['Ср.Общий балл по методике', 'Гетеро респонденты: ', $middleResults['hetero']['Methodic 1']],
            ['', 'ЛГБТ респонденты', $middleResults['lgbt']['Methodic 1']],
            ['Ср. балл по полу респондентов', 'Мужчины:', $middleResults['men']['Methodic 1']],
            ['', 'Женщины:', $middleResults['women']['Methodic 1']],
            ['Ср. балл гетеро группы', 'Мужчины:', $middleResults['heteroMen']['Methodic 1']],
            ['', 'Женщины:', $middleResults['heteroWomen']['Methodic 1']],
            ['Ср. балл ЛГБТ группы', 'Мужчины:', $middleResults['lgbtMen']['Methodic 1']],
            ['', 'Женщины:', $middleResults['lgbtWomen']['Methodic 1']],
        ];

        $columns2 = [
            ['Средние баллы респондентов', 'Методика 2 Индекс толерантности', 'Общий', 'Этническая', 'Социальная', 'Как черта личности'],
            [
                'Ср.Общий балл по методике',
                'Гетеро респонденты: ',
                $middleResults['hetero']['Methodic 2']['all'],
                $middleResults['hetero']['Methodic 2']['scale-1'],
                $middleResults['hetero']['Methodic 2']['scale-2'],
                $middleResults['hetero']['Methodic 2']['scale-3']],
            [
                '',
                'ЛГБТ респонденты',
                $middleResults['lgbt']['Methodic 2']['all'],
                $middleResults['lgbt']['Methodic 2']['scale-1'],
                $middleResults['lgbt']['Methodic 2']['scale-2'],
                $middleResults['lgbt']['Methodic 2']['scale-3']],
            [
                'Ср. балл по полу респондентов',
                'Мужчины:',
                $middleResults['men']['Methodic 2']['all'],
                $middleResults['men']['Methodic 2']['scale-1'],
                $middleResults['men']['Methodic 2']['scale-2'],
                $middleResults['men']['Methodic 2']['scale-3']],
            [
                '',
                'Женщины:',
                $middleResults['women']['Methodic 2']['all'],
                $middleResults['women']['Methodic 2']['scale-1'],
                $middleResults['women']['Methodic 2']['scale-2'],
                $middleResults['women']['Methodic 2']['scale-3']],
            [
                'Ср. балл гетеро группы',
                'Мужчины:',
                $middleResults['heteroMen']['Methodic 2']['all'],
                $middleResults['heteroMen']['Methodic 2']['scale-1'],
                $middleResults['heteroMen']['Methodic 2']['scale-2'],
                $middleResults['heteroMen']['Methodic 2']['scale-3']],
            [
                '',
                'Женщины:',
                $middleResults['heteroWomen']['Methodic 2']['all'],
                $middleResults['heteroWomen']['Methodic 2']['scale-1'],
                $middleResults['heteroWomen']['Methodic 2']['scale-2'],
                $middleResults['heteroWomen']['Methodic 2']['scale-3']],
            [
                'Ср. балл ЛГБТ группы',
                'Мужчины:',
                $middleResults['lgbtMen']['Methodic 2']['all'],
                $middleResults['lgbtMen']['Methodic 2']['scale-1'],
                $middleResults['lgbtMen']['Methodic 2']['scale-2'],
                $middleResults['lgbtMen']['Methodic 2']['scale-3']],
            [
                '',
                'Женщины:',
                $middleResults['lgbtWomen']['Methodic 2']['all'],
                $middleResults['lgbtWomen']['Methodic 2']['scale-1'],
                $middleResults['lgbtWomen']['Methodic 2']['scale-2'],
                $middleResults['lgbtWomen']['Methodic 2']['scale-3']],
        ];

        $columns3 = [
            ['Средние баллы респондентов', 'Методика 3 Кинси', ''],
            ['Ср.Общий балл по методике', 'Гетеро респонденты: ', $middleResults['hetero']['Methodic 3']],
            ['', 'ЛГБТ респонденты', $middleResults['lgbt']['Methodic 3']],
            ['Ср. балл по полу респондентов', 'Мужчины:', $middleResults['men']['Methodic 3']],
            ['', 'Женщины:', $middleResults['women']['Methodic 3']],
            ['Ср. балл гетеро группы', 'Мужчины:', $middleResults['heteroMen']['Methodic 3']],
            ['', 'Женщины:', $middleResults['heteroWomen']['Methodic 3']],
            ['Ср. балл ЛГБТ группы', 'Мужчины:', $middleResults['lgbtMen']['Methodic 3']],
            ['', 'Женщины:', $middleResults['lgbtWomen']['Methodic 3']],
        ];

        $columns4 = [
            ['Средние баллы респондентов', 'Методика 4 Клейн', ''],
            ['Ср.Общий балл по методике', 'Гетеро респонденты: ', $middleResults['hetero']['Methodic 4'] / 21],
            ['', 'ЛГБТ респонденты', $middleResults['lgbt']['Methodic 4'] / 21],
            ['Ср. балл по полу респондентов', 'Мужчины:', $middleResults['men']['Methodic 4'] / 21],
            ['', 'Женщины:', $middleResults['women']['Methodic 4'] / 21],
            ['Ср. балл гетеро группы', 'Мужчины:', $middleResults['heteroMen']['Methodic 4'] / 21],
            ['', 'Женщины:', $middleResults['heteroWomen']['Methodic 4']],
            ['Ср. балл ЛГБТ группы', 'Мужчины:', $middleResults['lgbtMen']['Methodic 4'] / 21],
            ['', 'Женщины:', $middleResults['lgbtWomen']['Methodic 4'] / 21],
        ];

        $columns6 = [
            ['Средние баллы респондентов', 'Методика 6 Кинси', 'Общий', 'Сила', 'Оценка','Активность'],
            [
                'Ср.Общий балл по методике',
                'Гетеро респонденты: ',
                $middleResults['hetero']['Methodic 6']['all'],
                $middleResults['hetero']['Methodic 6']['o'],
                $middleResults['hetero']['Methodic 6']['s'],
                $middleResults['hetero']['Methodic 6']['a']],
            [
                '',
                'ЛГБТ респонденты',
                $middleResults['lgbt']['Methodic 6']['all'],
                $middleResults['lgbt']['Methodic 6']['o'],
                $middleResults['lgbt']['Methodic 6']['s'],
                $middleResults['lgbt']['Methodic 6']['a']],
            [
                'Ср. балл по полу респондентов',
                'Мужчины:',
                $middleResults['men']['Methodic 6']['all'],
                $middleResults['men']['Methodic 6']['o'],
                $middleResults['men']['Methodic 6']['s'],
                $middleResults['men']['Methodic 6']['a']],
            [
                '',
                'Женщины:',
                $middleResults['women']['Methodic 6']['all'],
                $middleResults['women']['Methodic 6']['o'],
                $middleResults['women']['Methodic 6']['s'],
                $middleResults['women']['Methodic 6']['a']],
            [
                'Ср. балл гетеро группы',
                'Мужчины:',
                $middleResults['heteroMen']['Methodic 6']['all'],
                $middleResults['heteroMen']['Methodic 6']['o'],
                $middleResults['heteroMen']['Methodic 6']['s'],
                $middleResults['heteroMen']['Methodic 6']['a']],
            [
                '',
                'Женщины:',
                $middleResults['heteroWomen']['Methodic 6']['all'],
                $middleResults['heteroWomen']['Methodic 6']['o'],
                $middleResults['heteroWomen']['Methodic 6']['s'],
                $middleResults['heteroWomen']['Methodic 6']['a']],
            [
                'Ср. балл ЛГБТ группы',
                'Мужчины:',
                $middleResults['lgbtMen']['Methodic 6']['all'],
                $middleResults['lgbtMen']['Methodic 6']['o'],
                $middleResults['lgbtMen']['Methodic 6']['s'],
                $middleResults['lgbtMen']['Methodic 6']['a']],
            [
                '',
                'Женщины:',
                $middleResults['lgbtWomen']['Methodic 6']['all'],
                $middleResults['lgbtWomen']['Methodic 6']['o'],
                $middleResults['lgbtWomen']['Methodic 6']['s'],
                $middleResults['lgbtWomen']['Methodic 6']['a']],
        ];

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns1,       // The data to set
            null,           // Array values with this value will not be set
            "A3"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->mergeCells("A15:A16");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A17:A18");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A19:A20");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A21:A22");

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns2,       // The data to set
            null,           // Array values with this value will not be set
            "A14"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->mergeCells("A26:A27");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A28:A29");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A30:A31");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A32:A33");

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns3,       // The data to set
            null,           // Array values with this value will not be set
            "A25"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->mergeCells("A37:A38");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A39:A40");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A41:A42");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A43:A44");

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns4,       // The data to set
            null,           // Array values with this value will not be set
            "A36"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex)
        ->mergeCells("A48:A49");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A50:A51");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A52:A53");
        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A54:A55");

        $this->spreadsheet->getActiveSheet()->fromArray(
            $columns6,       // The data to set
            null,           // Array values with this value will not be set
            "A47"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(14);

        $this->spreadsheet->getActiveSheet()
        ->setTitle($title);

        $this->worksheetIndex = $this->worksheetIndex + 1;
    }

    private function createKunSheet(string $title = "Группы по Куну")
    {
        //$spreadsheet->getWorksheetIterator()
        if ($this->worksheetIndex > 0) {
            $this->spreadsheet->createSheet();
        }
        $this->spreadsheet->setActiveSheetIndex($this->worksheetIndex);

        $statistic = new Statistic();
        $data = $this->data;

        $headers = ['Мужчины Гетеро', '', '', 'Мужчины ЛГБТ.', '', '', 'Женщины Гетеро', '', '', 'Женщины ЛГБТ'];

        $this->spreadsheet->getActiveSheet()->fromArray(
            $headers,       // The data to set
            null,           // Array values with this value will not be set
            "A1"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()
        ->mergeCells("A1:B1")->mergeCells("D1:E1")->mergeCells("G1:H1")->mergeCells("J1:K1");

        $this->spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(18);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(4);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(4);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(4);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $this->spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(4);

        $individualsMenHeteroKeys = array_keys($statistic->getindividualsMethodic5($data['heteroMen']));
        $individualsMenLgbtKeys = array_keys($statistic->getindividualsMethodic5($data['lgbtMen']));
        $individualsWomenHeteroKeys = array_keys($statistic->getindividualsMethodic5($data['heteroWomen']));
        $individualsWomenLgbtKeys = array_keys($statistic->getindividualsMethodic5($data['lgbtWomen']));

        $menHeteroKeys = array_chunk(
            array_map(function ($el) {
                return "\"{$el}\"";
            }, $individualsMenHeteroKeys),
            1
        );
        $menLgbtKeys = array_chunk(
            array_map(function ($el) {
                return "\"{$el}\"";
            }, $individualsMenLgbtKeys),
            1
        );
        $womenHeteroKeys = array_chunk(
            array_map(function ($el) {
                return "\"{$el}\"";
            }, $individualsWomenHeteroKeys),
            1
        );
        $womenLgbtKeys = array_chunk(
            array_map(function ($el) {
                return "\"{$el}\"";
            }, $individualsWomenLgbtKeys),
            1
        );


        $individualsMenHetero = array_chunk($statistic->getindividualsMethodic5($data['heteroMen']), 1);
        $individualsMenLgbt = array_chunk($statistic->getindividualsMethodic5($data['lgbtMen']), 1);
        $individualsWomenHetero = array_chunk($statistic->getindividualsMethodic5($data['heteroWomen']), 1);
        $individualsWomenLgbt = array_chunk($statistic->getindividualsMethodic5($data['lgbtWomen']), 1);

        $this->spreadsheet->getActiveSheet()->fromArray(
            $menHeteroKeys,       // The data to set
            null,           // Array values with this value will not be set
            "A2"            // Top left coordinate of the worksheet range where
        );
        $this->spreadsheet->getActiveSheet()->fromArray(
            $individualsMenHetero,       // The data to set
            null,           // Array values with this value will not be set
            "B2"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()->fromArray(
            $menLgbtKeys,       // The data to set
            null,           // Array values with this value will not be set
            "D2"            // Top left coordinate of the worksheet range where
        );
        $this->spreadsheet->getActiveSheet()->fromArray(
            $individualsMenLgbt,       // The data to set
            null,           // Array values with this value will not be set
            "E2"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()->fromArray(
            $womenHeteroKeys,       // The data to set
            null,           // Array values with this value will not be set
            "G2"            // Top left coordinate of the worksheet range where
        );
        $this->spreadsheet->getActiveSheet()->fromArray(
            $individualsWomenHetero,       // The data to set
            null,           // Array values with this value will not be set
            "H2"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()->fromArray(
            $womenLgbtKeys,       // The data to set
            null,           // Array values with this value will not be set
            "J2"            // Top left coordinate of the worksheet range where
        );
        $this->spreadsheet->getActiveSheet()->fromArray(
            $individualsWomenLgbt,       // The data to set
            null,           // Array values with this value will not be set
            "K2"            // Top left coordinate of the worksheet range where
        );

        $this->spreadsheet->getActiveSheet()
        ->setTitle($title);

        $this->worksheetIndex = $this->worksheetIndex + 1;
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
