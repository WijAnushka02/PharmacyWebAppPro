<?php

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Pharmacy_db";

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $message = "";

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $staffId = $_POST['staff_id'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $address = $_POST['address'];

        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($staffId) || empty($phone) || empty($email) || empty($password) || empty($confirmPassword) || empty($address)) {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Please fill in all fields.</div>";
        } elseif ($password !== $confirmPassword) {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Passwords do not match.</div>";
        } else {
            // Check if the table 'staff_users' exists.
            $tableCheckQuery = "SHOW TABLES LIKE 'staff_users'";
            $tableResult = $conn->query($tableCheckQuery);

            if ($tableResult->num_rows == 0) {
                // If the table doesn't exist, create it.
                $createTableSql = "CREATE TABLE staff_users (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(50) NOT NULL,
                    last_name VARCHAR(50) NOT NULL,
                    staff_id VARCHAR(20) NOT NULL,
                    phone VARCHAR(20) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    address TEXT NOT NULL,
                    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";

                if ($conn->query($createTableSql) === TRUE) {
                    $message .= "<div style='color: green; text-align: center; margin-bottom: 5px;'>Table 'staff_users' created successfully.</div>";
                } else {
                    $message .= "<div style='color: red; text-align: center; margin-bottom: 5px;'>Error creating table: " . $conn->error . "</div>";
                }
            }
            
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert data into the 'staff_users' table.
            // Use a prepared statement to prevent SQL injection
            $insertSql = "INSERT INTO staff_users (first_name, last_name, staff_id, phone, email, password, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("sssssss", $firstName, $lastName, $staffId, $phone, $email, $hashedPassword, $address);

            if ($stmt->execute()) {
                $message .= "<div style='color: green; text-align: center; margin-bottom: 15px;'>Staff account created successfully!</div>";
            } else {
                $message .= "<div style='color: red; text-align: center; margin-bottom: 15px;'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        }
    }
    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sign In - MediCare Pharmacy</title>
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
        
        /* Updated Navigation CSS for dropdown */
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


        .header {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo {
            display: flex;
            align-items: center;
            font-size: 1.2em;
            font-weight: bold;
        }

        .header .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .header .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .header .search-bar input {
            border: none;
            padding: 8px 30px 8px 15px;
            border-radius: 20px;
            outline: none;
            background-color: white;
        }

        .header .search-bar .fa-search {
            position: absolute;
            right: 15px;
            color: #555;
        }

        .top-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .top-nav li {
            margin-left: 20px;
        }

        .top-nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }

        .top-nav a:hover {
            color: #0c6d11ff;
        }

        .top-nav a i {
            margin-right: 5px;
        }

        .main-nav {
            background-color: #216124;
            padding: 10px 20px;
        }

        .main-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        .main-nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
            padding: 10px 15px;
        }

        .main-nav a:hover {
            background-color: #1a4d1d;
        }
      

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
            min-height: calc(100vh - 180px);
        }

        .signup-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }

        .signup-container h2 {
            color: #216124;
            text-align: center;
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        .signup-container p {
            color: #555;
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
            color: #555;
        }

        .form-group input, .form-group textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .signup-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background-color: #2e7d32;
            color: white;
            font-size: 1.1em;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .signup-button:hover {
            background-color: #216124;
        }

        /* Footer Styling */
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
<body>

    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="MediCare Pharmacy Logo">
            <span>AXR PHARMACY</span>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <i class="fas fa-search"></i>
        </div>
        <nav class="top-nav">
            <ul>
                <li><a href="#"> <i class="fas fa-tags"></i> Offers</a></li>
                <li><a href="#"> <i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li><a href="#"> <i class="fas fa-user"></i> Sign In</a></li>
                <li><a href="#">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="../Home/index.html">Home</a></li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Medications <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Search</a>
                    <a href="#">Categories</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Prescriptions <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Upload</a>
                    <a href="#">History</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Inventory <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Stock</a>
                    <a href="#">Expiry</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Orders <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Pending</a>
                    <a href="#">History</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Admin <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">Users</a>
                    <a href="#">Settings</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" onclick="toggleDropdown(event)">Support <i class="fas fa-caret-down"></i></a>
                <div class="dropdown-content">
                    <a href="#">FAQ</a>
                    <a href="#">Contact</a>
                </div>
            </li>
        </ul>
    </nav>


    <main class="main-content">
        <div class="signup-container">
            <h2>Staff Page - Sign Up</h2>
            <p>Fill out all the details below,</p>
            <?php if (isset($message)) echo $message; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter First Name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="staff_id">Staff ID</label>
                        <input type="text" id="staff_id" name="staff_id" placeholder="Enter Your ID Number">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter Your Phone Number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="someone@gamil.com">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="At least 8 characters">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Enter Your Address Here"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="confirm_password">Password (Re-Enter)</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="At least 8 characters">
                    </div>
                </div>
                <a href="../Home/index.html">
                    <button type="submit" class="signup-button">Sign Up</button>
                </a>
            </form>
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
            const dropdownContent = event.currentTarget.nextElementSibling;
            
            // Close all other active dropdowns
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
            if (!event.target.matches('.dropdown a, .dropdown a *')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(dropdown => {
                    if (dropdown.classList.contains('active')) {
                        dropdown.classList.remove('active');
                    }
                });
            }
        }
    </script>
</body>
</html>