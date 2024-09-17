<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-Commerce Website</title>
  <link rel="stylesheet" href="styles.css">
  <script src="scripts.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
   <!-- Navbar -->
   <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <a href="#">MyShop</a>
            </div>
            <ul class="navbar-menu">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="navbar-search-cart">
                <input type="text" class="search-bar" placeholder="Search products...">
                <button class="search-btn">Search</button>
                <button id="view-cart" class="cart-btn">
                    <i class="fa fa-shopping-cart"></i> Cart
                </button>
            </div>
        </div>
    </nav>

  <!-- Main content with product cards -->
  <div class="product-container">
    <?php
      // Connect to the database
      $conn = new mysqli('localhost', 'root', '', 'products_db');

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Fetch product data from the database
      $sql = "SELECT product_id, product_pic, product_name, price, description FROM products";
      $result = $conn->query($sql);

      // Display product cards
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<div class="product-card">';
              echo '<img src="' . $row['product_pic'] . '" alt="Product Image" class="product-image">';
              echo '<h3 class="product-name">' . $row['product_name'] . '</h3>';
              echo '<p class="product-price">$' . $row['price'] . '</p>';
              echo '<p class="product-description">' . $row['description'] . '</p>';
              echo '<button class="add-to-cart" data-id="' . $row['product_id'] . '" data-name="' . $row['product_name'] . '" data-price="' . $row['price'] . '">Add to Cart</button>';
              echo '</div>';
          }
      } else {
          echo 'No products found.';
      }

      // Close the database connection
      $conn->close();
    ?>
  </div>

  <!-- Cart Modal -->
  <div id="cart-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Your Cart</h2>
      <div id="cart-items"></div>
      <button id="clear-cart">Clear Cart</button>
    </div>
  </div>
  
</body>
</html>
