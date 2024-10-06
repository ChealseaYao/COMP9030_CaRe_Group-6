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

// Get the patient ID and note content from the POST request
$patient_id = isset($_POST['patient_id']) ? intval($_POST['patient_id']) : 0;
$note_content = trim($_POST['note_content']);

// Validate patient ID and note content
if ($patient_id <= 0 || empty($note_content)) {
    echo json_encode(['success' => false, 'error' => "Invalid patient ID or note content."]);
    exit();
}

// Add the new note
$note_date = date('Y-m-d');
$insert_note_query = "INSERT INTO note (note_date, note_content, patient_id, therapist_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_note_query);
$stmt->bind_param("ssii", $note_date, $note_content, $patient_id, $therapist_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'note_id' => $stmt->insert_id]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add note']);
}

$stmt->close();
$conn->close();
?>
