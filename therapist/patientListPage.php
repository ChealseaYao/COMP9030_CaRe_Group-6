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
        <!-- Fetch and Display Patients Dynamically -->
        <?php
          // Database connection
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "caredb"; // Replace with your actual database name

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Query to get patient data with names
          $sql = "SELECT patient.age, patient.badge, user.full_name 
                  FROM patient 
                  JOIN user ON patient.user_id = user.user_id";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  // Output HTML for each patient
                  echo '<div class="patient-item">';
                  echo '<div class="left-section">';
                  echo '<div class="patient-icon">☰</div>';
                  echo '<div>';
                  echo '<strong>' . htmlspecialchars($row['full_name']) . '</strong><br>';
                  echo 'Age: ' . htmlspecialchars($row['age']);
                  echo '</div>';
                  echo '</div>';
                  echo '<div class="right-section">';
                  echo '<div class="status-container">';

                  // Determine the badge status and apply the appropriate class
                  $statusClass = '';
                  if ($row['badge'] === 'good status') {
                      $statusClass = 'green';
                  } elseif ($row['badge'] === 'bad status') {
                      $statusClass = 'yellow';
                  } elseif ($row['badge'] === 'danger status') {
                      $statusClass = 'red';
                  }

                  echo '<span class="status ' . $statusClass . '"></span>';
                  echo '</div>';
                  echo '<a href="patientDetail.html"><button class="details">Details</button></a>';
                  echo '</div>';
                  echo '</div>';
              }
          } else {
              echo "<p>No patient found.</p>";
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
