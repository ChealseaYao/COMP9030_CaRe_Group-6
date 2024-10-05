<!--Created by Hsin Hui Chu-->
<?php
// Database connection
include '../inc/dbconn.inc.php'; // 確保資料庫連線檔案的路徑正確

// Get journal_id from the URL
$journal_id = isset($_GET['journal_id']) ? intval($_GET['journal_id']) : 0;

if ($journal_id == 0) {
    echo "Invalid Journal ID.";
    exit();
}

// Fetch file information from the database
$query = "SELECT file_path, original_name, file_type, file_size FROM journal WHERE journal_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $journal_id);
$stmt->execute();
$result = $stmt->get_result();
$file_info = $result->fetch_assoc();

if (!$file_info || empty($file_info['file_path']) || empty($file_info['original_name'])) {
    echo "No file found for the given journal.";
    exit();
}

// File path
$file_path = $file_info['file_path'];
$original_name = $file_info['original_name'];
$file_type = $file_info['file_type'];
$file_size = $file_info['file_size'];

// The 'uploads' folder is in the 'patient' directory under the htdocs path
$absolute_file_path = realpath("C:/xampp/htdocs/COMP9030_CaRe_Groups-6/patient/" . $file_path);

// Check if file exists on the server
if (!$absolute_file_path || !file_exists($absolute_file_path)) {
    echo "File not found at path: " . $absolute_file_path;
    exit();
}

// Set headers to force download
header("Content-Type: " . $file_type);
header("Content-Disposition: attachment; filename=\"" . basename($original_name) . "\"");
header("Content-Length: " . $file_size);

// Read the file and output it to the browser
readfile($absolute_file_path);

$stmt->close();
$conn->close();
exit();
?>
