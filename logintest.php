<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Min Yao">
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="style/global.css">
    <!-- <script src="/scripts/login.js"></script> -->
</head>

<body class="login-body">
    <div class="therapistContainer ">
        <div class="login-container">
            <form class="login-form" method="post" id="loginForm" action="logintest.php">
                <h2>Login</h2>
                <ul class="login-input-group">
                    <li>Username<input type="text" name="Username" id="Username" required></li>
                    <li>Password <input type="password" name="password" id="password" required></li>
                    <li>
                        Role:
                        <select id="role" name="role">
                            <option value="therapist">Therapist</option>
                            <option value="patient">Patient</option>
                        </select>
                    </li>
                    <li><button type="submit">Sign in</button></li>
                    <li><a href="" id="forget-password">Forgot password?</a></li>
                    <li id="account"><span>new here?<a href="" id="create-account"> creat an account</a></span></li>
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
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a PHP session
session_start();

// Include the database configuration file
// include 'inc/dbconn.inc.php'; // Make sure this path is correct
include 'inc/dbconn.inc.php';

// Check if database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form submitted via POST method.<br>"; // Check if this line appears

    // Retrieve the submitted form data
    $username = $_POST['Username'] ?? 'N/A';
    $password = $_POST['password'] ?? 'N/A';
    $role = $_POST['role'] ?? 'N/A';

    // Echo the received POST data for testing
    echo "Received POST data:<br>";
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
    echo "Role: " . htmlspecialchars($role) . "<br>";

    // Now, let's proceed with the login logic
    $username = mysqli_real_escape_string($conn, $username);
    $role = mysqli_real_escape_string($conn, $role);

    // Fetch user from the database
    $query = "SELECT * FROM `user` WHERE `username` = '$username' AND `role` = '$role'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['password_hash']; // Retrieve hashed password

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Successful login
            echo "Login successful!<br>";
            
            // Store user info in session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] === 'therapist') {
                header("Location: therapist/therapistDashboard.html");
            } elseif ($user['role'] === 'patient') {
                header("Location: patient/patientDashboard.html");
            } else {
                header("Location: index.html"); // Default redirect
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username or role.";
    }
} else {
    echo "Form was not submitted using POST.";
}
?>

</body>
<script>

</script>

</html>




