<?php

header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy_db4";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Handle GET requests to fetch medicine data
        $medicines = [];
        $sql = "SELECT m.Medicine_ID, m.Medicine_name, m.description, s.Quantity_in_stock, s.Exp_Date
                FROM medications m
                JOIN stock s ON m.Medicine_ID = s.Medicine_ID";
        
        $result = $conn->query($sql);
        
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $medicines[] = [
                        'id' => $row['Medicine_ID'],
                        'name' => $row['Medicine_name'],
                        'dosage' => $row['description'],
                        'quantity' => $row['Quantity_in_stock'],
                        'expiry' => $row['Exp_Date']
                    ];
                }
            }
            echo json_encode($medicines);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error fetching data: ' . $conn->error]);
        }
        break;

    case 'PUT':
        // Handle PUT requests to update medicine data
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id'], $data['name'], $data['dosage'], $data['quantity'], $data['expiry'])) {
            // Update `medications` table
            $sql_medicine = "UPDATE medications SET Medicine_name = ?, description = ? WHERE Medicine_ID = ?";
            $stmt_medicine = $conn->prepare($sql_medicine);
            $stmt_medicine->bind_param("ssi", $data['name'], $data['dosage'], $data['id']);
            $stmt_medicine->execute();
            $stmt_medicine->close();
            
            // Update `stock` table
            $sql_stock = "UPDATE stock SET Quantity_in_stock = ?, Exp_Date = ? WHERE Medicine_ID = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $stmt_stock->bind_param("isi", $data['quantity'], $data['expiry'], $data['id']);
            $stmt_stock->execute();
            $stmt_stock->close();

            echo json_encode(['message' => 'Medicine updated successfully!']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input data']);
        }
        break;

    case 'DELETE':
        // Handle DELETE requests
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        if (isset($id)) {
            // Start transaction
            $conn->begin_transaction();

            try {
                // First, delete from the `stock` table due to foreign key constraints
                $sql_stock = "DELETE FROM stock WHERE Medicine_ID = ?";
                $stmt_stock = $conn->prepare($sql_stock);
                $stmt_stock->bind_param("i", $id);
                $stmt_stock->execute();
                $stmt_stock->close();

                // Then, delete from the `medications` table
                $sql_medicine = "DELETE FROM medications WHERE Medicine_ID = ?";
                $stmt_medicine = $conn->prepare($sql_medicine);
                $stmt_medicine->bind_param("i", $id);
                $stmt_medicine->execute();
                $stmt_medicine->close();

                // Commit transaction
                $conn->commit();
                echo json_encode(['message' => 'Medicine deleted successfully!']);

            } catch (mysqli_sql_exception $e) {
                $conn->rollback();
                http_response_code(500);
                echo json_encode(['error' => 'Error deleting medicine: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Medicine ID not provided']);
        }
        break;
}

$conn->close();

?>