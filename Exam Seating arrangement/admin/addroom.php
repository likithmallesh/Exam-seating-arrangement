<?php
include "../db.php";
session_start();

if (isset($_POST['addroom'])) {
    $roomno = mysqli_real_escape_string($conn, htmlentities($_POST['roomno']));
    $floor = mysqli_real_escape_string($conn, htmlentities($_POST['floor']));
    $capacity = (int)mysqli_real_escape_string($conn, htmlentities($_POST['cap']));
    $vacancy = $capacity;
    $check_query = "SELECT * FROM room WHERE room_no = '$roomno' AND floor = '$floor'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['roomnot'] = "Error!! This room is already added.";
        header("Location: add_room.php");
        exit(); 
    } else {
        $insert = "INSERT INTO room (room_no, floor, capacity, vacancy) VALUES ('$roomno', '$floor', '$capacity', '$vacancy')";
        $insert_query = mysqli_query($conn, $insert);

        if ($insert_query) {
            $_SESSION['room'] = "New room added successfully.";
            $jesus = "SELECT rid FROM room WHERE room_no='$roomno' AND floor='$floor'";
            $check_jesus = mysqli_query($conn, $jesus);
            $row = mysqli_fetch_assoc($check_jesus);
            $table_name = $row['rid']; 
            $table_name = mysqli_real_escape_string($conn, $table_name);
            $check_table_query = "SHOW TABLES LIKE '$table_name'";
            $table_exists = mysqli_query($conn, $check_table_query);

            if (mysqli_num_rows($table_exists) == 0) {
                $create_table_query = "
                    CREATE TABLE `$table_name` (
                        `student_id` int(11) NOT NULL,
                        `password` varchar(255) NOT NULL,
                        `name` varchar(100) NOT NULL,
                        `email` varchar(255),
                        `class` int(11) NOT NULL,
                        `rollno` int(11) NOT NULL,
						`seatno` int(11) NOT NULL
                    )
                ";
                mysqli_query($conn, $create_table_query);
            }
        } else {
            $_SESSION['roomnot'] = "Error!! New room not added.";
        }

        header("Location: add_room.php");
        exit(); 
    }
}
?>
