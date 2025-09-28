<?php
require_once '../src/config.php';
requireLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $newEmail = $_POST['email'];
    $conn = getDBConnection();
    
    // VULN: CSRF - No token validation
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newEmail, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $message = "Email updated successfully to: " . htmlspecialchars($newEmail);
    } else {
        $message = "Error updating email";
    }
}

// Get current user info
$conn = getDBConnection();
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Demo - VulnLab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold mb-6 text-gray-800">
                    <i class="fas fa-exchange-alt mr-2"></i>CSRF Demo
                </h1>

                <div class="bg-pink-50 border-l-4 border-pink-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-random text-pink-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-pink-700">
                                <strong>Vulnerability:</strong> CSRF - This form lacks CSRF tokens and can be exploited 
                                by malicious sites to change your email without consent.
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-bold mb-2">Current Profile Information</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            New Email Address
                        </label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($user['email']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-save mr-2"></i>Update Email
                    </button>
                </form>

                <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                    <h4 class="font-bold mb-2">CSRF Attack Simulation:</h4>
                    <p class="text-sm text-yellow-700 mb-2">
                        An attacker could create a hidden form that submits to this page to change your email.
                    </p>
                    <textarea class="w-full h-32 text-xs font-mono bg-gray-100 p-2 rounded" readonly>
<!-- Malicious page example -->
<form action="http://your-vulnlab-site/csrf.php" method="POST">
    <input type="hidden" name="email" value="hacker@evil.com">
</form>
<script>document.forms[0].submit();</script>
                    </textarea>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
