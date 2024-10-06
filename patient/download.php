<?php
// Database connection
include '../inc/dbconn.inc.php'; // Make sure the path to the database connection file is correct

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

// Debug output
echo "<pre>";
echo "File information from database:\n";
print_r($file_info);
echo "</pre>";

if (!$file_info || empty($file_info['file_path']) || empty($file_info['original_name'])) {
    echo "No file found for the given journal.";
    exit();
}

// File path from the database
$file_path = $file_info['file_path'];
$original_name = $file_info['original_name'];
$file_type = $file_info['file_type'];
$file_size = $file_info['file_size'];

// Debug output for file path
echo "File path from database: " . $file_path . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
// Construct the absolute file path using the server's document root
$project_root = $_SERVER['DOCUMENT_ROOT'] . '/COMP9030_CaRe_Groups-6/patient/';
$absolute_file_path = $project_root . $file_path;
// Debug output for absolute file path
echo "Absolute file path: " . $absolute_file_path . "<br>";

// Check if file exists on the server using absolute path
if (!$absolute_file_path || !file_exists($absolute_file_path)) {
    echo "File not found at path: " . $absolute_file_path;
    exit();
}

// Set headers to force download
header("Content-Type: " . $file_type);
header("Content-Disposition: attachment; filename=\"" . basename($original_name) . "\"");
header("Content-Length: " . filesize($absolute_file_path));

// Read the file and output it to the browser
readfile($absolute_file_path);

$stmt->close();
$conn->close();
exit();
?>
