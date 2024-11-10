<?php
$showerror = false;
$showalert = false;
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'partials/_dbconnect.php';
    
    // Collect form data
    $invoice_date = $_POST['invoice_date'];
    $account_name = $_POST['account_name'];
    $address1 = $_POST['address1'];
    $invoice_number = $_POST['invoice_number'];
    $order_number = $_POST['order_number'];
    $external_order = $_POST['external_order'];
    $delivery_date_time = $_POST['delivery_date_time'];
    $area = $_POST['area'];
    $drivers_name = $_POST['drivers_name'];

    // Validate mandatory fields
    if (empty($invoice_date) || empty($account_name) || empty($invoice_number) || empty($delivery_date_time)) {
        $showerror = true;
        $error_message = "Please fill all mandatory fields.";
    } else {
        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO place_courier (invoice_date, account_name, address1, invoice_number, order_number, external_order, delivery_date_time, area, drivers_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $invoice_date, $account_name, $address1, $invoice_number, $order_number, $external_order, $delivery_date_time, $area, $drivers_name);
        
        if ($stmt->execute()) {
            $showalert = true;
        } else {
            $showerror = true;
            $error_message = "Unable to place your order. Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Place courier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
<?php require 'partials/_nav.php' ?>
<style>
body {
  background-image: url('/login/assets/ecom.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;
}
</style>

<br><br>
<?php 
if($showalert) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success</strong> Your courier order has been placed successfully.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
if($showerror) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error:</strong> ' . $error_message . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
   
<br>
<div class="col-md-5 offset-md-6">
    <h1 style="color:black; text-align:center">Fill the Form to Deliver your Parcel!</h1>
    <form action="/login/place_courier.php" method="post">
        <div class="mb-3">
            <label for="invoice_date" class="form-label"><h5 style="color:black">Invoice Date*</h5></label>
            <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
        </div>
        <div class="mb-3">
            <label for="account_name" class="form-label"><h5 style="color:black">Account Name*</h5></label>
            <input type="text" class="form-control" id="account_name" name="account_name" required>
        </div>
        <div class="mb-3">
            <label for="address1" class="form-label"><h5 style="color:black">Address1</h5></label>
            <input type="text" class="form-control" id="address1" name="address1">
        </div>
        <div class="mb-3">
            <label for="invoice_number" class="form-label"><h5 style="color:black">Invoice Number*</h5></label>
            <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
        </div>
        <div class="mb-3">
            <label for="order_number" class="form-label"><h5 style="color:black">Order Number</h5></label>
            <input type="text" class="form-control" id="order_number" name="order_number">
        </div>
        <div class="mb-3">
            <label for="external_order" class="form-label"><h5 style="color:black">External Order</h5></label>
            <input type="text" class="form-control" id="external_order" name="external_order">
        </div>
        <div class="mb-3">
            <label for="delivery_date_time" class="form-label"><h5 style="color:black">Delivery Date Time*</h5></label>
            <input type="datetime-local" class="form-control" id="delivery_date_time" name="delivery_date_time" required>
        </div>
        <div class="mb-3">
            <label for="area" class="form-label"><h5 style="color:black">Area</h5></label>
            <input type="text" class="form-control" id="area" name="area">
        </div>
        <div class="mb-3">
            <label for="drivers_name" class="form-label"><h5 style="color:black">Driver's Name</h5></label>
            <input type="text" class="form-control" id="drivers_name" name="drivers_name">
        </div>
        <br>
        <button type="submit" class="btn btn-danger col-md-12">Submit</button>
    </form>
</div> 
<br><br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
