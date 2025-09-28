<?php
require_once '../src/config.php';
requireLogin();

$conn = getDBConnection();
$user_id = $_GET['id'] ?? $_SESSION['user_id'];

// VULN: IDOR - No authorization check
$sql = "SELECT id, username, email, role, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDOR Demo - VulnLab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold mb-6 text-gray-800">
                    <i class="fas fa-user mr-2"></i>User Profile (IDOR Demo)
                </h1>

                <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-purple-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-purple-700">
                                <strong>Vulnerability:</strong> IDOR - Try changing the <code>id</code> parameter in URL to access other users' profiles.
                                Current ID: <?php echo $user_id; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold"><?php echo htmlspecialchars($user['username']); ?></h2>
                            <p class="text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">User ID</div>
                            <div class="font-medium"><?php echo $user['id']; ?></div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Role</div>
                            <div class="font-medium"><?php echo htmlspecialchars($user['role']); ?></div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Member Since</div>
                            <div class="font-medium"><?php echo $user['created_at']; ?></div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-500">Status</div>
                            <div class="font-medium text-green-600">Active</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-bold mb-2">Quick Access Other Profiles:</h3>
                    <div class="flex space-x-2">
                        <a href="profile.php?id=1" class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200">User 1 (Alice)</a>
                        <a href="profile.php?id=2" class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200">User 2 (Admin)</a>
                        <a href="profile.php?id=3" class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm hover:bg-blue-200">User 3 (Bob)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
