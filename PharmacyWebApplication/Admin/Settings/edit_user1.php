<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Pharmacy_db4";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$user = null;
$userId = $_GET['id'] ?? null;
$userRole = $_GET['role'] ?? null;

// Function to find the user in the appropriate table
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

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = htmlspecialchars($_POST['user-id']);
    $userRole = htmlspecialchars($_POST['user-role']);
    $firstName = htmlspecialchars($_POST['first-name']);
    $lastName = htmlspecialchars($_POST['last-name']);
    $email = htmlspecialchars($_POST['email']);

    // Check if the user is in the correct table
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
                // Re-fetch the user data to show the updated information
                $user = findUser($conn, $userId, $userRole);
            } else {
                $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Error updating user: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Database error: " . $conn->error . "</div>";
        }
    }
} else {
    // Initial page load: fetch user data
    if ($userId && $userRole) {
        $user = findUser($conn, $userId, $userRole);
        if (!$user) {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>User not found.</div>";
        }
    } else {
        $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>User ID and role are required to edit.</div>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit User - MediCare Pharmacy</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
            --primary-color: #34D399;
            --secondary-color: #f0fdf4;
            --background-color: #2b9e4d;
            --text-primary: #1a202c;
            --text-secondary: #4b5563;
            --accent-color: #06441d;
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

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }

        .form-container h2 {
            color: var(--accent-color);
            text-align: center;
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        .form-container p {
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 25px;
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

        .edit-user-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: #34D399; /* Updated to primary-color */
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .edit-user-button:hover {
            background-color: #216124; /* Updated hover color */
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
        </div>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h2>Edit User</h2>
            <p>Update the user's information below.</p>
            <?php if (isset($message)) echo $message; ?>
            <?php if ($user): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="user-id" value="<?php echo htmlspecialchars($user['id']); ?>">
                <input type="hidden" name="user-role" value="<?php echo htmlspecialchars($userRole); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
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
                  
                <button type="submit" class="edit-user-button">Update User</button>
            </form>
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
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-content.active').forEach(openDropdown => {
                if (openDropdown !== dropdownContent) {
                    openDropdown.classList.remove('active');
                }
            });

            // Toggle the 'active' class on the clicked dropdown
            dropdownContent.classList.toggle('active');
        }

        // Close dropdowns if the user clicks outside
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