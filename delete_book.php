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
    $sql = "SELECT user_id FROM books WHERE id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Error in prepare(): ' . $db->error);
    }

    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($book_user_id);
    $stmt->fetch();
    $stmt->close();

    if ($book_user_id == $user_id) {
        // Book belongs to the logged-in user, proceed with deletion
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            die('Error in prepare(): ' . $db->error);
        }

        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        echo "Book deleted successfully. <a href='dashboard.php'>Go back to dashboard</a>";
    } else {
        echo "You don't have permission to delete this book.";
    }

    $db->close();
} else {
    echo "Invalid request.";
}
?>
