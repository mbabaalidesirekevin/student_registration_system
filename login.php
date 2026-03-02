<?php
session_start();
include 'connect.php'; // Make sure $con is defined in this file

// Validate input only if both fields are set
if (isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    // Redirect if either field is empty
    if (empty($username) || empty($password)) {
        
        header('Location: login.php');
        exit();
    }

    // Build and run the query
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($con, $sql);

    // Check for query failure
    if (!$result) {
        die("SQL Error: " . mysqli_error($con));
    }

    // Check if exactly one matching user was found
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Double-check credentials (optional since SQL already filtered)
        if ($row['username'] === $username && $row['password'] === $password) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header('Location: students.php');
            exit();
        } else {
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: admin.php?error=1');
        exit();
    }
} else {
    // If POST data isn't set, do nothing
}
?>
