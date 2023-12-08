<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="header.css">

</head>

<body>

    <div class="Header">
        <a href="index.html"><img id="logo" src="images\books-high-resolution-logo.png" width="50px"></a>
        <div id="Navbar">
            <a href="index.html">Home</a>
            <a href="dashboard.php">Books</a>
            <a href="Contact.html">Contact</a>
            <a href="logout.php"><i class="fa-sharp fa-regular fa-right-to-bracket" id="login-icon"></i></i></a>


        </div>

    </div>
    <div><br>
        <br>
        <br>
        <br>
        <br>
    </div>
    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit;
    }

    // Include your database connection code here
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Retrieve books added by the logged-in user
    $sql = "SELECT id, title, author, image_path FROM books WHERE user_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Error in prepare(): ' . $db->error);
    }

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();


        echo '<h2>Your Books:</h2>';

        echo '<div id="book-container">';

        while ($row = $result->fetch_assoc()) {
            echo '<div class="book-box">';
            echo '<h3>' . $row['title'] . '</h3>';
            echo '<p>Author: ' . $row['author'] . '</p>';
            if ($row["image_path"] != "images/default.jpg") {
                echo '<img src="' . $row['image_path'] . '" alt="Book Cover" width="150">';
            }

            // Add "Edit" and "Delete" buttons for each book
            echo '<div class="book-actions">';
            echo '<a href="edit_book.php?id=' . $row['id'] . '">Edit</a>';  // Update here
            echo '<a href="delete_book.php?id=' . $row['id'] . '">Delete</a>';
            echo '</div>';

            echo '</div>';
        }



        // Add the "Add a Book" button in the same row
        echo '<div class="book-box" id="add-book">';
        echo '<a class="addbook" href="add_book.php">Add a Book</a>';
        echo '</div>';
    }
    

    $stmt->close();


    $db->close();
    ?>

    


</body>
<script src="https://kit.fontawesome.com/c09eeab3f2.js" crossorigin="anonymous"></script>

</html>