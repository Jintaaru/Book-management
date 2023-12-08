
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Include your database connection directly
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Update the user's password in the database
    $stmt = $db->prepare('UPDATE users SET password = ? WHERE username = ? AND email = ?');
    if (!$stmt) {
        die("Error in statement preparation: " . $db->error);
    }

    $stmt->bind_param('sss', $new_password, $_SESSION['reset_username'], $_SESSION['reset_email']);
    $stmt_result = $stmt->execute();

    if (!$stmt_result) {
        die("Error executing update query: " . $stmt->error);
    }

    echo 'Password updated successfully. ';
    echo '<a href="login">';

    $stmt->close();
    $db->close();
} else {
    echo 'Invalid request.';
}
?>

 <a href="login.php">Go back</a>

    
</body>
</html>
