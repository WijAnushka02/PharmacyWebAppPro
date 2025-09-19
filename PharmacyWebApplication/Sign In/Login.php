<?php
session_start();

// Database credentials
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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Define the tables and corresponding roles
    $roles = [
        'admin_users' => 'admin',
        'staff_users' => 'staff',
        'patient_users' => 'patient'
    ];

    $found_user = false;

    // Iterate through roles/tables to find the user
    foreach ($roles as $table_name => $role) {
        // Prepare the SQL statement to prevent SQL injection
        $sql = "SELECT id, email, password FROM $table_name WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Verify the password with the stored hash
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $role;

                    // Redirect to the appropriate dashboard
                    header("Location: " . strtolower($role) . "_dashboard.php");
                    exit;
                }
            }
        }
    }
    
    // If the loop finishes without a match, display an error message
    $message = "<div style='color: red; text-align: center; margin-bottom: 15px;'>Invalid email or password.</div>";
}
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

        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .login-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9em;
        }

        .form-options a {
            color: #2e7d32;
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-options a:hover {
            color: #1a4d1d;
        }

        .sign-in-button {
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

        .sign-in-button:hover {
            background-color: #216124;
        }

        .or-divider {
            text-align: center;
            position: relative;
            margin: 30px 0;
            color: #777;
            font-size: 0.9em;
        }

        .or-divider::before, .or-divider::after {
            content: '';
            position: absolute;
            width: 40%;
            height: 1px;
            background-color: #ddd;
            top: 50%;
        }

        .or-divider::before {
            left: 0;
        }

        .or-divider::after {
            right: 0;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .social-button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            font-size: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background-color 0.3s;
        }

        .google-button {
            background-color: #fff;
            color: #333;
        }

        .google-button i {
            color: #db4437;
        }

        .facebook-button {
            background-color: #1877f2;
            color: white;
            border: 1px solid #1877f2;
        }

        .facebook-button i {
            color: white;
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9em;
        }

        .signup-link a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: #1a4d1d;
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
                    <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="../Home/index.html">Home</a>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Medications</a>
                        <div class="dropdown-content">
                            <a href="#">Search</a>
                            <a href="#">Categories</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Prescriptions</a>
                        <div class="dropdown-content">
                            <a href="#">Upload</a>
                            <a href="#">History</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Inventory</a>
                        <div class="dropdown-content">
                            <a href="#">Stock</a>
                            <a href="#">Expiry</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Orders</a>
                        <div class="dropdown-content">
                            <a href="#">Pending</a>
                            <a href="#">History</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Admin</a>
                        <div class="dropdown-content">
                            <a href="#">Users</a>
                            <a href="#">Settings</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--primary-color)]" href="#">Support</a>
                        <div class="dropdown-content">
                            <a href="#">FAQ</a>
                            <a href="#">Contact</a>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg aria-hidden="true" class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" fill-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input class="block w-full rounded-md border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:border-[var(--primary-color)] focus:bg-white focus:text-gray-900 focus:outline-none focus:ring-1 focus:ring-[var(--primary-color)] sm:text-sm" placeholder="Search" type="search"/>
                </div>
                <div class="hidden items-center gap-2 md:flex">
                    <a class="rounded-md bg-[var(--secondary-color)] px-4 py-2 text-sm font-medium text-[var(--primary-color)] hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 transition-colors duration-200" href="#">Offers</a>
                    <a class="rounded-md bg-[var(--primary-color)] px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 transition-colors duration-200" href="#">Sign In</a>
                    <a class="rounded-md border border-[var(--primary-color)] px-4 py-2 text-sm font-medium text-[var(--primary-color)] hover:bg-[var(--secondary-color)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 transition-colors duration-200" href="../Sign Up/signup_main.php">Sign Up</a>
                </div>
                <button class="relative rounded-full bg-gray-100 p-2 text-gray-600 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-[var(--primary-color)] text-xs text-white">0</span>
                </button>
                <button class="p-2 text-gray-500 hover:text-gray-700 lg:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h16M4 12h16m-7 6h7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="login-container">
            <div class="login-box">
                <h2>Sign In</h2>
                <?php if (isset($message)) echo $message; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="someone@gamil.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="At least 8 characters" required>
                    </div>
                    <div class="form-options">
                        <label>
                            <input type="checkbox" name="remember-me"> Remember Me
                        </label>
                        <a href="#">Forgot Password?</a>
                    </div>
                    <a href="../Home/index.html">
                    <button type="submit" class="sign-in-button">Sign In</button>
                    </a>
                    <div class="or-divider">Or</div>
                </form>
                <div class="social-login">
                    <a href="https://accounts.google.com/o/oauth2/auth?client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=openid%20email%20profile" class="social-button google-button">
                        <i class="fab fa-google"></i> Sign in with Google
                    </a>
                    <a href="https://www.facebook.com/v15.0/dialog/oauth?client_id=YOUR_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=email" class="social-button facebook-button">
                        <i class="fab fa-facebook"></i> Sign in with Facebook
                    </a>
                </div>
                <div class="signup-link">
                    Don't you have an account? <a href="../Sign Up/signup_main.php">Sign Up</a>
                </div>
            </div>
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
    </script>
</body>
</html>