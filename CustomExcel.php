<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * PhpSpreadsheet custom excel class
 *
 * @author Satjan
 */
class Excel {

    private $spreadsheet;
    private $activeSheet;
    private $columns;
    private $fileName;
    private $freezePane;
    private $rs;

    CONST CREATOR = 'CREATOR';
    CONST TITLE = 'TITLE';
    CONST KEYWORDS = 'KEYWORDS BLA BLA';

    public function __construct($fileName, $columns, $freezePane = 'A2') {
        $this->rs = new Service();
        $this->fileName = $fileName;
        $this->columns = $columns;
        $this->freezePane = $freezePane;
    }

    public function create() {

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator(self::CREATOR)
                ->setLastModifiedBy(self::CREATOR)
                ->setTitle(self::TITLE)
                ->setSubject(self::TITLE)
                ->setDescription($this->fileName)
                ->setKeywords(self::KEYWORDS)
                ->setCategory('EXCEL FILE');

        $this->spreadsheet = $spreadsheet;
        $this->activeSheet = $spreadsheet->getActiveSheet();

        $this->activeSheet->setTitle($this->fileName);
        $this->activeSheet->freezePane($this->freezePane);
        $this->activeSheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

        $spreadsheet->setActiveSheetIndex(0);

        $columns = $this->columns;
        $start = 'A';
        foreach ($columns as $column) {
            $this->activeSheet->setCellValue($start . '1', mb_strtoupper($column));
            ++$start;
        }

        return $spreadsheet;
    }

    public function setAutoSize($from, $to, $isAutoSize = true) {
        $sheet = $this->activeSheet;
        foreach (self::excelColumnRange($from, $to) as $value) {
            $sheet->getColumnDimension($value)->setAutoSize($isAutoSize);
        }

        if ($isAutoSize) {
            $sheet->calculateColumnWidths();
        }
    }

    public function setHeaderStyle($range, $i) {

        $color = str_replace('#', '', sellColor);

        $sheet = $this->activeSheet;
        $sheet->getStyle($range)->applyFromArray(
                array(
                    'font' => array(
                        'bold' => false,
                        'color' => array('rgb' => 'FFFFFF'),
                    ),
                    'alignment' => array(
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => Border::BORDER_THIN
                        )
                    ),
                    'fill' => array(
                        'fillType' => Fill::FILL_SOLID,
                        'color' => array('rgb' => $color)
                    )
                )
        );

        $rangeArr = explode(":", $range);
        $second = str_replace('1', '', $rangeArr[1]);

        $sheet->getStyle('A1:' . $second . ($i - 1))->applyFromArray(
                [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_DASHED,
                            'color' => ['rgb' => '000000'],
                        ],
                    ]
                ]
        );
    }
  
    public function getData($url, $params, Session $session = null) {

        if (isset($params['field'])) {
            $params['field'] = ($params['field'] + 1);
        }

        $service = new Service($session);
        $list = $service->callGet($url, $params);

        if ($list['status'] == 'success' && !is_null($list['data']) && isset($list['data']['data'])) {
            return $list['data']['data'];
        } else if ($list['status'] == 'success' && !is_null($list['data'])) {
            return $list['data'];
        }

        return null;
    }

    public function export() {

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->fileName . ' (' . date('Y-m-d H:i:s') . ').xlsx"');
        header('Cache-Control: max-age=0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        header('Set-Cookie: fileDownload=true; path=/');

        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    private static function excelColumnRange($lower, $upper) {
        ++$upper;
        for ($i = $lower; $i !== $upper; ++$i) {
            yield $i;
        }
    }

}