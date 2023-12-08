<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Add your stylesheets or any other necessary resources here -->
</head>
<body>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $book_id = $_GET["id"];

    // Include your database connection code here
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Check if the book belongs to the logged-in user
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Error in prepare(): ' . $db->error);
    }

    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Book data fetched successfully
        $book = $result->fetch_assoc();
        
        if ($book["user_id"] == $user_id) {
            // Book belongs to the logged-in user, display the edit form

            // Display the form with pre-filled values
            ?>
            <form method="post" action="edit_book_logic.php">
                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                Title: <input type="text" name="title" value="<?php echo $book['title']; ?>" required><br>
                Author: <input type="text" name="author" value="<?php echo $book['author']; ?>" required><br>
                <!-- Add other fields as needed -->

                <input type="submit" value="Save Changes">
            </form>
            <?php
        } else {
            echo "You don't have permission to edit this book.";
        }
    } else {
        echo "Book not found.";
    }

    $stmt->close();
    $db->close();
} else {
    echo "Invalid request.";
}
?>

</body>
</html>
