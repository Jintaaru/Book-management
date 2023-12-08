<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="header.css">
    <script src="https://kit.fontawesome.com/c09eeab3f2.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="Header">
        <a href="index.html"><img id="logo" src="images\books-high-resolution-logo.png" width="50px"></a>
        <div id="Navbar">
            <a href="index.html">Home</a>
            <a href="dashboard.php">Books</a>
            <a href="Contact.html">Contact</a>
            <a href="admin_dashboard.php">Admin</a>
            <a href="logout.php"><i class="fa-sharp fa-regular fa-right-to-bracket" id="login-icon"></i></i></a>


        </div>

    </div>
    <br>
    <br>
    <br>

    <h2>Admin Dashboard</h2>
    <!-- Display Users -->
    <?php
    // Include your database connection code here
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Function to fetch all users
    function getUsers($db)
    {
        $sql = "SELECT id, email, username FROM users";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // Fetch all users
    $users = getUsers($db);

    // Display users
    echo '<h3>Users:</h3>';
    echo !empty($users) ? '<ul>' : 'No users found.';

    foreach ($users as $user) {
        echo '<li>ID: ' . $user['id'] . ', Username: ' . $user['username'] . ', Email: ' . $user['email'] . '</li>';
    }

    echo !empty($users) ? '</ul>' : '';

    ?>

    <!-- Add User Form -->
    <h3>Add User:</h3>
    <form method="post">
        <label for="new_username">Username:</label>
        <input type="text" name="new_username" required>
        <label for="new_password">Password:</label>
        <input type="password" name="new_password" required>
        <label for="new_email">Email:</label>
        <input type="email" name="new_email" required>
        <input type="submit" name="add_user" value="Add User">
    </form>

    <!-- Delete User Form -->
    <h3>Delete User:</h3>
    <form method="post">
        <label for="user_id_to_delete">User ID to Delete:</label>
        <input type="number" name="user_id_to_delete" required>
        <input type="submit" name="delete_user" value="Delete User">
    </form>

    <?php

    // Function to add a new user
    function addUser($db, $username, $password, $email)
    {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            $stmt->execute();
            $stmt->close();
            echo "User added successfully!";
        } else {
            echo "Error adding user.";
        }
    }

    // Function to delete a user
    function deleteUser($db, $user_id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
            echo "User deleted successfully!";
        } else {
            echo "Error deleting user.";
        }
    }

    // Handle form submissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["add_user"])) {
            $newUsername = $_POST["new_username"];
            $newPassword = $_POST["new_password"];
            $newEmail = $_POST["new_email"];
            addUser($db, $newUsername, $newPassword, $newEmail);
        } elseif (isset($_POST["delete_user"])) {
            $userIdToDelete = $_POST["user_id_to_delete"];
            deleteUser($db, $userIdToDelete);
        }
    }

    // Fetch all users
    $users = getUsers($db);
    $db->close(); // Close the database connection after use
    ?>

</body>

</html>