<?php
session_start();

// Ensure the patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit;
}

require_once '../inc/dbconn.inc.php';  // Include your database connection settings

// Get patient's user_id from the session
$user_id = $_SESSION['user_id'];

// Fetch patient details
$patient_query = $conn->prepare("SELECT full_name FROM user WHERE user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient_result = $patient_query->get_result();

if (!$patient_result) {
    die("Error fetching patient details: " . $conn->error);
}

$patient = $patient_result->fetch_assoc();
$patient_name = $patient['full_name'] ?? 'Unknown Patient';

// Fetch journal entries for the patient
$journal_query = $conn->prepare("SELECT journal_content, journal_date FROM journal WHERE patient_id = ? ORDER BY journal_date DESC LIMIT 5");
$journal_query->bind_param("i", $user_id);
$journal_query->execute();
$journal_result = $journal_query->get_result();



// Calculate stats for the last week's activity
$week_stats_query = $conn->prepare("
    SELECT 
        COUNT(journal_id) AS journal_entries,
        AVG(TIME_TO_SEC(TIMEDIFF(wake_time, sleep_time))/3600) AS average_sleep_hours,
        GROUP_CONCAT(DISTINCT exercise ORDER BY journal_date DESC SEPARATOR ', ') AS exercise_summary,
        GROUP_CONCAT(DISTINCT food ORDER BY journal_date DESC SEPARATOR ', ') AS food_summary
    FROM journal
    WHERE patient_id = ? AND journal_date > DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
");
$week_stats_query->bind_param("i", $user_id);
$week_stats_query->execute();
$week_stats_result = $week_stats_query->get_result();
$week_stats = $week_stats_result->fetch_assoc();

$average_sleep_hours = number_format($week_stats['average_sleep_hours'], 1);

// Fetch three random affirmations
$affirmations_query = $conn->prepare("SELECT affirmation FROM affirmation ORDER BY RAND() LIMIT 3");
$affirmations_query->execute();
$affirmations_result = $affirmations_query->get_result();

// Handle affirmation selection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['affirmation'])) {
    $selected_affirmation = $_POST['affirmation'];
    // Save or update the affirmation selection in the database
    // Placeholder for saving logic
}
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
  <script src="../scripts/patientDashboard.js"></script>
  <script src="../scripts/selectAffirmation.js"></script>
</head>
<body>
    <header class="navbar">
        <a href="patientDashboard.html"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
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
                        <?php while ($journal = $journal_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($journal['journal_content']) ?></td>
                            <td><?= htmlspecialchars($journal['journal_date']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="viewHistoryRecord.php">
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
                <form id="affirmationForm" method="post">
                    <?php while ($affirmation = $affirmations_result->fetch_assoc()): ?>
                    <label>
                        <input type="radio" name="affirmation" value="<?= htmlspecialchars($affirmation['affirmation']) ?>"> 
                        <?= htmlspecialchars($affirmation['affirmation']) ?>
                    </label><br>
                    <?php endwhile; ?>
                
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
$journal_query->close();
$affirmations_query->close();
$week_stats_query->close();
$conn->close();
?>

