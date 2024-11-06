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
  
  // Handle Excel file upload
  if(isset($_FILES['excel_file'])) {
      require 'vendor/autoload.php'; // Make sure to install PhpSpreadsheet via composer
      
      $file = $_FILES['excel_file']['tmp_name'];
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
      $worksheet = $spreadsheet->getActiveSheet();
      $rows = $worksheet->toArray();
      
      // Skip header row
      array_shift($rows);
      
      $stmt = $conn->prepare("INSERT INTO place_courier (invoice_date, account_name, address1, invoice_number, order_number, external_order, delivery_date_time, area, drivers_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      
      foreach($rows as $row) {
          $stmt->bind_param("sssssssss", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
          $stmt->execute();
      }
      
      $showalert = true;
  } else {
      // Regular form submission
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
          $showerror = true;
          $error_message = "Please fill all mandatory fields.";
      } else {
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
}

// Fetch existing orders
$sql = "SELECT * FROM place_courier ORDER BY delivery_date_time DESC";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body {
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
          min-height: 100vh;
      }
      .form-container {
          background: rgba(255, 255, 255, 0.9);
          padding: 20px;
          border-radius: 10px;
          margin-top: 20px;
      }
      .orders-table {
          background: white;
          border-radius: 10px;
          margin-top: 20px;
          padding: 20px;
      }
  </style>
</head>
<body>

<?php require 'partials/_nav.php' ?>

<div class="container">
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

  <div class="form-container">
      <h1 class="text-center mb-4">Orders</h1>
      
      <!-- Excel Upload Form -->
      <form action="" method="post" enctype="multipart/form-data" class="mb-4">
          <div class="row">
              <div class="col-md-8">
                  <input type="file" class="form-control" name="excel_file" accept=".xlsx,.xls">
              </div>
              <div class="col-md-4">
                  <button type="submit" class="btn btn-success w-100">Upload Excel</button>
              </div>
          </div>
      </form>

      <!-- Regular Form -->
      <form action="" method="post">
          <div class="row">
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="invoice_date" class="form-label">Invoice Date*</label>
                      <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="account_name" class="form-label">Account Name*</label>
                      <input type="text" class="form-control" id="account_name" name="account_name" required>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="address1" class="form-label">Address1</label>
                      <input type="text" class="form-control" id="address1" name="address1">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="invoice_number" class="form-label">Invoice Number*</label>
                      <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="order_number" class="form-label">Order Number</label>
                      <input type="text" class="form-control" id="order_number" name="order_number">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="external_order" class="form-label">External Order</label>
                      <input type="text" class="form-control" id="external_order" name="external_order">
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="delivery_date_time" class="form-label">Delivery Date Time*</label>
                      <input type="datetime-local" class="form-control" id="delivery_date_time" name="delivery_date_time" required>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="area" class="form-label">Area</label>
                      <input type="text" class="form-control" id="area" name="area">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="mb-3">
                      <label for="drivers_name" class="form-label">Driver's Name</label>
                      <input type="text" class="form-control" id="drivers_name" name="drivers_name">
                  </div>
              </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">Submit Order</button>
      </form>
  </div>

  <!-- Orders Table -->
  <div class="orders-table">
      <h2 class="text-center mb-4">Current Orders</h2>
      <div class="table-responsive">
          <table class="table table-striped">
              <thead>
                  <tr>
                      <th>Invoice Date</th>
                      <th>Account Name</th>
                      <th>Invoice Number</th>
                      <th>Delivery Date</th>
                      <th>Area</th>
                      <th>Driver</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php while($row = $result->fetch_assoc()): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($row['invoice_date']); ?></td>
                      <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                      <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                      <td><?php echo htmlspecialchars($row['delivery_date_time']); ?></td>
                      <td><?php echo htmlspecialchars($row['area']); ?></td>
                      <td><?php echo htmlspecialchars($row['drivers_name']); ?></td>
                      <td>
                          <a href="edit_order.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                          <a href="delete_order.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                      </td>
                  </tr>
                  <?php endwhile; ?>
              </tbody>
          </table>
      </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>