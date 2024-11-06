<?php
include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['orders_file'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    if ($file_error === 0) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if ($file_ext == 'xlsx' || $file_ext == 'xls') {
            $file_new_name = uniqid('', true) . '.' . $file_ext;
            $file_destination = 'uploads/' . $file_new_name;
            move_uploaded_file($file_tmp, $file_destination);

            // Read Excel file
            require 'vendor/autoload.php';
            use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file_destination);
            $sheet = $spreadsheet->getActiveSheet();

            // Insert data into database
            $sql = "INSERT INTO place_courier (invoice_date, account_name, address1, invoice_number, order_number, external_order, delivery_date_time, area, drivers_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            foreach ($sheet->getRowIterator() as $row) {
                $data = array(
                    $row->getCellByColumnAndRow(1, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(2, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(3, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(4, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(5, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(6, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(7, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(8, $row->getRowIndex())->getValue(),
                    $row->getCellByColumnAndRow(9, $row->getRowIndex())->getValue()
                );
                $stmt->execute($data);
            }
            $stmt->close();
            echo 'Orders uploaded successfully!';
        } else {
            echo 'Invalid file type!';
        }
    } else {
        echo 'Error uploading file!';
    }
}
?>