<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy_db4";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query from the URL parameter
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Base SQL query
$sql = "SELECT 
            o.Order_ID, 
            o.Customer_ID,
            o.Total_price, 
            ocm.Medicine_ID,
            ocm.Quantity
        FROM 
            `order` o
        JOIN 
            `order_contains_medicine` ocm ON o.Order_ID = ocm.Order_ID";

// Add a WHERE clause if a search query is provided
if (!empty($search_query)) {
    // Use prepared statement to prevent SQL injection
    $sql .= " WHERE o.Order_ID LIKE ? OR o.Customer_ID LIKE ? OR ocm.Medicine_ID LIKE ?";
    $search_param = '%' . $search_query . '%';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search query, execute the base query
    $sql .= " ORDER BY o.Order_ID DESC";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    // Loop through all results and echo each row
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--text-primary)]">#' . htmlspecialchars($row["Order_ID"]) . '</td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">' . htmlspecialchars($row["Customer_ID"]) . '</td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">$' . htmlspecialchars(number_format($row["Total_price"], 2)) . '</td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">' . htmlspecialchars($row["Medicine_ID"]) . '</td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">' . htmlspecialchars($row["Quantity"]) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)] text-center">No matching orders found.</td></tr>';
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>