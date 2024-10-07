<?php
include '../inc/dbconn.inc.php';

if (isset($_GET['therapist_id'])) {
    $therapist_id = intval($_GET['therapist_id']);

    $sql = "SELECT p.patient_id, 
                   GROUP_CONCAT(DISTINCT c.case_type ORDER BY c.case_type SEPARATOR ', ') AS case_types, 
                   SUM(c.duration_minutes) AS total_minutes
            FROM consultation c
            JOIN patient p ON c.patient_id = p.patient_id
            JOIN `user` u ON p.user_id = u.user_id
            WHERE c.therapist_id = ?
            GROUP BY p.patient_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $therapist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $consultations = [];
    while ($row = $result->fetch_assoc()) {
        $consultations[] = [
            'patient_id' => $row['patient_id'],   // 返回唯一的 patient_id
            'case_types' => $row['case_types'],
            'total_minutes' => $row['total_minutes']
        ];
    }
    
    echo json_encode($consultations);
    exit();
}
?>
