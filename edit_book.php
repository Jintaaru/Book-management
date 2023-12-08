<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM books WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Error in prepare(): ' . $db->error);
    }

    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();

    if ($book) {
        // Display the edit form with the book data
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Book</title>
            <link rel="stylesheet" href="your_stylesheet.css"> <!-- Replace with your stylesheet link -->
        </head>

        <body>

            <h2>Edit Book</h2>
            <form method="post" action="update_book.php">
                <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                Title: <input type="text" name="title" value="<?php echo $book['title']; ?>" required><br>
                Author: <input type="text" name="author" value="<?php echo $book['author']; ?>" required><br>
                <input type="submit" value="Save Changes">
            </form>

        </body>

        </html>

        <?php
    } else {
        echo "You don't have permission to edit this book.";
    }

    $db->close();
} else {
    echo "Invalid request.";
}
?>
