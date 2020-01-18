<?php

/**
 * Pdf Class
 *
 * @category	Pdf
 * @author	Satjan
 */
use Knp\Snappy\Pdf as SnappyPdf;

class Pdf {

    public function __construct() {
        parent::__construct();
    }

    // <editor-fold defaultstate="collapsed" desc="Knp snappy https://github.com/KnpLabs/snappy">
    public function createSnappyPdf($orientation = 'Portrait', $pageSize = 'A4') {

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $basePath = str_replace('/web', '', $_SERVER['DOCUMENT_ROOT']);
    
        require_once($basePath . '/knp-snappy/vendor/autoload.php');
        require_once($basePath . '/knp-snappy/vendor/knplabs/knp-snappy/config/snappy_config.php');

        $pdf = new SnappyPdf();

        $pdf->setBinary($basePath. '/knp-snappy/vendor/bin/wkhtmltopdf');

        $top = 5;
        $left = KNP_PDF_MARGIN_LEFT;
        $right = 5;
        $bottom = 10;

        $options = array(
            'title' => 'TITLE',
            'orientation' => $orientation,
            'page-size' => !empty($pageSize) ? $pageSize : 'A4',
            'encoding' => 'UTF-8',
            'margin-top' => $top,
            'margin-left' => $left,
            'margin-right' => $right,
            'margin-bottom' => $bottom,
            'header-font-name' => KNP_PDF_FONT_NAME_MAIN,
            'header-font-size' => KNP_PDF_FONT_SIZE_MAIN,
            'footer-font-name' => KNP_PDF_FONT_NAME_DATA,
            'footer-font-size' => KNP_PDF_FONT_SIZE_DATA,
            'footer-line' => true,
            'footer-right' => '[page] / [toPage]',
        );

        $pdf->setOptions($options);
        $pdf->setTimeout(600);

        return $pdf;
    }

    public function setSnappyOutput(SnappyPdf $pdf, $htmlContent, $fileName) {
        header('Content-Disposition: attachment; filename="' . (!is_null($fileName) ? $fileName : 'snappy') . ' - ' . Date::currentDate('YmdHi') . '.pdf"');
        self::setCommonHeader();
        //$replacedHtml = self::getReplacedHtml($htmlContent);
        echo $pdf->getOutputFromHtml($htmlContent);
    }

    public function generateFromHtml(SnappyPdf $pdf, $htmlContent, $fileName) {
        //$replacedHtml = self::getReplacedHtml($htmlContent);
        $pdf->generateFromHtml($htmlContent, $fileName . '.pdf');
    }

    private function getReplacedHtml($htmlContent) {
        $replacedHtml = str_replace(array('"storage/uploads/', "'storage/uploads/"), array('"' . URL . 'storage/uploads/', "'" . URL . "storage/uploads/"), $htmlContent);
        return $replacedHtml;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Common">
    private function setCommonHeader() {
        header('Content-Type: application/pdf');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Set-Cookie: fileDownload=true; path=/');
    }

    // </editor-fold>

    public static function getCss() {
        $css = '';

        $fontFamily = 'Arial, Helvetica, sans-serif';

        $css .= 'background: white; }';

        $css .= '* {
        -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
                box-sizing: border-box;
        }
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
                box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            line-height: 1.4em;
            font: 12px ' . $fontFamily . ';
            color: #000;
            width: 100%; 
            -webkit-print-color-adjust: exact;
        }
        a, a:visited, a:hover, a:active {
            color: inherit; 
            text-decoration: none; 
        } 
        a:after { content:\'\'; } 
        a[href]:after { content: none !important; } 
        .navbar, .sidebar-nav {
            display: none;
        }
        p {
            margin: 0 0 10px;
        } 
        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #ddd;
            border-bottom: 0;
            width: 100%;
        }
        table {
            table-layout: fixed; 
            clear: both;
            border-collapse: collapse;
            page-break-after: auto;
            font-size: 12px;
            border-color: grey;
        }
        tr { page-break-inside:avoid; page-break-after:auto }
        td { page-break-inside:avoid; page-break-after:auto }
        thead {
            display: table-header-group; 
        }
        tbody {
           display: table-row-group;
        }
        tfoot {
            display: table-footer-group;
        }
        table thead th, table thead td, table tbody td, table tfoot td {
            overflow: hidden; 
            word-wrap: break-word;
            padding: 2px 4px !important;
            line-height: 12px;
        }
        table tbody td table tbody td, 
        table tbody td table thead td, 
        table tbody td table thead th, 
        table tbody td table tfoot td {
            padding: 1px 0 !important;
        }
        .ta-c {
            text-align:center !important;
        }
        .ta-r {
            text-align:right !important;
        }
        .ta-l {
            text-align:left !important;
        }';

        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(array("\r\n", '    ', '     '), '', $css);
        $css = str_replace(';}', '}', $css);

        $compressCss = $css;

        return $compressCss;
    }

}