<?php
header('Content-Type: application/json');

// Database credentials from your local setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy_db4"; // Updated to your current database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the search query from the URL parameter 'q'
$query = strtolower($_GET['q'] ?? '');

if (empty($query)) {
    echo json_encode(["error" => "No search query provided."]);
    $conn->close();
    exit;
}

// Prepare SQL query to search for a medication
// Removed JOIN from stock table and now retrieves price from medications table
$sql = "SELECT Medicine_name, Description, price
        FROM medications
        WHERE LOWER(Medicine_name) LIKE ?";
        
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(["error" => "SQL statement preparation failed: " . $conn->error]));
}

$searchTerm = "%" . $query . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$medication = null;
if ($result->num_rows > 0) {
    // Fetch the first result
    $row = $result->fetch_assoc();
    $medication = [
        "name" => $row['Medicine_name'],
        "description" => $row['Description'],
        "price" => $row['price']
    ];
} else {
    // No results found
    $medication = ["error" => "No results found for '" . $_GET['q'] . "'"];
}

$stmt->close();
$conn->close();

echo json_encode($medication);
?>