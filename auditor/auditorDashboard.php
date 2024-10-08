<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'auditor') {
    header("Location: ../login.php");
    exit();
}

include '../inc/dbconn.inc.php';  // Ensure database connection is properly configured

// Fetch auditor's full name
$user_id = $_SESSION['user_id'];
$sql = "SELECT full_name FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$auditor_name = ($result->fetch_assoc())['full_name'] ?? 'Auditor';

// Fetch all therapists and their consultations overview
$sql_therapist = "SELECT t.therapist_id, u.full_name AS therapist_name, COUNT(DISTINCT p.patient_id) AS patient_count, 
                        GROUP_CONCAT(DISTINCT c.case_type ORDER BY c.case_type SEPARATOR ', ') AS case_types
                 FROM therapist t
                 JOIN consultation c ON t.therapist_id = c.therapist_id
                 JOIN patient p ON c.patient_id = p.patient_id
                 JOIN user u ON t.user_id = u.user_id
                 GROUP BY t.therapist_id, u.full_name";
$stmt_therapist = $conn->prepare($sql_therapist);
$stmt_therapist->execute();
$therapist_results = $stmt_therapist->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Zhen Liu">
    <meta name="description" content="Auditor Dashboard for CaRe Groups6">
    <title>Auditor Dashboard</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/auditorDashboard.css">
</head>

<body>
    <header class="navbar">
        <a href="auditorDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
        <div class="logout-container">
            <a href="../logout.php" class="logout-link">Log-out</a>
        </div>
    </header>

    <div class="auditorDashboard">
        <div class="left-panel">
            <h1>G'Day <?php echo htmlspecialchars($auditor_name); ?>!</h1>
            <div class="therapist-consultation-overview">
                <div class="tips">
                    <h3>Therapist Consultation Overview</h3>
                    <p class="tip">Click therapist name to see details</p>
                </div>
                <div class="tableContainer">
                    <table class="consultationOverview-table">
                        <thead>
                            <tr>
                                <th>Therapist Name</th>
                                <th>Patient Count</th>
                                <th>Case Type</th>
                            </tr>
                        </thead>
                        <tbody id="therapist-list">
                            <?php while ($row = $therapist_results->fetch_assoc()): ?>
                                <tr data-therapist-id="<?php echo $row['therapist_id']; ?>">
                                    <td><?php echo htmlspecialchars($row['therapist_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['patient_count']); ?></td>
                                    <td><?php echo htmlspecialchars($row['case_types']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="therapist-consultation-detail">
                <h3 id="therapist-detail-header">Therapist Consultation Detail</h3>
                <div class="tableContainer">
                    <table class="consultationDetail-table" id="therapist-detail">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Case Type</th>
                                <th>Total Consultation Minutes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Consultation details will be dynamically loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>

    <script src="../scripts/auditorDashboard.js"></script>
</body>

</html>