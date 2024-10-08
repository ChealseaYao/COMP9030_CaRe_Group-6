<?php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php");
    exit();
}


$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
$journal_id = isset($_GET['journal_id']) ? intval($_GET['journal_id']) : 0;

if ($journal_id == 0 || $patient_id == 0) {
    echo "Invalid Journal ID or Patient ID";
    exit();
}

// Database connection
include '../inc/dbconn.inc.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['journal_id']) && isset($data['highlight'])) {
        $journal_id = intval($data['journal_id']);
        $highlight = intval($data['highlight']);

        // 更新指定 journal_id 的 highlight 状态
        if (in_array($highlight, [0, 1])) {
            $sql = "UPDATE journal SET highlight = ? WHERE journal_id = ?";
            $stmt = $conn->prepare($sql); // 使用变量 $sql
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


$query = "SELECT j.journal_content, j.journal_date, j.sleep_time, j.wake_time, j.food, 
                 j.exercise, j.file_path, j.original_name, j.file_type, j.file_size, j.highlight, 
                 u.full_name
          FROM journal j
          JOIN patient p ON j.patient_id = p.patient_id
          JOIN `user` u ON p.user_id = u.user_id
          WHERE j.journal_id = ? AND j.patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $journal_id, $patient_id);
$stmt->execute();
$journal_result = $stmt->get_result();
$journal_info = $journal_result->fetch_assoc();

if (!$journal_info) {
    echo "Journal not found.";
    exit();
}

$patient_name = $journal_info['full_name'];

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
        <!-- logout button -->
        <div class="logout-container">
            <a href="../logout.php" class="logout-link">Log-out</a>
        </div>
    </header>
    <div class="therapistContainer">

        <?php
        $origin = isset($_GET['origin']) ? $_GET['origin'] : 'history'; // Default to 'history' if not provided
        $back_url = ($origin === 'dashboard') ? 'therapistDashboard.php' : 'historyJournalList.php?patient_id=' . $patient_id;
        ?>
        <div class="leftbox">
            <a href="<?php echo $back_url; ?>">
                <button class="back-btn">Back</button>
            </a>
        </div>

        <div class="container">
            <div class="entry-header"><?php echo htmlspecialchars($patient_name); ?></div>
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
                <div><?php echo isset($journal_info['food']) ? htmlspecialchars($journal_info['food']) : 'N/A'; ?></div>
                <label>Exercise:</label>
                <div><?php echo isset($journal_info['exercise']) ? htmlspecialchars($journal_info['exercise']) : 'N/A'; ?></div>
            </div>
            <div class="download">
                <label>Attached File:</label>
                <div><?php echo !empty($journal_info['original_name']) ? htmlspecialchars($journal_info['original_name']) : 'No file attached'; ?></div>
                <?php if (!empty($journal_info['file_path']) && !empty($journal_info['original_name'])): ?>
                    <a href="../patient/download.php?journal_id=<?php echo $journal_id; ?>"><button>Download</button></a>
                <?php else: ?>
                    <button disabled>No file to download</button>
                <?php endif; ?>
            </div>

            <span class="star" id="starIcon" data-journal-id="<?php echo $journal_id; ?>">
                <?php echo $journal_info['highlight'] == 1 ? '★' : '☆'; ?>
            </span>
        </div>
        <div class="rightbox"></div>
    </div>
    <script>
        // Initialize PHP variables into JavaScript
        var journalId = <?php echo json_encode($journal_id); ?>;

        document.getElementById('starIcon').addEventListener('click', function() {

            const journalId = this.getAttribute('data-journal-id');
            const currentHighlight = this.textContent === '★' ? 1 : 0;
            const newHighlight = currentHighlight === 1 ? 0 : 1;


            fetch('../therapist/updateHighlight.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        journal_id: journalId,
                        highlight: newHighlight
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        this.textContent = newHighlight === 1 ? '★' : '☆';
                    } else {
                        alert('Failed to update highlight.');
                    }
                })
                .catch(error => {
                    console.error('Error updating highlight:', error);
                });
        });
    </script>


    </script>
    <script src="../scripts/journal.js"></script>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>