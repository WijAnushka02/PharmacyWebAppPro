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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $user_id = $_POST['user_id'];
    $full_name = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $permissions = $_POST['permissions'] ?? [];

    // Begin a transaction for data integrity
    $conn->begin_transaction();

    try {
        // 1. Update the user's details in the `users` table
        $sql_update_user = "UPDATE users SET full_name=?, email=?, phone=?, role=?, status=? WHERE user_id=?";
        $stmt_user = $conn->prepare($sql_update_user);
        $stmt_user->bind_param("sssssi", $full_name, $email, $phone, $role, $status, $user_id);
        $stmt_user->execute();
        $stmt_user->close();

        // 2. Clear existing permissions for the user
        $sql_delete_permissions = "DELETE FROM user_permissions WHERE user_id=?";
        $stmt_delete = $conn->prepare($sql_delete_permissions);
        $stmt_delete->bind_param("i", $user_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // 3. Insert new permissions for the user
        if (!empty($permissions)) {
            $sql_insert_permission = "INSERT INTO user_permissions (user_id, permission_id) VALUES (?, (SELECT permission_id FROM permissions WHERE permission_name = ?))";
            $stmt_permission = $conn->prepare($sql_insert_permission);

            foreach ($permissions as $permission_name) {
                $stmt_permission->bind_param("is", $user_id, $permission_name);
                $stmt_permission->execute();
            }
            $stmt_permission->close();
        }

        // Commit the transaction
        $conn->commit();
        echo "User details and permissions updated successfully.";

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();

?>