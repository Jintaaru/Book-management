<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $title = $_POST["title"];
    $author = $_POST["author"];

    // Check if the book belongs to the logged-in user
    $sql = "UPDATE books SET title = ?, author = ? WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die('Error in prepare(): ' . $db->error);
    }

    $stmt->bind_param("ssii", $title, $author, $id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Book updated successfully
        header("Location: dashboard.php");
    } else {
        echo "You don't have permission to update this book.";
    }

    $stmt->close();
    $db->close();
} else {
    echo "Invalid request.";
}
?>
