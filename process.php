<?php
session_start();

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "abc";

// Create connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Prepare statement
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $_POST['username']);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the user from the result
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Check if the input password matches the stored password
        // It's safer to hash passwords. Here we assume the password is stored in plain text for this example
        if ($_POST['password'] === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Store the username in session
            header("Location: success.php");
            exit;
        } else {
            // Handle login failure
            echo "Invalid username or password.";
        }
    } else {
        // Handle the case where no user was found
        echo "User not found.";
        header("Location: form1.php");
        exit; // It's a good practice to exit after a header redirect
    }
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
