<?php
require_once '../src/config.php';
requireLogin();

$conn = getDBConnection();

// Handle new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $post_id = 1; // Default post
    
    // VULN: Stored XSS - storing raw user input
    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $_SESSION['user_id'], $comment);
    $stmt->execute();
}

// Get comments
$sql = "SELECT c.content, u.username, c.created_at 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Demo - VulnLab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-comments mr-2"></i>XSS Demo - Comments
                </h1>
                
                <form method="POST" class="mb-6">
                    <textarea name="comment" rows="4" 
                              placeholder="Add a comment"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Post Comment
                    </button>
                </form>

                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bug text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <strong>Vulnerability:</strong> Stored XSS - Comments are displayed without proper escaping.
                                Try: <code class="bg-red-100 px-1 rounded">&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php foreach ($comments as $comment): ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <span class="font-bold"><?php echo $comment['username']; ?></span>
                                    <span class="text-gray-500 text-sm ml-2"><?php echo $comment['created_at']; ?></span>
                                </div>
                            </div>
                            <div class="text-gray-700">
                                <?php echo $comment['content']; // VULN: Stored XSS - output without escaping ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
