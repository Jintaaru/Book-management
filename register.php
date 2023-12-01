<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code here
    // For example, using mysqli:
    $db = new mysqli("localhost", "root", "", "book_management");

    // Check if the database connection was successful
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Retrieve user input from the registration form
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password for security

    // Insert the user data into the "users" table
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $db->prepare($sql); 

    
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful. <a href='login.html'>Login here</a>";
        } else {
            // Registration failed
            echo "Registration failed. Please try again.";
        }

        $stmt->close();
    } else {
        // Error in the SQL statement
        echo "Error in the SQL statement.";
    }

    $db->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="Header">
        <a href="index.html"><img id="logo" src="bilder/books-high-resolution-logo (1).png" width="50px"></a>
        <div id="Navbar">
            <a href="index.html">Home</a>
            <a href="dashboard.php">Books</a>
            <a href="about.html">About</a>
            <a href="Contact.html">Contact</a>
            <a href="login.html"><i class="fa-sharp fa-regular fa-right-to-bracket" id="login-icon"></i></i></a>
  
           
        </div>
    </div>
    <br> <br> <br>
<br>
<br>
    <div id="heleboksen">
       
        <div class="mini-boks">
            <div id="image">
                <img src="bilder/25ibez24.bmp" alt="" width="50px">
                <div id="sign-in">
                    <p>Register</>
                </div>
            </div>
            <div id="login-input">
                <form action="register.php" class="userpass" method="post">
                    Username: <input class="username" type="text" name="username" required>
                    Password: <input class="password" type="password" name="password" required>
                    <input class="login-button" type="submit" value="Register">
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://kit.fontawesome.com/c09eeab3f2.js" crossorigin="anonymous"></script>
</html>