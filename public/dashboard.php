<?php
require_once '../src/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnLab - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-bug text-2xl mr-3"></i>
                    <span class="font-bold text-xl">VulnLab</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, <?php echo $_SESSION['username']; ?></span>
                    <a href="logout.php" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white min-h-screen shadow-lg">
            <div class="p-4">
                <h2 class="font-bold text-lg mb-4 text-gray-700">Vulnerability Modules</h2>
                <nav class="space-y-2">
                    <a href="search.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-search mr-3"></i>SQL Injection
                    </a>
                    <a href="comments.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-comments mr-3"></i>XSS Attacks
                    </a>
                    <a href="upload.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-upload mr-3"></i>File Upload
                    </a>
                    <a href="profile.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-user mr-3"></i>IDOR Demo
                    </a>
                    <?php if (isAdmin()): ?>
                    <a href="admin.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-cog mr-3"></i>Admin Panel
                    </a>
                    <?php endif; ?>
                    <a href="csrf.php" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 rounded-lg transition duration-300">
                        <i class="fas fa-exchange-alt mr-3"></i>CSRF Demo
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- SQL Injection Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-database text-red-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">SQL Injection</h3>
                    <p class="text-gray-600 mb-4">Test SQL injection vulnerabilities in search functionality</p>
                    <a href="search.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- XSS Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-code text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">XSS Attacks</h3>
                    <p class="text-gray-600 mb-4">Cross-site scripting vulnerabilities in comments</p>
                    <a href="comments.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- File Upload Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-upload text-green-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">File Upload</h3>
                    <p class="text-gray-600 mb-4">Unrestricted file upload vulnerabilities</p>
                    <a href="upload.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- IDOR Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-id-card text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">IDOR Demo</h3>
                    <p class="text-gray-600 mb-4">Insecure Direct Object Reference vulnerabilities</p>
                    <a href="profile.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Admin Panel Card -->
                <?php if (isAdmin()): ?>
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-cog text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Admin Panel</h3>
                    <p class="text-gray-600 mb-4">Admin-only functionality with vulnerabilities</p>
                    <a href="admin.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <?php endif; ?>

                <!-- CSRF Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-exchange-alt text-pink-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">CSRF Demo</h3>
                    <p class="text-gray-600 mb-4">Cross-Site Request Forgery vulnerabilities</p>
                    <a href="csrf.php" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        Explore <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Warning Banner -->
            <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                    <div>
                        <h3 class="font-bold text-red-800">Security Warning</h3>
                        <p class="text-red-700 text-sm">This application contains intentional vulnerabilities for educational purposes. 
                        Do not use in production or expose to untrusted networks.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
