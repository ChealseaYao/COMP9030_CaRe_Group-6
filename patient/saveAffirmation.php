<?php
session_start();
require_once '../inc/dbconn.inc.php';  // 包含数据库连接

// 获取传入的数据
$affirmation = $_POST['affirmation'] ?? null;
$patient_id = $_POST['patient_id'] ?? null;

if ($affirmation && $patient_id) {
    // 查找肯定句的 ID
    $affirmation_query = $conn->prepare("SELECT affirmation_id FROM affirmation WHERE affirmation = ?");
    $affirmation_query->bind_param("s", $affirmation);
    $affirmation_query->execute();
    $affirmation_result = $affirmation_query->get_result();
    $affirmation_data = $affirmation_result->fetch_assoc();

    if ($affirmation_data) {
        $affirmation_id = $affirmation_data['affirmation_id'];

        // 输出调试信息，确认获取到了正确的 affirmation_id
        error_log("Affirmation ID: " . $affirmation_id);

        // 插入或更新肯定句选择
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
