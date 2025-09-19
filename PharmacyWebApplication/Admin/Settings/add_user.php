<?php

// Database connection details
$servername = "localhost";
$username = "your_db_username"; // Replace with your database username
$password = "your_db_password"; // Replace with your database password
$dbname = "pharmacy_db4";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = $_POST['full-name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash the password for security before storing it in the database
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL query to insert data into the users table
    $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";

    // Prepare and bind the statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $full_name, $email, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New user added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();

?>