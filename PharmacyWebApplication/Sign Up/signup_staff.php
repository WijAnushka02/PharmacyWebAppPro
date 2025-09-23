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
            $message = "<div class='text-red-500 text-center mb-4'>Please fill in all fields.</div>";
        } elseif ($password !== $confirmPassword) {
            $message = "<div class='text-red-500 text-center mb-4'>Passwords do not match.</div>";
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
                    $message .= "<div class='text-green-500 text-center mb-2'>Table 'staff_users' created successfully.</div>";
                } else {
                    $message .= "<div class='text-red-500 text-center mb-2'>Error creating table: " . $conn->error . "</div>";
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
                $message .= "<div class='text-green-500 text-center mb-4'>Staff account created successfully!</div>";
            } else {
                $message .= "<div class='text-red-500 text-center mb-4'>Error: " . $stmt->error . "</div>";
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
    <title>Staff Sign Up - MediCare Pharmacy</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
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
    </style>
</head>
<body class="bg-[var(--background-color)] text-[var(--text-primary)]">

<div class="relative flex size-full min-h-screen flex-col overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
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
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Home</a>
                       
                        
                    </nav>
                </div>
                
                </div>
            </div>
        </header>

        <main class="container mx-auto flex max-w-7xl flex-1 flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="my-8 w-full max-w-md rounded-lg bg-white p-8 shadow-md sm:max-w-xl">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-[var(--text-primary)]">Staff Sign Up</h2>
                    <p class="mt-2 text-base text-[var(--text-secondary)]">Fill out all the details below.</p>
                </div>
                <?php if (isset($message)) echo $message; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mt-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="staff_id" class="block text-sm font-medium text-gray-700">Staff ID</label>
                            <input type="text" id="staff_id" name="staff_id" placeholder="Enter Your ID Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter Your Phone Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" placeholder="someone@gmail.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" placeholder="At least 8 characters" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter Your Address Here" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring-[var(--primary-color)] sm:text-sm"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-md bg-[var(--primary-color)] px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 transition-colors duration-200">Sign Up</button>
                </form>
            </div>
        </main>

        <footer class="bg-gray-50">
            <div class="container mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-baseline justify-between">
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">MediCare Pharmacy</h2>
                        <p class="text-sm text-[var(--text-secondary)]">123 Health St, Wellness City, 12345</p>
                        <p class="text-sm text-[var(--text-secondary)]">Phone: (123) 456-7890</p>
                        <p class="text-sm text-[var(--text-secondary)]">Email: contact@medicare.com</p>
                    </div>
                    <nav aria-label="Footer" class="-mx-5 -my-2 flex flex-wrap justify-center">
                        <div class="px-5 py-2"><a class="text-base text-gray-500 hover:text-gray-900" href="#">Contact Us</a></div>
                        <div class="px-5 py-2"><a class="text-base text-gray-500 hover:text-gray-900" href="#">Privacy Policy</a></div>
                        <div class="px-5 py-2"><a class="text-base text-gray-500 hover:text-gray-900" href="#">Terms of Service</a></div>
                    </nav>
                    <div class="mt-8 flex justify-center space-x-6 md:mt-0">
                        <a class="text-gray-400 hover:text-gray-500" href="#">
                            <span class="sr-only">Facebook</span>
                            <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a class="text-gray-400 hover:text-gray-500" href="#">
                            <span class="sr-only">Instagram</span>
                            <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path clip-rule="evenodd" d="M12.315 2c-4.062 0-4.575.018-6.174.089C4.403 2.158 3.117 2.5 2.18 3.437c-.937.938-1.278 2.224-1.347 3.965C.768 9.098.75 9.61.75 12.315c0 2.705.018 3.217.089 4.816.07 1.741.41 3.027 1.348 3.965.937.937 2.223 1.278 3.965 1.347 1.6.07 2.112.088 4.816.088s3.217-.018 4.816-.088c1.741-.07 3.027-.41 3.965-1.348.937-.937 1.278-2.223 1.347-3.965.07-1.6.088-2.112.088-4.816s-.018-3.217-.088-4.816c-.07-1.741-.41-3.027-1.348-3.965C20.902 2.5 19.616 2.158 17.875 2.09C16.275 2.018 15.762 2 12.315 2zm0 1.802c4.004 0 4.475.016 6.06.086 1.463.066 2.305.358 2.87.824.642.545.96 1.27.995 2.182.07 1.585.086 2.057.086 5.225s-.016 3.64-.086 5.225c-.035.912-.353 1.637-.995 2.182-.565.466-1.407.758-2.87.824-1.585.07-2.056.086-6.06.086s-4.475-.016-6.06-.086c-1.463-.066-2.305-.358-2.87-.824-.642-.545-.96-1.27-.995-2.182-.07-1.585-.086-2.057-.086-5.225s.016-3.64.086-5.225c.035-.912.353-1.637.995-2.182.565-.466 1.407-.758 2.87-.824 1.585-.07 2.056-.086 6.06-.086zM12.315 7.1a5.215 5.215 0 100 10.43 5.215 5.215 0 000-10.43zm0 8.628a3.413 3.413 0 110-6.826 3.413 3.413 0 010 6.826zm5.225-9.332a1.23 1.23 0 100 2.46 1.23 1.23 0 000-2.46z" fill-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a class="text-gray-400 hover:text-gray-500" href="#">
                            <span class="sr-only">Twitter</span>
                            <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                    <p class="text-base text-gray-400">© 2025 MediCare Pharmacy. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</div>

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