<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Form</title>
    <link rel="stylesheet" href="password.css">
</head>

<body>
  
      
    </h2>
    <form method="post" action="update_password.php">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <button type="submit">Update Password</button>
    </form>
    <?php
    session_start();

    if (!isset($_SESSION['reset_username']) || !isset($_SESSION['reset_email'])) {
        header("Location: reset_password.php");
        exit;
    }

    $username = $_SESSION['reset_username'];
    $email = $_SESSION['reset_email'];

    // Clear the stored data
    unset($_SESSION['reset_username']);
    unset($_SESSION['reset_email']);
    ?>
</body>

</html>