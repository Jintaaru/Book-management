<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>


<html>

<body>
    <div class="Header">
        <a href="index.html"><img id="logo" src="images/books-high-resolution-logo.png" width="50px"></a>
        <div id="Navbar">
            <a href="index.html">Home</a>
            <a href="dashboard.php">Books</a>
            <a href="Contact.html">Contact</a>
            <a href="login.php"><i class="fa-sharp fa-regular fa-right-to-bracket" id="login-icon"></i></i></a>


        </div>
    </div>
    <div><br>
        <br>
        <br>
        <br>
        <br>
    </div>
    <div id="heleboksen">

        <div class="mini-boks">
            <div id="image">
                <img src="images/25ibez24.bmp" alt="" width="50px">
                <div id="sign-in">
                    <p>Sign in</>
                </div>
            </div>
            <div id="login-input">
                <form action="login.php" class="userpass" method="post">
                    Username: <input class="username" type="text" name="username" required>
                    Password: <input class="password" type="password" name="password" required>
                    <input class="login-button-2" type="submit" value="Login">
                    <a class="reset"href="reset_password.php">Forgot Password? Reset it here.</a>
                </form>
                <div class="login-register">
                    <a href="register.php">
                        <p>Register here</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    session_start();


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Connecting to database
        $db = new mysqli("localhost", "root", "", "book_management");

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($user_id, $db_username, $db_password, $user_role);

            if ($stmt->fetch() && password_verify($password, $db_password)) {
                // Password is correct, start a session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['role'] = $user_role;

                //  role
                if ($user_role === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
            } else {
                // Login failed
                echo "Login failed. Please check your credentials.";
            }

            $stmt->close();
        } else {
            // Error in the SQL statement
            echo "Error in the SQL statement.";
        }



        $db->close();

    }
    ?>

</body>

<script src="https://kit.fontawesome.com/c09eeab3f2.js" crossorigin="anonymous"></script>

</html>