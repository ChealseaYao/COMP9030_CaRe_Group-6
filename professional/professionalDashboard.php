<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professional_staff') {
  header("Location: ../login.php");
  exit();
}

include '../inc/dbconn.inc.php';

$user_id = $_SESSION['user_id'];

// Query to fetch the logged-in user's full name
$userSql = "
    SELECT full_name 
    FROM `user` 
    WHERE user_id = ?
";

$stmt = $conn->prepare($userSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$full_name = isset($user['full_name']) ? $user['full_name'] : 'Professional Staff';

// Query to fetch patient details from the view
$sql = "
    SELECT 
        patient_id, 
        age, 
        gender, 
        height, 
        weight, 
        therapist_title,
        therapist_name
    FROM professional_patient_view
";

$result = $conn->query($sql);
$patients = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Wenqiang Jin">
  <meta name="description" content="Professional of COMP9030_CaRe_Groups6">
  <title>Professional Staff Dashboard</title>
  <link rel="stylesheet" href="../style/global.css">
  <link rel="stylesheet" href="../style/professionalDashboard.css">
</head>

<body class="therapistBody">
  <!-- global navigation bar -->
  <header class="navbar">
    <a href="professionalDashboard.php"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    <div class="logout-container">
      <a href="../logout.php" class="logout-link">Log-out</a>
    </div>
  </header>

  <div class="professionalDashboard">
    <div class="left-panel">
      <h1>G'Day  <?php echo htmlspecialchars($full_name); ?>!</h1>

      <div id="therapistStatistics">
        <h3>Patient Schedule</h3>
      </div>
    </div>
    <div class="right-panel">
      <div id="global_journalList">
        <h3>Patient Database</h3>
        <!-- <div class="searchPannel">
          <form class="search-bar">
            <input type="text" placeholder="Search..." name="search" />
            <button type="submit">Search</button>
          </form>

        </div> -->
        <div class="tableContainer">
          <!-- click journal title will open that journal in new page -->
          <table class="global_journalLists-table">
            <thead>
              <tr>
                <th class="sortable" onclick="sortTable('patient_id')">Patient ID</th>
                <th class="sortable" onclick="sortTable('age')">Age</th>
                <th class="sortable" onclick="filterByGender()">Gender</th>
                <th class="sortable" onclick="sortTable('height')">Height</th>
                <th class="sortable" onclick="sortTable('weight')">Weight</th>
                <th>Therapist</th>
              </tr>
            </thead>
            <tbody id="patientTableBody">
              <?php foreach ($patients as $patient) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($patient['patient_id']); ?></td>
                  <td><?php echo htmlspecialchars($patient['age']); ?></td>
                  <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                  <td><?php echo htmlspecialchars($patient['height']); ?></td>
                  <td><?php echo htmlspecialchars($patient['weight']); ?></td>
                  <td><?php echo htmlspecialchars($patient['therapist_name']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <footer class="site-footer">
    <p>&copy; 2024 CaRe | All Rights Reserved</p>
  </footer>
  <script src="../scripts/professional.js"></script>
</body>

</html>