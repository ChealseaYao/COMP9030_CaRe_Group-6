<?php


session_start();

// 确保用户已登录
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

require_once '../inc/dbconn.inc.php';  // 包含数据库连接设置

// 获取用户的 user_id
$user_id = $_SESSION['user_id'];

// 获取患者详情
$patient_query = $conn->prepare("SELECT u.full_name, p.patient_id FROM user u JOIN patient p ON u.user_id = p.user_id WHERE u.user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient_result = $patient_query->get_result();

if (!$patient_result) {
    die("Error fetching patient details: " . $conn->error);
}

$patient = $patient_result->fetch_assoc();
$patient_name = $patient['full_name'] ?? 'Unknown Patient';
$patient_id = $patient['patient_id'];  // 获取 patient_id 

// 获取患者的日记
$query = "SELECT journal_content, journal_date FROM journal WHERE patient_id = ? ORDER BY journal_date DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id); 
$stmt->execute();
$journal_result = $stmt->get_result();
$journals = [];
while ($row = $journal_result->fetch_assoc()) {
    $journals[] = $row;
}

// 计算上周的活动统计
$week_stats_query = $conn->prepare("SELECT 
COUNT(journal_id) AS journal_entries,
AVG(
    CASE
        WHEN wake_time < sleep_time THEN
            TIME_TO_SEC(TIMEDIFF(ADDTIME('24:00:00', wake_time), sleep_time))/3600
        ELSE
            TIME_TO_SEC(TIMEDIFF(wake_time, sleep_time))/3600
    END
) AS average_sleep_hours,
GROUP_CONCAT(DISTINCT exercise ORDER BY journal_date DESC SEPARATOR ', ') AS exercise_summary,
GROUP_CONCAT(DISTINCT food ORDER BY journal_date DESC SEPARATOR ', ') AS food_summary
FROM journal
WHERE patient_id = ? AND journal_date > DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
$week_stats_query->bind_param("i", $patient_id);  
$week_stats_query->execute();
$week_stats_result = $week_stats_query->get_result();
$week_stats = $week_stats_result->fetch_assoc();

$average_sleep_hours = number_format($week_stats['average_sleep_hours'], 1);

// 查询患者当天是否已经选择了肯定句
$selected_affirmation_query = $conn->prepare("
    SELECT a.affirmation
    FROM patient_affirmation pa
    JOIN affirmation a ON pa.affirmation_id = a.affirmation_id
    WHERE pa.patient_id = ? AND pa.selection_date = CURDATE()");
$selected_affirmation_query->bind_param("i", $patient_id);
$selected_affirmation_query->execute();
$selected_affirmation_result = $selected_affirmation_query->get_result();
$selected_affirmation = $selected_affirmation_result->fetch_assoc()['affirmation'] ?? null;

// 输出调试信息，查看是否正确读取了肯定句
if ($selected_affirmation) {
    error_log("Selected Affirmation: " . $selected_affirmation);
} else {
    error_log("No affirmation selected for today.");
}



// 随机生成肯定句
$affirmations_query = $conn->prepare("SELECT affirmation FROM affirmation ORDER BY RAND() LIMIT 3");
$affirmations_query->execute();
$affirmations_result = $affirmations_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Zhen Liu">
  <meta name="description" content="Patient Dashboard for CaRe Groups6">
  <title>Patient Dashboard</title>
  <link rel="stylesheet" href="../style/global.css">
  <link rel="stylesheet" href="../style/patientDashboard.css">
  <script src="../scripts/selectAffirmation.js" defer></script> <!-- 外部JS文件 -->
</head>

<body data-patient-id="<?= htmlspecialchars($patient_id) ?>"> <!-- 确保 patient_id 正确传递 -->

    <header class="navbar">
        <a href="patientDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
         <!-- logout button -->
        <div class="logout-container">
            <a href="../logout.php" class="logout-link">Log-out</a>
        </div>
    </header>
    
    <div class="patientDashboard">
        <div class="left-panel">
            <h1>G'Day <?= htmlspecialchars($patient_name) ?>!</h1>
            <div class="content-list">
                <a href="newJournalPage.php">
                    <button class="newJournal-button">New Journal</button>  
                </a>
                <table class="contentList-table">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($journals)): ?>
                            <?php foreach ($journals as $journal): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($journal['journal_content']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($journal['journal_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No journals available for this patient.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="viewHistoryRecord.php?patient_id=<?= $patient_id ?>">
                    <button class="patientDashboardViewMore-Button">View More</button>
                </a>
            </div>
        </div>
        <div class="right-panel">
            <div class="activity">
                <h3>Last Week's Activity</h3>
                <div class="activity-stats">
                    <div class="stat">
                        <p>Journal Entry</p>
                        <h2><?= $week_stats['journal_entries'] ?></h2>
                    </div>
                    <div class="stat">
                        <p>Average Sleep Time</p>
                        <h2><?= $average_sleep_hours ?> <span>hr</span></h2>
                    </div>
                    <div class="stat">
                        <p>Food and Exercise</p>
                        <h2>Good</h2>
                    </div>
                </div>
            </div>
            <div class="goal">
                <h3>Current Week's Goal</h3>
                <p>Continue good sleep cycle and diet, try to record more journals.</p>
            </div>
            <div class="affirmation">
                <h3>Pick Your Daily Affirmation ✔</h3>
                <form id="affirmationForm">
                    <?php if ($selected_affirmation): ?>
                        <!-- 显示用户已选择的肯定句 -->
                        <p style="margin: 0; padding: 40px; color: #102e5d; font-size: 1.8rem; font-family: Comic Sans MS, cursive;">
                            <?= htmlspecialchars($selected_affirmation) ?>
                        </p>
                    <?php else: ?>
                        <!-- 用户尚未选择，显示随机肯定句 -->
                        <?php while ($affirmation = $affirmations_result->fetch_assoc()): ?>
                        <label>
                            <input type="radio" name="affirmation" value="<?= htmlspecialchars($affirmation['affirmation']) ?>"> 
                            <?= htmlspecialchars($affirmation['affirmation']) ?>
                        </label><br>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>
</html>

<?php
$patient_query->close();
$stmt->close();
$affirmations_query->close();
$selected_affirmation_query->close();
$conn->close();
?>
