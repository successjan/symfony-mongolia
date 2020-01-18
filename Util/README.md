# symfony-mongolia


Excel.php

Symfony-гийн phpspreadsheet library ашиглаж excel рүү хөрвүүлэх class.

composer require phpoffice/phpspreadsheet

USAGE

```php

<?php 
$columns = array('№', 'Овог', 'Нэр', 'Регистр', 'Төлөв', 'Утас', 'И-мэйл', 'Огноо');
$excel = new Excel('Харилцагчид', $columns);
$spreadsheet = $excel->create();

$list = $excel->getData('holders', $params, $this->get('session'));

if (!is_null($list)) {
    $i = 2;
    foreach ($list as $row) {
        $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $i, ($i - 1))
                ->setCellValue('B' . $i, $row['lastName])
                ->setCellValue('C' . $i, $row['firstName'])
                ->setCellValue('D' . $i, $row['registerNumber])
                ->setCellValue('E' . $i, $row['statusName'])
                ->setCellValue('F' . $i, $row['mobileNumber])
                ->setCellValue('G' . $i, $row['email'])
                ->setCellValue('H' . $i, $row['createdDate']);
        $i++;
    }
}

$excel->setAutoSize('A', 'H');
$excel->setHeaderStyle('A1:H1', $i);

$excel->export();


```


Pdf.php

Symfony-гийн KnpSnappy bundle ашиглаж html to pdf рүү хөрвүүлэх class.

Usage

```php
<?php
$htmlContent = $request->get('htmlContent');

$basePath = $_SERVER['DOCUMENT_ROOT'];

$css .= '<style type="text/css">';
$css .= Pdf::getCss();
$css .= '</style>';

$pdf = Pdf::createSnappyPdf();

Pdf::setSnappyOutput($pdf, $css . $htmlContent, 'Title');

```


FcmNotication.php

Firebase ашиглан push notification явуулах class.

Usage

```php
<?php
$msg = [
    'title' => $title,
    'body' => $message,
    'click_action' => $directUrl
];

$tokenList = array(); //token list

if ($tokenList && !is_null($tokenList)) {
    $tokens = [];

    foreach ($tokenList as $token) {
        $tokens[] = $token['fcm_token'];
    }

    FcmNotification::send($tokens, $msg);
}

```