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
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($phone) || empty($address) || empty($email) || empty($password) || empty($confirmPassword)) {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Please fill in all fields.</div>";
        } elseif ($password !== $confirmPassword) {
            $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Passwords do not match.</div>";
        } else {
            // Check if the table 'patient_users' exists.
            $tableCheckQuery = "SHOW TABLES LIKE 'patient_users'";
            $tableResult = $conn->query($tableCheckQuery);

            if ($tableResult->num_rows == 0) {
                // If the table doesn't exist, create it.
                $createTableSql = "CREATE TABLE patient_users (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(50) NOT NULL,
                    last_name VARCHAR(50) NOT NULL,
                    phone VARCHAR(20) NOT NULL,
                    address TEXT NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";

                if ($conn->query($createTableSql) === TRUE) {
                    $message .= "<div style='color: green; text-align: center; margin-bottom: 5px;'>Table 'patient_users' created successfully.</div>";
                } else {
                    $message .= "<div style='color: red; text-align: center; margin-bottom: 5px;'>Error creating table: " . $conn->error . "</div>";
                }
            }
            
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert data into the 'patient_users' table.
            // Use a prepared statement to prevent SQL injection
            $insertSql = "INSERT INTO patient_users (first_name, last_name, phone, address, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("ssssss", $firstName, $lastName, $phone, $address, $email, $hashedPassword);

            if ($stmt->execute()) {
                $message .= "<div style='color: green; text-align: center; margin-bottom: 15px;'>Customer account created successfully!</div>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Sign Up - AXR Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            background-image: url('blurred-pharmacy-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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
            color: #d4e8d5;
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

        .footer {
            background-color: #2e7d32;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .footer-row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 20px;
        }

        .footer-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-item i {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .contact-info h3 {
            margin-bottom: 10px;
        }

        .contact-info p {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
        }

        .copyright {
            font-size: 0.8em;
            color: #b0c9b1;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="AXR Pharmacy Logo">
            <span>AXR PHARMACY</span>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <i class="fas fa-search"></i>
        </div>
        <nav class="top-nav">
            <ul>
                <li><a href="#"><i class="fas fa-tags"></i> Offers</a></li>
                <li><a href="#"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li><a href="#"><i class="fas fa-user"></i> Sign In</a></li>
                <li><a href="#">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Medications <i class="fas fa-caret-down"></i></a></li>
            <li><a href="#">Prescriptions <i class="fas fa-caret-down"></i></a></li>
            <li><a href="#">Inventory <i class="fas fa-caret-down"></i></a></li>
            <li><a href="#">Orders <i class="fas fa-caret-down"></i></a></li>
            <li><a href="#">Admin <i class="fas fa-caret-down"></i></a></li>
            <li><a href="#">Support <i class="fas fa-caret-down"></i></a></li>
        </ul>
    </nav>

    <main class="main-content">
        <div class="signup-container">
            <h2>Patient Page - Sign Up</h2>
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
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter Your Phone Number">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" placeholder="Enter Your Address Here"></textarea>
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
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
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

    <footer class="footer">
        <div class="footer-row">
            <div class="footer-item">
                <i class="fas fa-truck"></i>
                <p>FREE DELIVERY</p>
            </div>
            <div class="footer-item">
                <i class="fas fa-stethoscope"></i>
                <p>NEW MEDICAL ENCOUNTER</p>
            </div>
            <div class="footer-item">
                <i class="fas fa-shield-alt"></i>
                <p>MEDICAL GUARANTEED</p>
            </div>
        </div>
        <div class="footer-row contact-info">
            <h3>Contact Info</h3>
            <p><i class="fas fa-map-marker-alt"></i> Address</p>
            <p><i class="fas fa-phone"></i> +94 75 256 5423</p>
            <p><i class="fas fa-envelope"></i> someone@gmail.com</p>
        </div>
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-[var(--text-secondary)]">
            © 2025 MediCare. All rights reserved.
        </div>
    </footer>

</body>
</html>