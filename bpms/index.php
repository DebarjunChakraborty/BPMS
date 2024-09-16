<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-Commerce Website</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">E-Shop</div>
    <ul class="nav-links">
      <li><a href="#">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
    <div class="search-bar">
      <input type="text" placeholder="Search products...">
      <button type="submit">Search</button>
    </div>
  </nav>

  <!-- Main content with product cards -->
  <div class="product-container">
    <?php
      // Step 2: Connect to the database
      $conn = new mysqli('localhost', 'root', '', 'products_db');

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Step 3: Fetch product data from the database
      $sql = "SELECT product_pic, product_name, price, description FROM products";
      $result = $conn->query($sql);

      // Step 4: Loop through the products and display them
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<div class="product-card">';
              echo '<img src="' . $row['product_pic'] . '" alt="Product Image" class="product-image">';
              echo '<h3 class="product-name">' . $row['product_name'] . '</h3>';
              echo '<p class="product-price">$' . $row['price'] . '</p>';
              echo '<p class="product-description">' . $row['description'] . '</p>';
              echo '</div>';
          }
      } else {
          echo 'No products found.';
      }

      // Step 5: Close the database connection
      $conn->close();
    ?>
  </div>
</body>
</html>
