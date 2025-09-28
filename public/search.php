<?php
require_once '../src/config.php';
requireLogin();

$results = [];
$search = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = $_POST['search'] ?? '';
    
    if (!empty($search)) {
        $conn = getDBConnection();
        
        // VULN: SQL Injection - direct concatenation
        $sql = "SELECT p.title, p.body, u.username, p.created_at 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.title LIKE '%$search%' OR p.body LIKE '%$search%'";
        
        $result = $conn->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Demo - VulnLab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-search mr-2"></i>SQL Injection Demo
                </h1>
                
                <form method="POST" class="mb-6">
                    <div class="flex space-x-4">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Search posts..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </form>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Vulnerability:</strong> This search is vulnerable to SQL Injection. 
                                Try: <code class="bg-yellow-100 px-1 rounded">' OR '1'='1</code>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if (!empty($results)): ?>
                    <div class="space-y-4">
                        <?php foreach ($results as $post): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold text-lg mb-2"><?php echo $post['title']; ?></h3>
                                <p class="text-gray-600 mb-2"><?php echo $post['body']; ?></p>
                                <div class="text-sm text-gray-500">
                                    By <?php echo $post['username']; ?> on <?php echo $post['created_at']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <p class="text-gray-600">No results found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
