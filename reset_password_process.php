<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Include your database connection directly
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Check if the username and email match a user in the database
    $stmt = $db->prepare('SELECT id FROM users WHERE username = ? AND email = ?');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, proceed to the password reset form
        $_SESSION['reset_username'] = $username;
        $_SESSION['reset_email'] = $email;

        header("Location: password_reset_form.php");
    } else {
        echo 'Invalid username or email.';
    }

    $stmt->close();
    $db->close();
}
?>
