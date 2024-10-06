<?php
// Database connection
include '../inc/dbconn.inc.php'; 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
if ($patient_id == 0) {
    echo "Invalid Patient ID";
    exit();
}

$sql_name = "SELECT full_name FROM user 
             INNER JOIN patient ON user.user_id = patient.user_id 
             WHERE patient.patient_id = ?";
$stmt_name = $conn->prepare($sql_name);
$stmt_name->bind_param("i", $patient_id);
$stmt_name->execute();
$result_name = $stmt_name->get_result();

$patient_name = "";
if ($result_name->num_rows > 0) {
    $row = $result_name->fetch_assoc();
    $patient_name = $row['full_name'];
} else {
    $patient_name = "Unknown Patient";
}

$sql = "SELECT journal_id, journal_date, journal_content
        FROM journal 
        WHERE patient_id = ? 
        ORDER BY journal_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

$journals = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $journals[] = [
            'journal_id' => $row['journal_id'],
            'date' => $row['journal_date'],
            'content' => $row['journal_content']
        ];
    }
}

// Convert journal data to JSON
$journalDataJSON = json_encode($journals);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Wenqiang Jin">
    <meta name="description" content="therapist of COMP9030_CaRe_Groups6">
    <title>Patient - History Journal List</title>
    <script src="../scripts/datepicker.js"></script>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/historyJournal.css">
</head>

<body class="therapistBody">
    <!-- global navigation bar TBD -->
    <header class="navbar">
        <a href="patientDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
        <!-- logout button -->
        <div class="logout-container">
            <a href="../logout.php" class="logout-link">Log-out</a>
        </div>
    </header>
    <div class="therapistContainer">
        <div class="historyleft">
            <a href="patientDashboard.php">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="centre">
            <h1>
                <!-- John Smith should be user's Full name -->
                History Journals of <?php echo htmlspecialchars($patient_name); ?>
            </h1>
            <div id="historyJournal">
                <div class="searchPannel">
                    <form class="search-bar">
                        <input type="text" placeholder="Search..." name="search" />
                        <button type="submit">Search</button>
                    </form>
                    <div class="date-picker-container">
                        <label for="date-range-input" id="history-note"></label>
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
                </div>
                <div class="tableContainer">
                    <!-- click journal title will open that journal in new page -->
                    <table class="historyJournal-table patientHJT">
                        <thead>
                            <tr>
                                <th>Content</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // if ($result->num_rows > 0) {
                            //     while ($row = $result->fetch_assoc()) {
                            //         $journal_id = $row['journal_id'];
                            //         $journal_content = $row['journal_content'];
                            //         $journal_date = $row['journal_date'];
                                    
                            //         $formattedDate = date("d/m/Y", strtotime($journal_date));
                                    
                            //         echo "<tr>";
                            //         echo "<td><a href='journal.php?journal_id=$journal_id'>$journal_content</a></td>";
                            //         echo "<td>$formattedDate</td>";
                            //         echo "</tr>";
                            //     }
                            // } else {
                            //     echo "<tr><td colspan='3'>No journals found.</td></tr>";
                            // }

                            // $stmt->close();
                            // $conn->close();
                            ?>
                        </tbody>
                          
                    </table>
                </div>
            </div>
        </div>
        <div class="historyright">

        </div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
    <script>
        // Pass PHP data to JavaScript
        const journalData = <?php echo $journalDataJSON; ?>;
    </script>
    <script src="../scripts/viewHistoryRecord.js"></script>
</body>

</html>