<?php
include 'dbconn.inc.php';

// Fetch journals for a specific patient
$patientName = $_GET['patient'];  // Get patient name from the request

$sql = "SELECT id, content, date FROM journals WHERE patient_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $patientName);
$stmt->execute();
$result = $stmt->get_result();

// Return journals as a JSON response
$journals = array();
while ($row = $result->fetch_assoc()) {
    $journals[] = $row;
}

echo json_encode($journals);
?>