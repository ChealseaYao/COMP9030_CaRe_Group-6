<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php");
    exit();
}


$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
if ($patient_id == 0) {
    echo "Invalid Patient ID";
    exit();
}


include '../inc/dbconn.inc.php'; 


$query = "SELECT u.full_name, p.age, p.gender, p.email 
          FROM patient p 
          INNER JOIN user u ON p.user_id = u.user_id 
          WHERE p.patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient_info = $patient_result->fetch_assoc();


if (!$patient_info) {
    echo "Patient not found.";
    exit();
}


$query = "SELECT journal_content, journal_date FROM journal WHERE patient_id = ? ORDER BY journal_date DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$journal_result = $stmt->get_result();
$journals = [];
while ($row = $journal_result->fetch_assoc()) {
    $journals[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Zhen Liu">
    <meta name="description" content="therapist of COMP9030_CaRe_Groups6">
    <title>Patient Details</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/patientDetail.css">
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
        <div class="leftbox">
            <a href="patientListPage.php"><button class="back-btn">Back</button></a>   
        </div>
        
        <div class="patientDetailContainer">
            <h1>Patient Details</h1>

            <div class="user-details">
                <ul class="patient-info">
                    <li>Name: <?php echo htmlspecialchars($patient_info['full_name']); ?></li>
                    <li>Age: <?php echo htmlspecialchars($patient_info['age']); ?></li>
                    <li>Gender: <?php echo htmlspecialchars($patient_info['gender']); ?></li>
                    <li>Email: <?php echo htmlspecialchars($patient_info['email']); ?></li>
                </ul>
                <a href="addNewNote.php?patient_id=<?php echo $patient_id; ?>">
                    <button class="notes-button">Notes</button>
                </a>
            </div>

            <div class="journal-lists">
                <label for="date-range-input" id="journals">Journals</label>
                <table class="journalLists-table">
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
                <a href="historyJournalList.php?patient_id=<?php echo $patient_id; ?>">
                    <button class="patientDetailPageViewMore-button">View More</button>
                </a>
            </div>
        </div>
        
        <div class="rightbox">
            
        </div>
    </div>

    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>
</html>
