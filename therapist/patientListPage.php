<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caredb"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $status = $data['status'];

    // Update the patient's status in the database
    $sql = "UPDATE patient SET badge = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
    exit; // Stop further script execution since this is an AJAX request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/patientList.css">
</head>
<body class="patientList-body">
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.html"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    </header>

    <div class="therapistContainer">
        <div class="leftbox">
            <a href="therapistDashboard.html">
                <button class="back-btn">Back</button>
            </a>
            <h3>Badge</h3>
            <div class="badge-section">
                <div class="badge-item" draggable="true" data-status="good">
                    <span class="status good"></span> Good Status
                </div>
                <div class="badge-item" draggable="true" data-status="bad">
                    <span class="status bad"></span> Bad Status
                </div>
                <div class="badge-item" draggable="true" data-status="danger">
                    <span class="status danger"></span> Danger Status
                </div>
            </div>
        </div>

        <div class="patient-list">
            <div class="nameAndButton">
                <h2>Patient List</h2>
                <form class="search-bar">
                    <input type="text" placeholder="Search..." name="search">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="tableContainer">
                <!-- PHP Code to Fetch and Display Patients Dynamically -->
                <?php
                // Query to get patient data with names and status
                $sql = "SELECT patient.age, patient.badge, user.full_name, patient.user_id
                        FROM patient
                        JOIN user ON patient.user_id = user.user_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Output HTML for each patient
                        echo '<div class="patient-item" data-user-id="' . $row['user_id'] . '">';
                        echo '<div class="left-section">';
                        echo '<div class="patient-icon">☰</div>';
                        echo '<div>';
                        echo '<strong>' . htmlspecialchars($row['full_name']) . '</strong><br>';
                        echo 'Age: ' . htmlspecialchars($row['age']);
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="right-section">';
                        echo '<div class="status-container">';

                        // Add a span with the correct status color
                        if ($row['badge'] === 'good status') {
                            echo '<span class="status good"></span>';
                        } elseif ($row['badge'] === 'bad status') {
                            echo '<span class="status bad"></span>';
                        } elseif ($row['badge'] === 'danger status') {
                            echo '<span class="status danger"></span>';
                        }

                        echo '</div>';
                        echo '<a href="patientDetail.html"><button class="details">Details</button></a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No patients found.</p>";
                }

                $conn->close();
                ?>
            </div>
        </div>

        <div class="groups">
            <div class="nameAndButton">
                <h2>Groups</h2>
                <button class="create-new">Create New</button>
            </div>
            <div id="groupContainer" class="tableContainer">
                <div class="group-item">Tuesday 3pm Session</div>
                <div class="group-item">Friday Special</div>
                <div class="group-item">Anxiety Group</div>
                <div class="group-item">Avengers</div>
            </div>
            <h3>Members</h3>
            <div class="members">
                <p id="currentGroupName">Group Name</p>
                <div id="membersContainer" class="tableContainer">
                    <!-- Dynamic members list -->
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/createNewModal.js"></script>
    <script src="../scripts/groupSelection.js"></script>
    <script src="../scripts/memberDeletion.js"></script>
    <script src="../scripts/drag&drop.js"></script>

    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>
</html>
