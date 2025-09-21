<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "pharmacy_db4"; // This must match the name in your SQL file

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Check if a specific prescription ID is requested
if (isset($_GET['id'])) {
    $prescriptionId = $conn->real_escape_string($_GET['id']);
    
    // Query to get prescription, customer, and medicine details
    $sql = "SELECT
                p.Perception_ID AS prescription_id,
                p.Issue_Date AS issue_date,
                p.Status AS status,
                p.Notes AS notes,
                c.Patient_name AS patient_name,
                c.DoB AS dob,
                c.Address AS address,
                m.Medication_name AS medicine,
                mcp.Dosage AS dosage,
                mcp.Quantity AS quantity,
                mcp.Refills AS refills
            FROM
                perception p
            LEFT JOIN
                customer c ON p.Customer_ID = c.Customer_ID
            LEFT JOIN
                medicine_contains_perception mcp ON p.Perception_ID = mcp.Perception_ID
            LEFT JOIN
                medications m ON mcp.Medication_ID = m.Medication_ID
            WHERE
                p.Perception_ID = '{$prescriptionId}'";

    $result = $conn->query($sql);
    $details = [];

    if ($result->num_rows > 0) {
        $medicines = [];
        $firstRow = true;
        
        while($row = $result->fetch_assoc()) {
            if ($firstRow) {
                // Populate patient and prescription details from the first row
                $details = [
                    'prescriptionId' => $row['prescription_id'],
                    'patientName' => $row['patient_name'],
                    'dob' => $row['dob'],
                    'address' => $row['address'],
                    'issueDate' => $row['issue_date'],
                    'status' => $row['status'],
                    'notes' => $row['notes'],
                    // Add a placeholder image URL since the database doesn't provide one
                    'image' => 'https://via.placeholder.com/600x400.png?text=Scanned+Prescription+Image',
                    'medicines' => [], // Initialize the medicines array
                ];
                $firstRow = false;
            }
            // Add each medicine as a separate object to the medicines array
            if ($row['medicine']) {
                $medicines[] = [
                    'medicine' => $row['medicine'],
                    'dosage' => $row['dosage'],
                    'quantity' => $row['quantity'],
                    'refills' => $row['refills'],
                ];
            }
        }
        $details['medicines'] = $medicines;
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
                'priority' => $row['priority']
            ];
        }
    }
    echo json_encode($prescriptions);
}

$conn->close();
?>