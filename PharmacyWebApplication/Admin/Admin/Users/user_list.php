<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Pharmacy_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$userToEdit = null;
$action = $_GET['action'] ?? 'list';
$userId = $_GET['id'] ?? null;
$userRole = $_GET['role'] ?? null;

// --- PHP Functions for CRUD Operations ---

function findUser($conn, $id, $role) {
    $tableName = '';
    switch ($role) {
        case 'admin':
            $tableName = 'admin_users';
            break;
        case 'staff':
            $tableName = 'staff_users';
            break;
        case 'patient':
            $tableName = 'patient_users';
            break;
        default:
            return null;
    }

    $sql = "SELECT * FROM $tableName WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    return null;
}

function deleteUser($conn, $id, $role) {
    $tableName = '';
    switch ($role) {
        case 'admin':
            $tableName = 'admin_users';
            break;
        case 'staff':
            $tableName = 'staff_users';
            break;
        case 'patient':
            $tableName = 'patient_users';
            break;
        default:
            return "Invalid role.";
    }

    $sql = "DELETE FROM $tableName WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return "User deleted successfully!";
        } else {
            return "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        return "Database error: " . $conn->error;
    }
}

// --- PHP Logic based on Action ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Add User Logic ---
    if ($action == 'add') {
        $firstName = htmlspecialchars($_POST['first-name']);
        $lastName = htmlspecialchars($_POST['last-name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $tableName = '';
        switch ($role) {
            case 'admin':
                $tableName = 'admin_users';
                break;
            case 'staff':
                $tableName = 'staff_users';
                break;
            case 'patient':
                $tableName = 'patient_users';
                break;
        }

        if ($tableName) {
            $sql = "INSERT INTO $tableName (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
                if ($stmt->execute()) {
                    $message = "<div style='color: green; text-align: center; margin-bottom: 15px;'>User added successfully!</div>";
                } else {
                    $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Error adding user: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Database error: " . $conn->error . "</div>";
            }
        }
    } 
    // --- Edit User Logic ---
    else if ($action == 'edit') {
        $userId = htmlspecialchars($_POST['user-id']);
        $userRole = htmlspecialchars($_POST['user-role']);
        $firstName = htmlspecialchars($_POST['first-name']);
        $lastName = htmlspecialchars($_POST['last-name']);
        $email = htmlspecialchars($_POST['email']);

        $tableName = '';
        switch ($userRole) {
            case 'admin':
                $tableName = 'admin_users';
                break;
            case 'staff':
                $tableName = 'staff_users';
                break;
            case 'patient':
                $tableName = 'patient_users';
                break;
        }

        if ($tableName) {
            $sql = "UPDATE $tableName SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
                if ($stmt->execute()) {
                    $message = "<div style='color: green; text-align: center; margin-bottom: 15px;'>User updated successfully!</div>";
                    $userToEdit = findUser($conn, $userId, $userRole);
                } else {
                    $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Error updating user: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Database error: " . $conn->error . "</div>";
            }
        }
    }
} 
// --- Handle GET requests (viewing, editing, deleting) ---
else if ($action == 'edit' && $userId && $userRole) {
    $userToEdit = findUser($conn, $userId, $userRole);
    if (!$userToEdit) {
        $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>User not found.</div>";
        $action = 'list'; // Fallback to list view
    }
} else if ($action == 'delete' && $userId && $userRole) {
    $message = "<div style='color: black; text-align: center; margin-bottom: 15px;'>Deleting user...</div>";
    $resultMessage = deleteUser($conn, $userId, $userRole);
    $message = "<div style='color: " . (strpos($resultMessage, 'Error') !== false ? 'red' : 'green') . "; text-align: center; margin-bottom: 15px;'>$resultMessage</div>";
    $action = 'list'; // Redirect back to list view
}

// --- Dynamic SQL for Search and Filter ---
$searchQuery = $_GET['search-query'] ?? '';
$filterRole = $_GET['filter-role'] ?? '';
$searchTerm = "%$searchQuery%";

$sql = "";
$params = [];
$types = "";

if ($filterRole && $filterRole != 'all') {
    // Filter by a specific role
    $tableName = $filterRole . '_users';
    $sql = "SELECT id AS user_id, first_name, last_name, email, ? AS role FROM $tableName";
    $params[] = ucfirst($filterRole);
    $types .= "s";

    if ($searchQuery) {
        $sql .= " WHERE first_name LIKE ? OR last_name LIKE ?";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "ss";
    }
} else {
    // No specific role, or 'all' selected. Union all tables.
    $sql = "
        (SELECT id AS user_id, first_name, last_name, email, 'Admin' AS role FROM admin_users)
        UNION ALL
        (SELECT id AS user_id, first_name, last_name, email, 'Staff' AS role FROM staff_users)
        UNION ALL
        (SELECT id AS user_id, first_name, last_name, email, 'Patient' AS role FROM patient_users)
    ";

    if ($searchQuery) {
        $sql = "
            (SELECT id AS user_id, first_name, last_name, email, 'Admin' AS role FROM admin_users WHERE first_name LIKE ? OR last_name LIKE ?)
            UNION ALL
            (SELECT id AS user_id, first_name, last_name, email, 'Staff' AS role FROM staff_users WHERE first_name LIKE ? OR last_name LIKE ?)
            UNION ALL
            (SELECT id AS user_id, first_name, last_name, email, 'Patient' AS role FROM patient_users WHERE first_name LIKE ? OR last_name LIKE ?)
        ";
        $params = array_fill(0, 6, $searchTerm);
        $types = "ssssss";
    }
}

$stmt = null;
$result = null;

if ($sql) {
    if ($stmt = $conn->prepare($sql)) {
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>User Management - MediCare Pharmacy</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
            --primary-color: #34D399;
            --secondary-color: #f0fdf4;
            --background-color: #2b9e4d;
            --text-primary: #1a202c;
            --text-secondary: #4b5563;
            --accent-color: #06441d;
            --white-card-bg: rgba(255, 255, 255, 0.9);
        }
        body {
            font-family: "Inter", sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
        }
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 10;
            border-radius: 4px;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        .dropdown-content a {
            color: var(--text-primary);
            padding: 12px 16px;
            display: block;
            text-align: left;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: var(--secondary-color);
        }
        .dropdown-content.active {
            display: block;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
        }
        .content-container {
            background-color: var(--white-card-bg);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            width: 100%;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            flex: none;
            width: 100%;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--text-secondary);
        }
        .form-group input, .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
        }
        .form-group select {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>');
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1.5em 1.5em;
        }
        .action-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: var(--primary-color);
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .action-button:hover {
            background-color: #216124;
        }
        .new-footer {
            background-color: #f0f2f5;
            color: #4b5563;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .new-footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px 0;
        }
        .new-footer-left {
            text-align: left;
            margin-bottom: 20px;
        }
        .new-footer-left h2 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 10px;
        }
        .new-footer-left p {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .new-footer-center nav {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .new-footer-center nav a {
            text-decoration: none;
            color: #4b5563;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        .new-footer-center nav a:hover {
            color: #1a202c;
        }
        .new-footer-right .social-icons {
            display: flex;
            gap: 15px;
        }
        .new-footer-right .social-icons a {
            color: #9ca3af;
            font-size: 1.5rem;
            transition: color 0.3s;
        }
        .new-footer-right .social-icons a:hover {
            color: #4b5563;
        }
        .new-footer .copyright-text {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>
<body class="bg-[var(--background-color)] text-[var(--text-primary)]">
<header class="border-b border-gray-200">
    <div class="container mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-8">
            <a class="flex items-center gap-2 text-[var(--text-primary)]" href="#">
                <svg class="h-8 w-8 text-[var(--primary-color)]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.5 14h-3v-3.5a1.5 1.5 0 00-3 0V16h-3V8.5a.5.5 0 011 0V11h2.5a.5.5 0 01.5.5v2.5h2V16zm-1.5-6h-4V8h4v2z"></path>
                </svg>
                <h1 class="text-2xl font-bold">MediCare</h1>
            </a>
            <nav class="hidden items-center gap-6 lg:flex">
                <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="../../Home/index.html">Home</a>
            </nav>
        </div> 
    </div>
</header>
<main class="main-content">
    <div class="content-container">
        <?php if ($action == 'list'): ?>
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-4xl font-extrabold text-[var(--accent-color)]">Users</h1>
                <a href="../Settings/add_user1.php" class="bg-[var(--primary-color)] text-white px-6 py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 transition duration-300 ease-in-out flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    <span class="truncate">Add User</span>
                </a>
            </div>
            <?php if (isset($message)) echo $message; ?>
            
            <!-- NEW SEARCH AND FILTER FORM -->
            <form action="" method="GET">
                <input type="hidden" name="action" value="list">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <label class="relative block">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="material-symbols-outlined text-gray-400">search</span>
                            </span>
                            <input class="block w-full px-10 py-3 border border-gray-300 rounded-md bg-white text-[var(--text-primary)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-200 ease-in-out" name="search-query" placeholder="Search users by name..." type="text" value="<?php echo htmlspecialchars($searchQuery); ?>"/>
                        </label>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative w-full">
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-md bg-white text-[var(--text-primary)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-200 ease-in-out appearance-none" name="filter-role" onchange="this.form.submit()">
                                <option value="all" <?php if ($filterRole == 'all') echo 'selected'; ?>>All Roles</option>
                                <option value="admin" <?php if ($filterRole == 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="staff" <?php if ($filterRole == 'staff') echo 'selected'; ?>>Staff</option>
                                <option value="patient" <?php if ($filterRole == 'patient') echo 'selected'; ?>>Patient</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <span class="material-symbols-outlined">expand_more</span>
                            </div>
                        </div>
                        <div class="relative w-full">
                            <select class="block w-full px-4 py-3 border border-gray-300 rounded-md bg-white text-[var(--text-primary)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] transition duration-200 ease-in-out appearance-none" name="filter-status" onchange="this.form.submit()">
                                <option disabled="" selected="">Status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <span class="material-symbols-outlined">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status = "Active";
                            $status_color = "bg-green-100 text-green-800";
                    ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--text-primary)]"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($row['role']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo $status_color; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a class="text-[var(--primary-color)] hover:underline mr-4" href="../Settings/edit_user1.php?id=<?php echo htmlspecialchars($row['user_id']); ?>&role=<?php echo htmlspecialchars(strtolower($row['role'])); ?>">Edit</a>
                            <a class="text-red-600 hover:underline" href="?action=delete&id=<?php echo htmlspecialchars($row['user_id']); ?>&role=<?php echo htmlspecialchars(strtolower($row['role'])); ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='px-6 py-4 text-center'>No users found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($action == 'add'): ?>
            <div class="form-container">
                <h2 class="text-4xl font-extrabold text-[var(--accent-color)] text-center">Add New User</h2>
                <p class="text-center">Fill out the form below to create a new user account.</p>
                <?php if (isset($message)) echo $message; ?>
                <form action="../Settings/add_user1.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Doe" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="john.doe@example.com" required>
                    </div>
                
                    <div class="form-group full-width">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <button type="submit" class="action-button">Add User</button>
                </form>
            </div>
        <?php elseif ($action == 'edit' && $userToEdit): ?>
            <div class="form-container">
                <h2 class="text-4xl font-extrabold text-[var(--accent-color)] text-center">Edit User</h2>
                <p class="text-center">Update the user's information below.</p>
                <?php if (isset($message)) echo $message; ?>
                <form action="../Settings/edit_user1.php" method="POST">
                    <input type="hidden" name="user-id" value="<?php echo htmlspecialchars($userToEdit['id']); ?>">
                    <input type="hidden" name="user-role" value="<?php echo htmlspecialchars($userRole); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($userToEdit['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($userToEdit['last_name']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userToEdit['email']); ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                    <button type="submit" class="action-button">Update User</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>
<footer class="new-footer">
    <div class="new-footer-content">
        <div class="new-footer-left">
            <h2>MediCare Pharmacy</h2>
            <p>123 Health St, Wellness City, 12345</p>
            <p>Phone: (123) 456-7890</p>
            <p>Email: contact@medicare.com</p>
        </div>
        <div class="new-footer-center">
            <nav>
                <a href="#">Contact Us</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </nav>
        </div>
        <div class="new-footer-right">
            <div class="social-icons">
                <a href="#" aria-label="Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" aria-label="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="copyright-text">
        © 2025 MediCare Pharmacy. All rights reserved.
    </div>
</footer>
<script>
    function toggleDropdown(event) {
        event.preventDefault();
        const dropdownContent = event.target.nextElementSibling;
        document.querySelectorAll('.dropdown-content.active').forEach(openDropdown => {
            if (openDropdown !== dropdownContent) {
                openDropdown.classList.remove('active');
            }
        });
        dropdownContent.classList.toggle('active');
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropdown a')) {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(dropdown => {
                if (dropdown.classList.contains('active')) {
                    dropdown.classList.remove('active');
                }
            });
        }
    }

    document.querySelectorAll('.dropdown a').forEach(link => {
        link.addEventListener('click', toggleDropdown);
    });
</script>
</body>
</html>
