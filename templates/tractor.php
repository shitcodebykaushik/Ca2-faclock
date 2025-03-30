<?php
// tractor.php
header("Access-Control-Allow-Origin: *");

// Define auction products (10 farmer products) with absolute image URLs
$auction_products = [
    [
        'name'        => 'Kaushik Tractor (Model X)',
        'price'       => '₹50,000',
        'current_bid' => '₹52,000',
        'image'       => 'http://localhost:8000/tractor.jpeg', 
        'auction_end' => '2025-04-01 18:00:00'
    ],
    [
        'name'        => 'Farm Harvester (Model Z)',
        'price'       => '₹75,000',
        'current_bid' => '₹80,000',
        'image'       => 'http://localhost:8000/baller.jpeg', 
        'auction_end' => '2025-04-05 20:00:00'
    ],
    [
        'name'        => 'Spray Machine Pro',
        'price'       => '₹30,000',
        'current_bid' => '₹32,500',
        'image'       => 'http://localhost:8000/spray.jpeg', 
        'auction_end' => '2025-04-03 15:30:00'
    ],
    [
        'name'        => 'Combine Harvester (Model A)',
        'price'       => '₹90,000',
        'current_bid' => '₹95,000',
        'image'       => 'http://localhost:8000/drone.jpeg', 
        'auction_end' => '2025-04-07 19:00:00'
    ],
    [
        'name'        => 'Rotavator (Model R)',
        'price'       => '₹40,000',
        'current_bid' => '₹42,000',
        'image'       => 'http://localhost:8000/irrigation.jpeg', 
        'auction_end' => '2025-04-02 17:00:00'
    ],
    [
        'name'        => 'Seeder Machine (Model S)',
        'price'       => '₹35,000',
        'current_bid' => '₹37,000',
        'image'       => 'http://localhost:8000/tractor.jpeg', 
        'auction_end' => '2025-04-04 14:00:00'
    ],
    [
        'name'        => 'Plough (Model P)',
        'price'       => '₹20,000',
        'current_bid' => '₹21,500',
        'image'       => 'http://localhost:8000/baller.jpeg', 
        'auction_end' => '2025-04-06 10:00:00'
    ],
    [
        'name'        => 'Irrigation Pump (Model I)',
        'price'       => '₹25,000',
        'current_bid' => '₹27,000',
        'image'       => 'http://localhost:8000/irrigation.jpeg', 
        'auction_end' => '2025-04-03 16:30:00'
    ],
    [
        'name'        => 'Fertilizer Spreader (Model F)',
        'price'       => '₹15,000',
        'current_bid' => '₹16,000',
        'image'       => 'http://localhost:8000/spray.jpeg', 
        'auction_end' => '2025-04-05 12:00:00'
    ],
    [
        'name'        => 'Baler Machine (Model B)',
        'price'       => '₹55,000',
        'current_bid' => '₹57,500',
        'image'       => 'http://localhost:8000/drone.jpeg', 
        'auction_end' => '2025-04-08 11:00:00'
    ],
];
?>
<div class="auction-container">
  <div class="row">
    <?php foreach ($auction_products as $product): ?>
      <div class="col-md-4 auction-card">
        <div class="card">
          <div class="card-body text-center">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 style="width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:15px;">
            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
            <p class="card-text">Starting Price: <?php echo htmlspecialchars($product['price']); ?></p>
            <p class="card-text">Current Bid: <strong><?php echo htmlspecialchars($product['current_bid']); ?></strong></p>
            <p class="auction-end">Auction Ends: <?php echo htmlspecialchars($product['auction_end']); ?></p>
            <!-- Place Bid button with product data attributes -->
            <a href="#" class="btn btn-primary btn-sm place-bid" 
               data-name="<?php echo htmlspecialchars($product['name']); ?>"
               data-price="<?php echo htmlspecialchars($product['price']); ?>"
               data-current-bid="<?php echo htmlspecialchars($product['current_bid']); ?>"
               data-auction-end="<?php echo htmlspecialchars($product['auction_end']); ?>"
               data-image="<?php echo htmlspecialchars($product['image']); ?>">
              Place Bid
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
