<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:login.php");
    exit;
}

$showerror = false;
$showalert = false;
$error_message = '';
$alert_message = '';

try {
    include 'partials/_dbconnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_FILES['excel_file'])) {
            require 'vendor/autoload.php'; // Ensure PhpSpreadsheet is installed

            $file = $_FILES['excel_file']['tmp_name'];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            array_shift($rows); // Skip header row

            $success_count = 0;
            $error_count = 0;

            foreach ($rows as $row) {
                if (count($row) >= 9) {
                    $sql = "INSERT INTO place_courier (invoice_date, account_name, address1, invoice_number, 
                            order_number, external_order, delivery_date_time, area, drivers_name) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("sssssssss",
                            $row[0], // invoice_date
                            $row[1], // account_name
                            $row[2], // address1
                            $row[3], // invoice_number
                            $row[4], // order_number
                            $row[5], // external_order
                            $row[6], // delivery_date_time
                            $row[7], // area
                            $row[8]  // drivers_name
                        );

                        if ($stmt->execute()) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                        $stmt->close();
                    }
                }
            }

            if ($success_count > 0) {
                $showalert = true;
                $alert_message = "Successfully imported $success_count records. Failed: $error_count";
            } else {
                $showerror = true;
                $error_message = "No records were imported. Please check your Excel file format.";
            }
        } else {
            $invoice_date = $_POST['invoice_date'];
            $account_name = $_POST['account_name'];
            $address1 = $_POST['address1'];
            $invoice_number = $_POST['invoice_number'];
            $order_number = $_POST['order_number'];
            $external_order = $_POST['external_order'];
            $delivery_date_time = $_POST['delivery_date_time'];
            $area = $_POST['area'];
            $drivers_name = $_POST['drivers_name'];

            if (empty($invoice_date) || empty($account_name) || empty($invoice_number) || empty($delivery_date_time)) {
                throw new Exception("Please fill all mandatory fields.");
            }

            $sql = "INSERT INTO place_courier (invoice_date, account_name, address1, invoice_number, 
                    order_number, external_order, delivery_date_time, area, drivers_name) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $stmt->bind_param("sssssssss",
                $invoice_date,
                $account_name,
                $address1,
                $invoice_number,
                $order_number,
                $external_order,
                $delivery_date_time,
                $area,
                $drivers_name
            );

            if ($stmt->execute()) {
                $showalert = true;
                $alert_message = "Your courier order has been placed successfully.";
            } else {
                throw new Exception("Error saving record: " . $stmt->error);
            }

            $stmt->close();
        }
    }
} catch (Exception $e) {
    $showerror = true;
    $error_message = $e->getMessage();
    error_log("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Place Courier Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require 'partials/_nav.php'; ?>

    <div class="container mt-5">
        <?php if ($showalert): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?= htmlspecialchars($alert_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($showerror): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card p-4">
            <h2 class="mb-4">Place Courier Order</h2>

            <form action="place_courier.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Upload Excel File</label>
                    <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls">
                </div>
                <button type="submit" class="btn btn-primary">Upload Excel</button>
            </form>

            <hr class="my-4">

            <form action="place_courier.php" method="post">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="invoice_date" class="form-label">Invoice Date*</label>
                        <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                    </div>
                    <div class="col-md-3">
                        <label for="account_name" class="form-label">Account Name*</label>
                        <input type="text" class="form-control" id="account_name" name="account_name" required>
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_number" class="form-label">Invoice Number*</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
                    </div>
                    <div class="col-md-3">
                        <label for="delivery_date_time" class="form-label">Delivery Date Time*</label>
                        <input type="datetime-local" class="form-control" id="delivery_date_time" name="delivery_date_time" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address1" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address1" name="address1">
                </div>
                <div class="mb-3">
                    <label for="order_number" class="form-label">Order Number</label>
                    <input type="text" class="form-control" id="order_number" name="order_number">
                </div>
                <div class="mb-3">
                    <label for="external_order" class="form-label">External Order</label>
                    <input type="text" class="form-control" id="external_order" name="external_order">
                </div>
                <div class="mb-3">
                    <label for="area" class="form-label">Area</label>
                    <input type="text" class="form-control" id="area" name="area">
                </div>
                <div class="mb-3">
                    <label for="drivers_name" class="form-label">Driver's Name</label>
                    <input type="text" class="form-control" id="drivers_name" name="drivers_name">
                </div>
                <button type="submit" class="btn btn-primary">Submit Order</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
