# symfony-mongolia

CustomExcel.php

```php

<?php 
$columns = array('№', 'Овог', 'Нэр', 'Регистр', 'Төлөв', 'Утас', 'И-мэйл', 'Огноо');
$excel = new CustomExcel('Харилцагчид', $columns);
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
