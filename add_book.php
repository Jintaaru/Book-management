<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Add_book.css">
    <title>Document</title>
</head>

<body>
    
    <!-- add_book.html -->
    <form method="post" enctype="multipart/form-data">
        Title: <input type="text" name="title" required>
        Author: <input type="text" name="author" required>
        Image: <input type="file" name="image" required> <!-- Change the name to "image" -->
        <input type="submit" value="Add Book">
    </form>


    <?php
    session_start();
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include your database connection code here
        // For example, using mysqli:
        $db = new mysqli("localhost", "root", "", "book_management");

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $title = $_POST["title"];
        $author = $_POST["author"];
        $image_path = "images/default.jpg"; // Default image path
    
        if (isset($_FILES["image"])) {
            // Handle image upload
            $target_dir = "images/";
            $image_path = $target_dir . basename($_FILES["image"]["name"]);

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                // Image uploaded successfully
            } else {
                echo "Image upload failed.";
            }
        } else {
            echo "No image uploaded.";
        }

        // Insert book data into the "books" table
        $sql = "INSERT INTO books (title, author, image_path, user_id) VALUES (?, ?, ?,?)";
        $stmt = $db->prepare($sql);

        $user_id = $_SESSION["user_id"];

        if ($stmt) {
            $stmt->bind_param("ssss", $title, $author, $image_path, $user_id);
            if ($stmt->execute()) {
                // Book added successfully
                echo "Book added successfully. <a href='dashboard.php'>Go back to dashboard</a>";
            } else {
                // Book addition failed
                echo "Book addition failed. Please try again.";
            }

            $stmt->close();
        } else {
            // Error in the SQL statement
            echo "Error in the SQL statement.";
        }

        $db->close();
    }
    ?>
    <a href="dashboard.php" class="back">Go back</a>
</body>

</html>