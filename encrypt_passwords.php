
<?php
// Include the database configuration file
include 'inc/dbconn.inc.php'; // Ensure this path is correct based on your project structure

// Fetch all users with their plaintext passwords
$query = "SELECT user_id, password_hash FROM `user`";
$result = mysqli_query($conn, $query);

if ($result) {
    // Loop through each user
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $plain_password = $row['password_hash']; // This column currently contains the plaintext password

        // Hash the plaintext password
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

        // Update the user's password_hash field with the hashed password
        $update_query = "UPDATE `user` SET password_hash = '$hashed_password' WHERE user_id = $user_id";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            echo "Password for user_id $user_id has been successfully hashed.<br>";
        } else {
            echo "Error updating password for user_id $user_id: " . mysqli_error($conn) . "<br>";
        }
    }
} else {
    echo "Error fetching users: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
