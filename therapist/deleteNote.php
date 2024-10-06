<?php
session_start();

// Check if user is logged in and is a therapist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Get the therapist's user_id from the session
$user_id = $_SESSION['user_id']; 

// Include the database connection file
include '../inc/dbconn.inc.php'; 

// Fetch the correct therapist_id using the user's user_id
$therapist_query = "SELECT therapist_id FROM therapist WHERE user_id = ?";
$stmt = $conn->prepare($therapist_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$therapist_result = $stmt->get_result();

if ($therapist_result->num_rows > 0) {
    $therapist_data = $therapist_result->fetch_assoc();
    $therapist_id = $therapist_data['therapist_id'];
} else {
    echo json_encode(['success' => false, 'error' => "Therapist not found."]);
    exit();
}
$stmt->close();

// Get the patient ID and note ID from the POST request
$patient_id = isset($_POST['patient_id']) ? intval($_POST['patient_id']) : 0;
$note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;

// Validate patient ID and note ID
if ($patient_id <= 0 || $note_id <= 0) {
    echo json_encode(['success' => false, 'error' => "Invalid patient ID or note ID."]);
    exit();
}

// Delete the note
$delete_note_query = "DELETE FROM note WHERE note_id = ? AND patient_id = ? AND therapist_id = ?";
$stmt = $conn->prepare($delete_note_query);
$stmt->bind_param("iii", $note_id, $patient_id, $therapist_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete note']);
}

$stmt->close();
$conn->close();
?>
