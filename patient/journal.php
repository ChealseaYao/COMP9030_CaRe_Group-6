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

// Check if delete request is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_journal']) && isset($_GET['journal_id'])) {
    $journal_id = intval($_GET['journal_id']); // Get the journal_id
    $user_id = $_SESSION['user_id']; // Make sure user_id in session exists correctly

    // Check if parameters are passed correctly
    var_dump($journal_id, $user_id, $_POST['delete_journal']);
    // Prepare delete query
    $delete_query = $conn->prepare("DELETE FROM journal WHERE journal_id = ? AND patient_id = (SELECT patient_id FROM patient WHERE user_id = ?)");
    $delete_query->bind_param("ii", $journal_id, $user_id);

    if ($delete_query->execute()) {
        // Return success message in JSON format
        header("Location: viewHistoryRecord.php");
        exit;
    } else {
        echo "<script>alert('Failed to delete journal entry: " . $conn->error . "');</script>";
    }

    exit; // Stop further script execution after sending response
}



// Fetch patient details (full_name from user table and patient_id from patient table)
$patient_query = $conn->prepare("SELECT u.full_name, p.patient_id FROM user u JOIN patient p ON u.user_id = p.user_id WHERE u.user_id = ?");
$patient_query->bind_param("i", $user_id);
$patient_query->execute();
$patient_result = $patient_query->get_result();

if (!$patient_result) {
    die("Error fetching patient details: " . $conn->error);
}

$patient = $patient_result->fetch_assoc();
$patient_name = $patient['full_name'] ?? 'Unknown Patient';
$patient_id = $patient['patient_id'];  // get patient_id 
$journal_id = $_GET['journal_id'] ?? null; //check journal_id

// Fetch the patient's journals using the correct patient_id
if ($journal_id) {
$query = "SELECT journal_content, journal_date, sleep_time, wake_time, food, 
exercise, file_path, original_name, file_type, file_size FROM journal WHERE patient_id = ? ORDER BY journal_date DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id); 
$stmt->execute();
$journal_result = $stmt->get_result();
if ($journal_result->num_rows > 0) {
    $journal_info = $journal_result->fetch_assoc();
} else {
    $journal_info = null;
}
} else {
die("No journal selected.");
}
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
            // call AJAX send the delete request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "patientJournal.php?journal_id=<?php echo $journal_id; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = "viewHistoryRecord.php"; // return to record page
                    } else {
                        alert(response.message);
                    }
                }
            };
            xhr.send("delete_journal=true");
        }
        // Check delete function
        function showPopup() {
        document.getElementById("popupOverlay").style.display = "block";
        document.getElementById("popup").style.display = "block";
        }

        function hidePopup() {
        document.getElementById("popupOverlay").style.display = "none";
        document.getElementById("popup").style.display = "none";
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
            <a href="viewHistoryRecord.php">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="container">
            <div class="entry-header">
                <?= htmlspecialchars($patient_name) ?>
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
            <form method="POST" action="journal.php?journal_id=<?php echo $journal_id; ?>">
            <input type="hidden" name="delete_journal" value="true">
            <button type="button" class="delete-button" onclick="showPopup()">Delete</button>
            <!--Popup page-->
            <div class="popup-overlay" id="popupOverlay"></div>
            <div class="popup" id="popup">
                <p>Do you want to delete the journal?</p>
                <div>
                    <button type="button" id="note-cancelBtn" onclick="hidePopup()">Cancel</button>
                    <button type="submit" id="note-confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>
