<!--Created by Hsin Hui Chu-->
<?php

// Database connection
include '../inc/dbconn.inc.php'; // Ensure the path is correct

// 接收 AJAX 請求並進行 highlight 狀態變更
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 解析傳入的 JSON 資料
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['journal_id']) && isset($data['highlight'])) {
        $journal_id = intval($data['journal_id']);
        $highlight = intval($data['highlight']);

        // 更新指定 journal_id 的 highlight 狀態
    if (in_array($highlight, [0, 1])){
        $sql = "UPDATE journal SET highlight = ? WHERE journal_id = ?";
        $stmt = $conn->prepare($update_query);
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
    exit();
    
    }
}

// Get journal_id and patient_id from the URL
$journal_id = isset($_GET['journal_id']) ? intval($_GET['journal_id']) : 0;
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($journal_id == 0 || $patient_id == 0) {
    echo "Invalid Journal ID or Patient ID";
    exit();
}

// Fetch the journal content and related fields
$query = "SELECT journal_content, journal_date, sleep_time, wake_time, food, 
exercise, file_path, original_name, file_type, file_size, highlight FROM journal 
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
    <title>Therapist Journal</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/therapistJournal.css">

</head>

<body>
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    </header>
    <div class="therapistContainer">
        <div class="leftbox">
            <!-- should be selected patient journal list page -->
            <a href="patientDetail.php?patient_id=<?php echo $patient_id; ?>">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="container">
            <div class="entry-header">John Smith</div>
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

            <span class="star" id="starIcon"><?php echo $journal_info['highlight'] == 1 ? '★' : '☆'; ?></span>
        </div>
        <div class="rightbox"></div>
    </div>
    <script>
        // 在此初始化 PHP 中的變數到 JavaScript 中
        var journalId = <?php echo json_encode($journal_id); ?>;
    </script>
    <script src="../scripts/journal.js"></script>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>