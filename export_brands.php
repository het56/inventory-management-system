<?php
// Include the database connection and any necessary files
$localhost = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$connect = new mysqli($localhost, $username, $password, $dbname);

if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
}

// Fetch brand data from the database
$sql = "SELECT * FROM brands";
$result = $connect->query($sql);

// Create a new PHPExcel object
require_once 'C:\xampp\htdocs\stock_system\PHPExcel.php';
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Your Name")
                             ->setLastModifiedBy("Your Name")
                             ->setTitle("Brand Data")
                             ->setSubject("Brand Data")
                             ->setDescription("Brand Data");

// Add data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Brand Name')
            ->setCellValue('B1', 'Status');

if ($result->num_rows > 0) {
    $rowIndex = 2; // Start from row 2
    while($row = $result->fetch_assoc()) {
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $rowIndex, $row['brandName'])
                    ->setCellValue('B' . $rowIndex, ($row['brandStatus'] == 1) ? 'Available' : 'Not Available');
        $rowIndex++;
    }
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Brand Data');

// Set headers to download Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="brand_data.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

// Close database connection
$connect->close();
