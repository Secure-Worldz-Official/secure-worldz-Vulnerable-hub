<nav class="bg-blue-600 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="dashboard.php" class="flex items-center">
                    <i class="fas fa-bug text-2xl mr-3"></i>
                    <span class="font-bold text-xl">VulnLab</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="dashboard.php" class="hover:bg-blue-700 px-3 py-2 rounded transition duration-300">
                    <i class="fas fa-home mr-1"></i>Dashboard
                </a>
                <a href="logout.php" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded transition duration-300">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>
