<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php");
    exit();
}

include '../inc/dbconn.inc.php'; // 请确保该路径指向您的数据库连接文件


// Get therapist's user_id from the session
$user_id = $_SESSION['user_id'];

// Fetch therapist details
$therapist_query = $conn->prepare("SELECT user.full_name, therapist.therapist_title FROM user JOIN therapist ON user.user_id = therapist.user_id WHERE user.user_id = ?");
$therapist_query->bind_param("i", $user_id);
$therapist_query->execute();
$therapist_result = $therapist_query->get_result();

if (!$therapist_result) {
    die("Error fetching therapist details: " . $conn->error);
}

$therapist = $therapist_result->fetch_assoc();
$therapist_name = $therapist['full_name'] ?? 'Unknown Therapist';
$therapist_title = $therapist['therapist_title'] ?? 'Title Not Available';

// Fetch journals of patients assigned to the therapist
$journals_query = $conn->prepare("SELECT user.full_name AS patient_name, journal.journal_content, journal.journal_date, journal.highlight 
                                  FROM journal 
                                  JOIN patient ON journal.patient_id = patient.patient_id 
                                  JOIN user ON patient.user_id = user.user_id
                                  WHERE patient.therapist_id = ? 
                                  ORDER BY journal.journal_date DESC");
$therapist_id_query = $conn->prepare("SELECT therapist_id FROM therapist WHERE user_id = ?");
$therapist_id_query->bind_param("i", $user_id);
$therapist_id_query->execute();
$therapist_id_result = $therapist_id_query->get_result();
$therapist_id_row = $therapist_id_result->fetch_assoc();
$therapist_id = $therapist_id_row['therapist_id'];
$journals_query->bind_param("i", $therapist_id);
$journals_query->execute();
$journals_result = $journals_query->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Wenqiang Jin">
    <meta name="description" content="therapist of COMP9030_CaRe_Groups6">
    <title>Therapist Dashboard</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/therapistDashboard.css">
</head>

<body class="therapistBody">
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
        <!-- logou button -->
        <div class="logout-container">
            <a href="../logout.php" class="logout-link">Logout</a>
         </div>
    </header>

    <div class="therapistContainer">
        <div class="left">
            <div id="therapistInfo">
                <img src="..\image\therapistIMG.png" alt="vivian" class="profileImage">
                <div id="txtinfo">
                    <p id="therapistName">
                        <!-- Vivian Harper should be user's full name -->
                        <?= htmlspecialchars($therapist_name) ?>
                    </p>
                    <p id="TherapistTitle">
                        <!-- User's title -->
                        <?= htmlspecialchars($therapist_title) ?>
                    </p>
                </div>

            </div>
            <a href="patientListPage.php">
                <button class="viewPatients">View Patients</button>
            </a>
            <div id="therapistStatistics">
                <h3>Statistics</h3>
            </div>
        </div>
        <div class="centre">
            <h1>
                <!-- vivian should be user's first name -->
                G'day <?= htmlspecialchars($therapist_name) ?>!
            </h1>
            <div id="global_journalList">
                <h3>Latest journals from your patients</h3>
                <div class="tableContainer">
                    <!-- click journal title will open that journal in new page -->
                    <table class="global_journalLists-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Patient</th>
                                <th>Content</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($journal = $journals_result->fetch_assoc()) : ?>
                                <tr>
                                    <td class="star"><?= $journal['highlight'] ? '★' : '' ?></td>
                                    <td><?= htmlspecialchars($journal['patient_name']) ?></td>
                                    <td><a href="journalDetail.php?date=<?= urlencode($journal['journal_date']) ?>&patient_name=<?= urlencode($journal['patient_name']) ?>">
                                        <?= htmlspecialchars(strlen($journal['journal_content']) > 50 ? substr($journal['journal_content'], 0, 50) . '...' : $journal['journal_content']) ?>
                                    </a></td>
                                    <td><?= date("d/m/Y", strtotime($journal['journal_date'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="right">
            <div id="therapistSummary">
                <h3>
                    Last Week Summary
                </h3>
            </div>
            <h2>
                Appointment Calendar
            </h2>
            <div id="therapistCalendar">

            </div>
        </div>
    </div>

    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>

<?php
// Close database connections
$therapist_query->close();
$therapist_id_query->close();
$journals_query->close();
$conn->close();
?>