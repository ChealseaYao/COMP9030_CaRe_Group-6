<?php
// 启动会话并检查用户是否登录以及角色是否为 therapist
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: login.php");
    exit();
}

// Database connection
include '../inc/dbconn.inc.php'; // 确保路径正确

// 接收 AJAX 请求
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['journal_id']) && isset($data['highlight'])) {
    $journal_id = intval($data['journal_id']);
    $highlight = intval($data['highlight']);

    // 更新指定 journal_id 的 highlight 状态
    if (in_array($highlight, [0, 1])) {
        $sql = "UPDATE journal SET highlight = ? WHERE journal_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $highlight, $journal_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid highlight']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}

$conn->close();
?>
