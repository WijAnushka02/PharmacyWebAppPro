<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sign Up - MediCare Pharmacy</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        /* General Body and Fonts */
        :root {
            --primary-color: #34D399;
            --secondary-color: #f0fdf4;
            --background-color: #2b9e4d;
            --text-primary: #1a202c;
            --text-secondary: #4b5563;
            --accent-color: #06441d;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            background-image: url('blurred-pharmacy-background.jpg'); /* Replace with your background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        /* Header and Navigation Bar Styling (reused from previous design) */
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
            top: calc(100% + 8px); /* Position below the link */
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

        /* Main Content for Category Selection */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
            min-height: calc(100vh - 180px); /* Adjust based on header/footer height */
        }

        .category-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .category-container h2 {
            color: #2e7d32;
            margin-bottom: 10px;
            font-size: 2.2em;
        }

        .category-container p {
            color: #555;
            margin-bottom: 30px;
            font-size: 1.1em;
        }

        .category-buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .category-button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 8px;
            background-color: #3f51b5; /* A primary blue color */
            color: white;
            font-size: 1.3em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .category-button:hover {
            background-color: #303f9f;
            transform: translateY(-2px);
        }

        /* Footer Styling (reused from previous design) */
        .new-footer {
            background-color: #f0f2f5; /* Light background as in Image 2 */
            color: #4b5563; /* Text color as in Image 2 */
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb; /* Border top as in Image 2 */
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
            color: #9ca3af; /* Social icon color as in Image 2 */
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
                    <a class="rounded-md border border-[var(--primary-color)] px-4 py-2 text-sm font-medium text-[var(--primary-color)] hover:bg-[var(--secondary-color)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 transition-colors duration-200" href="#">Sign Up</a>
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
        <div class="category-container">
            <h2>Sign Up</h2>
            <p>Choose the relevant category,</p>
            <div class="category-buttons">
                <button class="category-button" onclick="redirectToSignUp('admin')">Admin</button>
                <button class="category-button" onclick="redirectToSignUp('staff')">Staff</button>
                <button class="category-button" onclick="redirectToSignUp('customer')">Customer</button>
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
        function redirectToSignUp(userType) {
            // In a real application, you would redirect to a specific signup form
            // based on the userType. For example:
            // window.location.href = `signup_${userType}.php`;
            alert(`Redirecting to ${userType} signup form... (This is a placeholder)`);
            // Example:
            // if (userType === 'admin') {
            //     window.location.href = 'signup_admin.php';
            // } else if (userType === 'staff') {
            //     window.location.href = 'signup_staff.php';
            // } else if (userType === 'customer') {
            //     window.location.href = 'signup_customer.php';
            // }
        }

        function redirectToSignUp(userType) {
            // Redirect to a specific signup form based on the userType.
            if (userType === 'admin') {
                window.location.href = 'signup_admin.php';
            } else if (userType === 'staff') {
                window.location.href = 'signup_staff.php';
            } else if (userType === 'customer') {
                window.location.href = 'signup_patient.php';
            } else {
            // Optional: Handle unknown user types
            alert('Invalid user type selected.');
        }
    }


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