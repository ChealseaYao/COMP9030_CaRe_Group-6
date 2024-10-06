<?php
session_start(); // 启动会话

// 检查用户是否已登录，并且是therapist角色
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: login.php"); // 未登录则重定向到登录页面
    exit();
}

$user_id = $_SESSION['user_id']; // 获取当前登录的用户ID

// 连接数据库
include '../inc/dbconn.inc.php'; // 请确保该路径指向您的数据库连接文件

// 查询therapist表以获取当前登录用户的therapist_id
$query = "SELECT therapist_id FROM therapist WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$therapist = $result->fetch_assoc();

if (!$therapist) {
    echo "Therapist not found.";
    exit();
}

$therapist_id = $therapist['therapist_id']; // 获取当前therapist的therapist_id

// 查询当前therapist的所有患者
$query = "SELECT patient_id, age, user.full_name as name FROM patient 
          INNER JOIN user ON patient.user_id = user.user_id 
          WHERE patient.therapist_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$result = $stmt->get_result();

// 将患者数据存储到一个数组
$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Patient List</title>
    <link rel="stylesheet" href="../style/global.css" />
    <link rel="stylesheet" href="../style/patientList.css" />
</head>
<body class="patientList-body">
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.html">
            <img src="../image/logo.png" alt="Logo Icon" id="logo-icon" />
        </a>
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
                <form class="search-bar" method="GET" action="">
                    <input type="text" placeholder="Search..." name="search" />
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="tableContainer">
                <!-- 动态展示患者数据 -->
                <?php if (!empty($patients)): ?>
                    <?php foreach ($patients as $patient): ?>
                        <div class="patient-item" data-patient-id="<?php echo $patient['patient_id']; ?>">
                            <div class="left-section">
                                <div class="patient-icon">☰</div>
                                <div>
                                    <strong><?php echo htmlspecialchars($patient['name']); ?></strong><br />
                                    Age: <?php echo htmlspecialchars($patient['age']); ?>
                                </div>
                            </div>
                            <div class="right-section">
                                <div class="status-container"></div>
                                <a href="patientDetail.php?patient_id=<?php echo $patient['patient_id']; ?>">
                                    <button class="details">Details</button>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No patients found for this therapist.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 剩余部分保持不变 -->
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
                <div class="group-item">Revengers</div>
                <div class="group-item">Justice League</div>
            </div>
            <h3>Members</h3>
            <div class="members">
                <p id="currentGroupName">Group Name</p>
                <div id="membersContainer" class="tableContainer">
                    <!-- Dynamic members list -->
                </div>
            </div>
        </div>

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

    <script src="../scripts/createNewModal.js"></script>
    <script src="../scripts/groupSelection.js"></script>
    <script src="../scripts/memberDeletion.js"></script>
    <script src="../scripts/drag&drop.js"></script>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>
</html>
