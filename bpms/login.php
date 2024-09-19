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
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute SQL statement
$sql = "SELECT password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    // Redirect to index.php upon successful login
    header("Location: index.php");
    exit();
} else {
    // Show error message if login fails
    echo "Login failed. Please check your email and password.";
}

$stmt->close();
$conn->close();
