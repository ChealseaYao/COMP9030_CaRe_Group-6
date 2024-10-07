<?php
session_start();
require_once '../inc/dbconn.inc.php';  


$affirmation = $_POST['affirmation'] ?? null;
$patient_id = $_POST['patient_id'] ?? null;

if ($affirmation && $patient_id) {
   
    $affirmation_query = $conn->prepare("SELECT affirmation_id FROM affirmation WHERE affirmation = ?");
    $affirmation_query->bind_param("s", $affirmation);
    $affirmation_query->execute();
    $affirmation_result = $affirmation_query->get_result();
    $affirmation_data = $affirmation_result->fetch_assoc();

    if ($affirmation_data) {
        $affirmation_id = $affirmation_data['affirmation_id'];

     
        error_log("Affirmation ID: " . $affirmation_id);

     
        $insert_query = $conn->prepare("INSERT INTO patient_affirmation (patient_id, affirmation_id, selection_date) 
                                        VALUES (?, ?, CURDATE()) 
                                        ON DUPLICATE KEY UPDATE affirmation_id = VALUES(affirmation_id)");
        $insert_query->bind_param("ii", $patient_id, $affirmation_id);

        if ($insert_query->execute()) {
            echo 'Success: Affirmation saved for patient.';
        } else {
            error_log('Error: Failed to save affirmation.');
            echo 'Error: Failed to save affirmation.';
        }

        $insert_query->close();
    } else {
        error_log('Error: Affirmation not found.');
        echo 'Error: Affirmation not found.';
    }
} else {
    error_log('Error: Missing affirmation or patient_id.');
    echo 'Error: Missing affirmation or patient_id.';
}

$conn->close();
?>
