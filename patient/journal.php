<!--Created by Hsin Hui Chu-->
<?php
// Start session to access patient information
session_start();

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    echo "Please log in to view your journal.";
    exit();
}
// Database connection
include '../inc/dbconn.inc.php'; // Ensure the path is correct

// Get patient ID and patient name from session
$patient_id = $_SESSION['patient_id'];
$patient_name = $_SESSION['patient_name'];


// Get journal_id and patient_id from the URL
$journal_id = isset($_GET['journal_id']) ? intval($_GET['journal_id']) : 0;
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($journal_id == 0 || $patient_id == 0) {
    echo "Invalid Journal ID or Patient ID";
    exit();
}

// 如果有接收到刪除請求，則執行刪除操作
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_journal'])) {
    $delete_query = "DELETE FROM journal WHERE journal_id = ? AND patient_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $journal_id, $patient_id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Journal entry deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete journal entry"]);
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Fetch the journal content based on journal_id and patient_id
$query = "SELECT journal_content, journal_date, sleep_time, wake_time, food, 
exercise, file_path, original_name, file_type, file_size FROM journal 
WHERE journal_id = ? AND patient_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $journal_id, $patient_id);
$stmt->execute();
$journal_result = $stmt->get_result();
$journal_info = $journal_result->fetch_assoc();

if (!$journal_info) {
    echo "Journal not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Hsin-Hui Chu">
    <title>Patient Journal</title>
    <script src="../scripts/journal.js"></script>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/modal.css">
    <link rel="stylesheet" href="../style/therapistJournal.css">
    <link rel="stylesheet" href="../style/patientJournal.css">
    
    <script>
    function confirmDelete() {
            // 呼叫 AJAX 發送刪除請求
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "patientJournal.php?journal_id=<?php echo $journal_id; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = "viewHistoryRecord.html"; // 刪除成功後返回歷史紀錄頁面
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send("delete_journal=true");
        }
    </script>

</head>

<body>
    <header class="navbar">
        <a href="patientDashboard.html"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    </header>
    <div class="therapistContainer">
        <div class="leftbox">
            <!-- should be selected patient journal list page -->
            <a href="viewHistoryRecord.html">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="container">
            <div class="entry-header">
                <?php echo htmlspecialchars($patient_name); ?>
            </div>
            <div class="entry-date">Date: <?php echo date("d/m/Y", strtotime($journal_info['journal_date'])); ?></div>
            <div class="entry-content">
                <?php echo htmlspecialchars($journal_info['journal_content']); ?>
            </div>
            <div class="details">
                <label>Sleeping Time:</label>
                <div><?php echo isset($journal_info['sleep_time']) ? date("H:i:s", strtotime($journal_info['sleep_time'])) : 'N/A'; ?></div>
                <label>Wake Up Time:</label>
                <div><?php echo isset($journal_info['wake_time']) ? date("H:i:s", strtotime($journal_info['wake_time'])) : 'N/A'; ?></div>
                <label>Food:</label>
                <div><?php echo isset($journal_info['food'])? htmlspecialchars($journal_info['food']) : 'N/A'; ?></div>
                <label>Exercise:</label>
                <div><?php echo isset($journal_info['exercise'])? htmlspecialchars($journal_info['exercise']) : 'N/A'; ?></div>
            </div>
            <div class="download">
                <label>Attached File:</label>
                <div><?php echo !empty($journal_info['original_name']) ? htmlspecialchars($journal_info['original_name']) : 'No file attached'; ?></div>
                <?php if (!empty($journal_info['file_path']) && !empty($journal_info['original_name'])): ?>
                    <!--download button links to download.php-->
                    <a href="download.php?journal_id=<?php echo $journal_id; ?>"><button>Download</button></a>
                <?php else: ?>
                    <!-- If no file is attached, show disabled button -->
                    <button disabled>No file to download</button>
                <?php endif; ?>
            </div>
            <button class="delete-button" onclick="showPopup()">Delete</button>

            <!--Popup page-->
            <div class="popup-overlay" id="popupOverlay"></div>
            <div class="popup" id="popup">
                <p>Do you want to delete the journal?</p>
                <div>
                    <button id="note-cancelBtn" onclick="hidePopup()">Cancel</button>
                    <button id="note-confirmBtn" onclick="confirmDelete()">Confirm</button>
                </div>

            </div>
        </div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>
