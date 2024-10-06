<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}

include '../inc/dbconn.inc.php';

// Get therapist's user_id from the session
$user_id = $_SESSION['user_id'];

$patient_id_query = $conn->prepare("SELECT patient_id FROM patient WHERE user_id = ?");
$patient_id_query->bind_param("i", $user_id);
$patient_id_query->execute();
$patient_id_result = $patient_id_query->get_result();
$patient_id_row = $patient_id_result->fetch_assoc();
$patient_id = $patient_id_row['patient_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $journal_content = $_POST['journal_content'] ?? '';
    $food = $_POST['food'] ?? '';
    $exercise = $_POST['exercise'] ?? '';
    $sleep_time = $_POST['sleep_time'] ?? '';
    $wake_time = $_POST['wake_time'] ?? '';
    $highlight = 1; // Set highlight to 1


    // Get the current date for journal_date
    $journal_date = date('Y-m-d');

    // Handle file upload
    $file_path = null;
    $file_size = null;
    $file_type = null;
    $original_name = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $file_size = $file['size'];
        $file_type = $file['type'];
        $original_name = $file['name'];

        // Define the upload directory and move the uploaded file
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the uploads directory if it doesn't exist
        }

        $file_path = $upload_dir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $file_path);
    }

    // Insert the journal entry into the database
    $sql = "INSERT INTO journal (journal_date, journal_content, food, exercise, sleep_time, wake_time, highlight, file_path, original_name, file_size, file_type, patient_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssssi', $journal_date, $journal_content, $food, $exercise, $sleep_time, $wake_time, $highlight, $file_path, $original_name, $file_size, $file_type, $patient_id);

    if ($stmt->execute()) {
        echo "Journal submitted successfully!";
        // Redirect to success page
        header('Location: successfullySubmitPage.html');
        exit;
    } else {
        echo "Error submitting journal: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Today's Journal</title>
    <link rel="stylesheet" href="../style/newJournal.css" />
    <link rel="stylesheet" href="../style/global.css" />
  </head>

  <body class="newJournal-body">
    <!-- Header of page -->
    <header class="navbar">
      <a href="patientDashboard.html"
        ><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"
      /></a>
    </header>

    <div class="therapistContainer">
      <div class="leftbox">
        <!-- should be selected patient journal list page -->
        <a href="patientDashboard.php">
          <button class="back-btn">Back</button>
        </a>
      </div>
      <div class="container">
      <form class="main-newJournal" action="newJournalPage.php" method="POST" enctype="multipart/form-data">
        <div class="header">
          <div>
            <h1>Today's Journal</h1>
          </div>
          <div>
            <button
              class="upload-button"
              type="button" 
              onclick="document.getElementById('file-upload').click()"
            >
              Upload File
            </button>
            <input
              type="file"
              id="file-upload"
              name="file" 
              style="display: none"
              onchange="uploadFile(event)"
            />
            <div class="upload-info" id="upload-info">No file uploaded</div>
          </div>
        </div>

        <label for="day-description">How was your day?</label>
        <textarea id="day-description" name="journal_content" rows="4"></textarea>

        <div class="time-inputs">
          <div class="time-input">
            <label for="sleep-time">Sleep (last night)</label>
            <select id="sleep-time" name="sleep_time">
              <!-- Generated options here -->
            </select>
          </div>
          <div class="time-input">
            <label for="wake-time">Wake up (this morning)</label>
            <select id="wake-time" name="wake_time">
              <!-- Generated options here -->
            </select>
          </div>
        </div>

        <label for="food">What did you Eat?</label>
        <input type="text" id="food" name="food" />

        <label for="exercise">Today's Exercise?</label>
        <input type="text" id="exercise" name="exercise" />

        <button type="submit" class="submit-button">Submit</button>
      </form>
      </div>
    </div>
    <div class="rightbox"></div>
    <script src="../scripts/submitModal.js"></script>
    <script src="../scripts/generationOptions.js"></script>
    <script src="../scripts/uploadFile.js"></script>

    <footer class="site-footer">
      <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
  </body>
</html>
