<?php
session_start(); // Start the session

// Check if user is logged in and is a therapist
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php"); // Redirect to login page if not authenticated
    exit();
}

// Get the therapist's user_id from the session
$user_id = $_SESSION['user_id']; 

// Include the database connection file
include '../inc/dbconn.inc.php'; 

// Fetch the correct therapist_id using the user's user_id
$therapist_query = "SELECT therapist_id FROM therapist WHERE user_id = ?";
$stmt = $conn->prepare($therapist_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$therapist_result = $stmt->get_result();

if ($therapist_result->num_rows > 0) {
    $therapist_data = $therapist_result->fetch_assoc();
    $therapist_id = $therapist_data['therapist_id'];
} else {
    echo "Therapist not found.";
    exit();
}
$stmt->close();

// Fetch the patient ID from the URL parameters
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

// Validate the patient ID
if ($patient_id <= 0) {
    echo "Invalid patient ID.";
    exit();
}

// Retrieve patient details
$patient_query = "SELECT u.full_name FROM user u 
                  JOIN patient p ON u.user_id = p.user_id 
                  WHERE p.patient_id = ? AND p.therapist_id = ?";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("ii", $patient_id, $therapist_id);
$stmt->execute();
$patient_result = $stmt->get_result();

if ($patient_result->num_rows === 0) {
    echo "Patient not found or does not belong to the therapist.";
    exit();
}

$patient_data = $patient_result->fetch_assoc();
$patient_name = $patient_data['full_name'];

// Fetch patient's history notes
$note_query = "SELECT note_id, note_date, note_content 
               FROM note 
               WHERE patient_id = ? AND therapist_id = ? 
               ORDER BY note_date DESC";
$stmt = $conn->prepare($note_query);
$stmt->bind_param("ii", $patient_id, $therapist_id);
$stmt->execute();
$notes_result = $stmt->get_result();

$notesData = [];
while ($row = $notes_result->fetch_assoc()) {
    $noteDate = $row['note_date'];
    if (!isset($notesData[$noteDate])) {
        $notesData[$noteDate] = [];
    }
    $notesData[$noteDate][] = [
        'note_id' => $row['note_id'],  // Include the note_id
        'note_content' => $row['note_content']
    ];
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Min Yao">
    <title>Record Patients Notes</title>
    <link rel="stylesheet" href="../style/addNewNote.css">
    <link rel="stylesheet" href="../style/global.css">
</head>
<body>
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    </header>
    <div class="therapistContainer">
        <div class="leftbox">
            <a href="patientDetail.php?patient_id=<?php echo $patient_id; ?>">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="note-container">
            <div class="note-title">
                <h2 id="note-notes">Notes</h2>
            </div>
            <div class="note-patient-name">
                <label for="patient-name">Patient Name</label>
                <input type="text" id="patient-name" value="<?php echo htmlspecialchars($patient_name); ?>" disabled>
            </div>
            <div class="date-picker-container">
                <label for="date-range-input" id="history-note">History Notes</label>
                <span id="date-icon">
                    <img src="../image/calendar.png" alt="Calendar Icon">
                </span>
                <div class="calendar-popup">
                    <label for="year">Year:</label>
                    <select id="year"></select>
                    <label for="month">Month:</label>
                    <select id="month"></select>
                    <label for="day">Day:</label>
                    <select id="day"></select>
                    <button id="confirm-btn">Confirm</button>
                </div>
            </div>
            <div class="tableContainer">
                <table class="note-history-note">
                    <thead>
                        <tr>
                            <th class="date-column">Date</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div>
                <p id="note-add-new">Add a New Note</p>
                <textarea name="new-note" id="new-note"></textarea>
            </div>
            <div class="note-button">
                <button type="submit" id="note-save">Save</button>
            </div>
        </div>
        <div id="note-deleteModal" class="note-modal">
            <div class="note-modal-content">
                <p>Do you want to remove this note?</p>
                <button id="note-cancelBtn">Cancel</button>
                <button id="note-confirmBtn">Confirm</button>
            </div>
        </div>
        <div id="saveModal" class="note-modal-save">
            <div class="note-modal-content-save">
                <p>Note has been successfully added!</p>
                <button id="saveConfirmBtn">OK</button>
            </div>
        </div>
        <div class="rightbox"></div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
    <script src="../scripts/datepicker.js"></script>
    <script src="../scripts/showNote.js"></script>
    <script>
        // Embed the PHP data into the JavaScript context
        const notesData = <?php echo json_encode($notesData); ?>;
        const patientName = "<?php echo htmlspecialchars($patient_name, ENT_QUOTES); ?>";
        const patient_id = <?php echo json_encode($patient_id); ?>;
    </script>
</body>
</html>