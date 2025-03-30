<?php
// tractor.php
header("Access-Control-Allow-Origin: *");
// Define tractor product details
$products = [
    [
        'name'  => 'Kaushik (Model X)',
        'price' => '₹50,000'
    ],
    
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tractor Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS for styling -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
    <h2>Tractor Products</h2>
    <div class="row">
      <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
              <p class="card-text">Price: <?php echo htmlspecialchars($product['price']); ?></p>
              <a href="#" class="btn btn-success btn-sm">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
