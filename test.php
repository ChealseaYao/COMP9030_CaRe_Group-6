<!DOCTYPE html>
<html lang="en">
<head>
    <title>Database Connection Test</title>
    <meta charset="UTF-8" />
</head>
<body>
    <h1>Database Connection Test</h1>
    <?php
    require_once "inc/dbconn.inc.php";
    
    $sql = "SELECT p.patient_id, u.full_name AS patient_name, p.email, p.badge 
            FROM patient p 
            JOIN user u ON p.user_id = u.user_id;";
    
    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            echo "<h2>Patient List:</h2>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Badge</th></tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['patient_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['badge']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            mysqli_free_result($result);
        } else {
            echo "<p>No data found in the patient table.</p>";
        }
    } else {
        echo "<p>Error executing query: " . mysqli_error($conn) . "</p>";
    }

    echo "<h2>Therapist Information</h2>";
    $therapist_sql = "SELECT t.therapist_id, u.full_name AS therapist_name, t.therapist_title 
                      FROM therapist t 
                      JOIN user u ON t.user_id = u.user_id;";
    
    if ($therapist_result = mysqli_query($conn, $therapist_sql)) {
        if (mysqli_num_rows($therapist_result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Title</th></tr>";
            
            while ($row = mysqli_fetch_assoc($therapist_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['therapist_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['therapist_title']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            mysqli_free_result($therapist_result);
        } else {
            echo "<p>No therapists found.</p>";
        }
    } else {
        echo "<p>Error retrieving therapist data: " . mysqli_error($conn) . "</p>";
    }

    echo "<h2>Consultation Records</h2>";
    $consultation_sql = "SELECT c.consultation_id, c.consultation_date, c.duration_minutes, c.case_type, 
                                c.therapist_id, c.patient_id 
                         FROM consultation c;";
    
    if ($consultation_result = mysqli_query($conn, $consultation_sql)) {
        if (mysqli_num_rows($consultation_result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Date</th><th>Duration (mins)</th><th>Case Type</th><th>Therapist ID</th><th>Patient ID</th></tr>";
            
            while ($row = mysqli_fetch_assoc($consultation_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['consultation_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['consultation_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['duration_minutes']) . "</td>";
                echo "<td>" . htmlspecialchars($row['case_type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['therapist_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['patient_id']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            mysqli_free_result($consultation_result);
        } else {
            echo "<p>No consultation records found.</p>";
        }
    } else {
        echo "<p>Error retrieving consultation data: " . mysqli_error($conn) . "</p>";
    }

    echo "<h2>Group Information</h2>";
    $group_sql = "SELECT g.group_id, g.group_name, g.therapist_id 
                  FROM `group` g;";
    
    if ($group_result = mysqli_query($conn, $group_sql)) {
        if (mysqli_num_rows($group_result) > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Group Name</th><th>Therapist ID</th></tr>";
            
            while ($row = mysqli_fetch_assoc($group_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['group_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['group_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['therapist_id']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            mysqli_free_result($group_result);
        } else {
            echo "<p>No groups found.</p>";
        }
    } else {
        echo "<p>Error retrieving group data: " . mysqli_error($conn) . "</p>";
    }
    
    mysqli_close($conn);
    ?>
</body>
</html>
