<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caredb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request for different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Handle status update
    if (isset($data['user_id']) && isset($data['status'])) {
        $user_id = $data['user_id'];
        $status = $data['status'];
        
        if (in_array($status, ['good status', 'bad status', 'danger status'])) {
            $sql = "UPDATE patient SET badge = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $status, $user_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid status']);
        }
        exit;
    }

    // Handle adding a member to a group
    if (isset($data['group_id']) && isset($data['user_id'])) {
        $group_id = $data['group_id'];
        $user_id = $data['user_id'];

        $sql = "INSERT INTO group_patient (group_id, patient_id) 
                VALUES (?, (SELECT patient_id FROM patient WHERE user_id = ?))";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $group_id, $user_id);

        if ($stmt->execute()) {
            $sql = "SELECT full_name FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $patient = $result->fetch_assoc();

            echo json_encode(['success' => true, 'patient_name' => $patient['full_name']]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add member']);
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    // Handle fetching group members
    if (isset($data['group_id'])) {
        $group_id = $data['group_id'];

        $sql = "SELECT user.full_name 
                FROM group_patient 
                JOIN patient ON group_patient.patient_id = patient.patient_id
                JOIN user ON patient.user_id = user.user_id
                WHERE group_patient.group_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $group_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $members = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $members[] = ['name' => $row['full_name']];
            }
        }

        $stmt->close();
        $conn->close();

        echo json_encode(['members' => $members]);
        exit;
    }

    // Handle creating a new group
    if (isset($data['action']) && $data['action'] === 'create_group' && isset($data['group_name'])) {
        $group_name = $data['group_name'];

        // Insert new group into the group table
        $sql = "INSERT INTO `group` (group_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $group_name);

        if ($stmt->execute()) {
            // Get the ID of the newly created group
            $group_id = $stmt->insert_id;

            // Return success response with the new group ID
            echo json_encode(['success' => true, 'group_id' => $group_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create group']);
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    // Handle search request
    if (isset($data['action']) && $data['action'] === 'search_patient' && isset($data['search_query'])) {
        $search_query = '%' . $data['search_query'] . '%'; // Use wildcards for partial search

        // Query to search for patients by name
        $sql = "SELECT patient.age, patient.badge, user.full_name, patient.user_id
                FROM patient
                JOIN user ON patient.user_id = user.user_id
                WHERE user.full_name LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        $patients = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $patients[] = [
                    'full_name' => $row['full_name'],
                    'age' => $row['age'],
                    'badge' => $row['badge'],
                    'user_id' => $row['user_id']
                ];
            }
        }

        $stmt->close();
        $conn->close();

        // Return the matching patients as JSON
        echo json_encode(['patients' => $patients]);
        exit;
    }

    // Handle fetching all patients when no search query is provided
    if (isset($data['action']) && $data['action'] === 'fetch_all_patients') {
        // Query to get all patients
        $sql = "SELECT patient.age, patient.badge, user.full_name, patient.user_id
                FROM patient
                JOIN user ON patient.user_id = user.user_id";
        $result = $conn->query($sql);

        $patients = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $patients[] = [
                    'full_name' => $row['full_name'],
                    'age' => $row['age'],
                    'badge' => $row['badge'],
                    'user_id' => $row['user_id']
                ];
            }
        }

        $conn->close();

        // Return all patients as JSON
        echo json_encode(['patients' => $patients]);
        exit;
    }
}

// Handle DELETE request for deleting a member
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $group_id = $data['group_id'];
    $member_name = $data['member_name'];

    // Find the patient's user_id by their full name
    $sql = "SELECT user_id FROM user WHERE full_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // Now delete the member from group_patient table
        $sql = "DELETE FROM group_patient WHERE group_id = ? AND patient_id = (SELECT patient_id FROM patient WHERE user_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $group_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete member']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Member not found']);
    }

    $conn->close();
    exit;
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
                        echo '<div class="patient-icon" draggable="true">☰</div>'; // Icon now draggable
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
                ?>
            </div>
        </div>

        <div class="groups">
            <div class="nameAndButton">
                <h2>Groups</h2>
                <button class="create-new">Create New</button>
            </div>
            <div id="groupContainer" class="tableContainer">
                <!-- PHP to display group names dynamically -->
                <?php
                $sql = "SELECT group_id, group_name FROM `group`"; // Assuming your table is named `group`
                $group_result = $conn->query($sql);

                if ($group_result->num_rows > 0) {
                    while ($group_row = $group_result->fetch_assoc()) {
                        echo '<div class="group-item" data-group-id="' . $group_row['group_id'] . '">';
                        echo htmlspecialchars($group_row['group_name']);
                        echo '</div>';
                    }
                } else {
                    echo "<p>No groups found.</p>";
                }
                ?>
            </div>
            <h3>Members</h3>
            <div class="members">
                <p id="currentGroupName">Group Name</p>
                <div id="membersContainer" class="tableContainer">
                    <!-- Dynamic members list -->
                </div>
            </div>

            <!-- create new group modal -->
            <div class="modal" id="createGroupModal">
                <div class="modal-content">
                <h3>Create a New Group</h3>
                <div class="group">
                    <label for="groupName">Group </label>
                    <input type="text" id="groupName" name="groupName" />
                </div>
                <div class="modal-buttons">
                    <button id="cancelButton">Cancel</button>
                    <button id="confirmButton">Confirm</button>
                </div>
                </div>
            </div>

            <!--  delete member modal -->
            <div class="modal" id="confirmDeleteModal">
                <div class="modal-content">
                <p>Do you want to remove this member?</p>
                <div class="modal-buttons">
                    <button id="cancelDeleteButton">Cancel</button>
                    <button id="confirmDeleteButton">Confirm</button>
                </div>
            </div>
      </div>
        </div>
    </div>

    <script src="../scripts//createNewModal.js"></script>
    <script src="../scripts//groupSelection.js"></script>
    <script src="../scripts//memberDeletion.js"></script>
    <script src="../scripts//drag&dropBadge.js"></script> 
    <script src="../scripts//drag&dropMember.js"></script> 
    <script src="../scripts//searchPatient.js"></script> 

    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>
</html>
