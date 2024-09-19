<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "products_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$address = $_POST['address'];

// Prepare and execute SQL statement
$sql = "INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $password, $address);

if ($stmt->execute()) {
    echo "Sign Up successful. You can now <a href='login.html'>login</a>.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
