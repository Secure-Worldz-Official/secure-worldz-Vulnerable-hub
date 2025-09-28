<?php
require_once '../src/config.php';
requireLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    
    // VULN: Unrestricted file upload
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = basename($_FILES['file']['name']);
    $targetPath = $uploadDir . $fileName;
    
    // VULN: No proper file type validation
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        $conn = getDBConnection();
        $sql = "INSERT INTO files (filename, path, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $fileName, $targetPath, $_SESSION['user_id']);
        $stmt->execute();
        
        $message = "File uploaded successfully!";
    } else {
        $message = "File upload failed!";
    }
}

// Get uploaded files
$conn = getDBConnection();
$sql = "SELECT f.filename, f.path, f.uploaded_at, u.username 
        FROM files f 
        JOIN users u ON f.user_id = u.id 
        ORDER BY f.uploaded_at DESC";
$result = $conn->query($sql);
$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Demo - VulnLab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include '../includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-upload mr-2"></i>File Upload Demo
                </h1>
                
                <?php if ($message): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" name="file" id="file" class="hidden" required>
                        <label for="file" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Click to select a file or drag and drop</p>
                            <p class="text-sm text-gray-500 mt-1">Any file type accepted (vulnerable)</p>
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 mt-4">
                        <i class="fas fa-upload mr-2"></i>Upload File
                    </button>
                </form>

                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-orange-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-700">
                                <strong>Vulnerability:</strong> Unrestricted file upload - no validation of file types.
                                You can upload PHP shells or other malicious files.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-bold mb-4">Uploaded Files</h2>
                <div class="space-y-3">
                    <?php foreach ($files as $file): ?>
                        <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium"><?php echo htmlspecialchars($file['filename']); ?></div>
                                    <div class="text-sm text-gray-500">
                                        Uploaded by <?php echo $file['username']; ?> on <?php echo $file['uploaded_at']; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo $file['path']; ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
