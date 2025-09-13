<?php
session_start();
include "db.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = mysqli_real_escape_string($conn, $name);
    $name = htmlentities($name);
    $password = $_POST['password'];
    $password = mysqli_real_escape_string($conn, $password);
    $password = htmlentities($password);

    $select_admin = "SELECT name, password FROM admin WHERE name='$name' AND password='$password'";
    $select_admin_query = mysqli_query($conn, $select_admin);
    if (mysqli_num_rows($select_admin_query) > 0) {
        $_SESSION['login'] = "admin";
        header('Location: admin/dashboard.php');
    } else {
        $_SESSION['loginmsg'] = "Incorrect Credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="flex-r container">
        <div class="flex-r login-wrapper">
            <div class="login-text">
                <div class="logo">
                    <span><i class="fab fa-speakap"></i></span>
                </div>
                <h1 style="text-align: center;">Exam Hall Seating Arrangement</h1>
                <p style="text-align: center;">ADMIN LOGIN</p>

                <div class="login-div">
                    <p class="loginmsg">
                        <?php
                        if (isset($_SESSION['loginmsg'])) {
                            echo $_SESSION['loginmsg'];
                            unset($_SESSION['loginmsg']);
                        }
                        ?>
                    </p>
                </div>

                <form class="flex-c" method="post">
                    <div class="input-box">
                        <span class="label">Name</span>
                        <div class="flex-r input">
                            <input class="name" name="name" type="text" placeholder="Enter Name">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="input-box">
                        <span class="label">Password</span>
                        <div class="flex-r input">
                            <input class="pass" name="password" type="password" placeholder="Password">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <input class="btn" name="submit" type="submit" value="LOGIN">
                    <span class="extra-line">
                        <span>Are you a student?</span>
                        <a href="login_student.php">Login Here</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
