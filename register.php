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
        <a href="index.html"><img id="logo" src="images/books-high-resolution-logo.png" width="50px"></a>
        <div id="Navbar">
            <a href="index.html">Home</a>
            <a href="dashboard.php">Books</a>
            <a href="Contact.html">Contact</a>
            <a href="login.php"><i class="fa-sharp fa-regular fa-right-to-bracket" id="login-icon"></i></a>


        </div>
    </div>
    <br> <br> <br>
    <br>
    <br>
    <div id="heleboksen">

        <div class="mini-boks">
            <div id="image">
                <img src="images/25ibez24.bmp" alt="" width="50px">
                <div id="sign-in">
                    <p>Register</>
                </div>
            </div>
            <div id="login-input">
                <form action="register.php" class="userpass" method="post">
                    Username: <input class="username" type="text" name="username" required>
                    Email: <br> <input class="Email" type="email" name="email" required>
                    <br>
                    Password: <input class="password" type="password" name="password" required>
                    <input class="login-button" type="submit" value="Register">
                </form>
                <button class="login-button-3"><a href="login.php">Login</a></button>
            </div>
        </div>
    </div>

    <?php
    // Include your database connection code here
    $db = new mysqli("localhost", "root", "", "book_management");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = $_POST["email"];

        // Check if the email is unique before inserting into the database
        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $checkEmailStmt = $db->prepare($checkEmailQuery);
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkEmailResult->num_rows > 0) {
            echo "Email already exists. Please use a different email.";
        } else {
            // Insert user data into the database
            $insertQuery = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $insertStmt = $db->prepare($insertQuery);

            if ($insertStmt === false) {
                die('Error in prepare(): ' . $db->error);
            }

            $insertStmt->bind_param("sss", $username, $password, $email);
            $insertResult = $insertStmt->execute();

            if ($insertResult) {
                echo "Registration successful. You can now log in.";
                // Add code to send a confirmation email with a unique link
                // Generate a unique confirmation token
                $confirmationToken = bin2hex(random_bytes(32));

                // Store the token in the database along with the user's email
                $insertTokenQuery = "UPDATE users SET token = ? WHERE email = ?";
                $insertTokenStmt = $db->prepare($insertTokenQuery);

                if ($insertTokenStmt === false) {
                    die('Error in prepare(): ' . $db->error);
                }

                $insertTokenStmt->bind_param("ss", $confirmationToken, $userEmail);
                $insertTokenStmt->execute();
            } else {
                echo "Error registering user. Please try again.";
            }

            $insertStmt->close();
        }

        $checkEmailStmt->close();
        $db->close();
    }
    ?>


</body>
<script src="https://kit.fontawesome.com/c09eeab3f2.js" crossorigin="anonymous"></script>

</html>