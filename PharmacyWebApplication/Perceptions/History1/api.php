<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "pharmacy_db4"; // Database name from your SQL file

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if a specific prescription ID is requested
if (isset($_GET['id'])) {
    $prescriptionId = $conn->real_escape_string($_GET['id']);

    // Check if the ID is a valid number
    if (!is_numeric($prescriptionId)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid prescription ID.']);
        exit();
    }

    // Query to get prescription and customer details
    $sql = "SELECT
                p.Perception_ID AS prescription_id,
                p.Issue_Date AS issue_date,
                p.Status AS status,
                p.Notes AS notes,
                c.Patient_name AS patient_name,
                c.DoB AS dob,
                c.Address AS address
            FROM
                perception p
            LEFT JOIN
                customer c ON p.Customer_ID = c.Customer_ID
            WHERE
                p.Perception_ID = '{$prescriptionId}'";

    $result = $conn->query($sql);
    $details = [];

    if ($result->num_rows > 0) {
        $details = $result->fetch_assoc();
    }

    if (!empty($details)) {
        echo json_encode($details);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Prescription not found.']);
    }

} else {
    // This block is for the main list page, fetching all prescriptions
    $sql = "SELECT p.Perception_ID AS prescription_id, c.Patient_name AS patient_name, p.Issue_Date AS issue_date, p.Status AS priority
            FROM perception p
            JOIN customer c ON p.Customer_ID = c.Customer_ID
            ORDER BY p.Issue_Date DESC";

    $result = $conn->query($sql);
    $prescriptions = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $prescriptions[] = [
                'prescriptionId' => $row['prescription_id'],
                'patientName' => $row['patient_name'],
                'issueDate' => $row['issue_date'],
                'priority' => $row['priority'],
            ];
        }
        echo json_encode($prescriptions);
    } else {
        echo json_encode([]);
    }
}

$conn->close();
?>