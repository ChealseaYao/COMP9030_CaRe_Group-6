<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Min Yao">
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="style/global.css">
    <!-- <script src="./scripts/login.js"></script> -->
</head>

<body class="login-body">
    <div class="therapistContainer ">
        <div class="login-container">
            <form class="login-form" method="post" action="login.php"  id="loginForm">
                <h2>Login</h2>
                <ul class="login-input-group">
                    <li>Username<input type="text" name="Username" id="Username" required></li>
                    <li>Password <input type="password" name="password" id="password" required></li>
                    <li>
                        Role:
                        <select id="role" name="role">
                            <option value="therapist">Therapist</option>
                            <option value="patient">Patient</option>
                            <option value="professional_staff">Professional Staff</option>
                            <option value="auditor">Auditor</option>

                        </select>
                    </li>
                    <li><button type="submit">Sign in</button></li>
                    <li><a href="" id="forget-password">Forgot password?</a></li>
                    <li id="account"><span>new here?<a href="" id="create-account"> create an account</a></span></li>
                </ul>
                <hr>
                <p class="login-reminder"><input type="checkbox" required> By continuing, you confirm you agree to our
                    <a href="" id="ppcy">Privacy Policy and Terms of Use</a>.</p>
            </form>
        </div>
    </div>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>


    <?php
session_start(); // Start the session

// Include the database configuration file
require_once 'inc/dbconn.inc.php'; // Ensure this path is correct based on your project structure

// Retrieve form data
$username = $_POST['Username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Validate user input
if (empty($username) || empty($password) || empty($role)) {
    // echo "All fields are required.";
    exit();
}

// Check if the user exists with the given role
$query = "SELECT * FROM `user` WHERE username = ? AND role = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $role);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verify the password
    if (password_verify($password, $user['password_hash'])) {
        // Store user information in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];

        // Redirect to the appropriate page based on role
        switch ($role) {
            case 'therapist':
                header("Location: therapist/therapistDashboard.php");
                break;
            case 'patient':
                header("Location: patient/patientDashboard.php");
                break;
            case 'professional_staff':
                header("Location: professional/professionalDashboard.php");
                break;
            case 'auditor':
                header("Location: auditor/auditorDashboard.php");
                break;
            default:
                header("Location: login.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Invalid username or role.'); window.location.href='login.php';</script>";
}

// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

   
   
</body>
<script>

</script>

</html>


