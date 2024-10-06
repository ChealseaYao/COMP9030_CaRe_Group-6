<?php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php");
    exit();
}

// Database connection
include '../inc/dbconn.inc.php'; 


$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['journal_id']) && isset($data['highlight'])) {
    $journal_id = intval($data['journal_id']);
    $highlight = intval($data['highlight']);

    
    if (in_array($highlight, [0, 1])) {
        $sql = "UPDATE journal SET highlight = ? WHERE journal_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $highlight, $journal_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid highlight']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}

$conn->close();
?>
