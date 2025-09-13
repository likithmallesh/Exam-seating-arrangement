<?php
include "../db.php";
session_start();

if(isset($_POST['addstudent'])){
    $name = mysqli_real_escape_string($conn, htmlentities($_POST['sname']));
    $password = mysqli_real_escape_string($conn, htmlentities($_POST['spwd']));
    $class = mysqli_real_escape_string($conn, htmlentities($_POST['sclass']));
    $roll = mysqli_real_escape_string($conn, htmlentities($_POST['sroll']));
    $email = mysqli_real_escape_string($conn, htmlentities($_POST['semail']));
    $check_query = "SELECT * FROM students WHERE (rollno = '$roll' OR email = '$email') AND class = '$class'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        $existing_student = mysqli_fetch_assoc($check_result);
         if ($existing_student['rollno'] == $roll) {
            $_SESSION['studentnot'] = "Error!! This roll number is already added.";
        } else if ($existing_student['email'] == $email) {
            $_SESSION['studentnot'] = "Error!! This email is already added.";
        }

        header("Location: add_student.php");
        exit();
    } else {
        $insert = "INSERT INTO students(name, password, class, rollno, email) 
                   VALUES ('$name', '$password', '$class', '$roll', '$email')";
        $insert_query = mysqli_query($conn, $insert);

        if($insert_query){
            $_SESSION['student'] = "New student added successfully.";
        } else {
            $_SESSION['studentnot'] = "Error!! New student not added.";
        }

        header("Location: add_student.php");
        exit();
    }
}
?>
