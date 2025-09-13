<?php
include '../db.php';
function execute_query($conn, $query, $params = [], $types = '') {
    $stmt = mysqli_prepare($conn, $query);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
function allocate_seats($room_id) {
    global $conn;
    $room_query = "SELECT capacity FROM room WHERE rid = ?";
    $room_result = execute_query($conn, $room_query, [$room_id], 's');
    
	 $reset_seat_query = "UPDATE `$room_id` SET seatno = 0 WHERE seatno != 0";
    $stmt = mysqli_prepare($conn, $reset_seat_query);
    mysqli_stmt_execute($stmt);
	
	if (!$room_result || mysqli_num_rows($room_result) == 0) {
        return "Room not found or invalid room ID.";
    }

    $room = mysqli_fetch_assoc($room_result);
    $room_capacity = $room['capacity'];
    $class_query = "SELECT DISTINCT class_id FROM batch WHERE room_id = ?";
    $class_result = execute_query($conn, $class_query, [$room_id], 's');

    if (mysqli_num_rows($class_result) == 0) {
        return "No classes found for this room.";
    }
    $students_by_class = [];
    while ($class_row = mysqli_fetch_assoc($class_result)) {
        $class = $class_row['class_id'];
        $students_query = "SELECT * FROM `$room_id` WHERE class = ? AND seatno = 0";
        $students_result = execute_query($conn, $students_query, [$class], 's');
      $students_by_class[$class] = mysqli_fetch_all($students_result, MYSQLI_ASSOC);
    }
    foreach ($students_by_class as &$students) {
        shuffle($students);
    }
    $student_list = [];
    while (!empty($students_by_class)) {
        foreach ($students_by_class as &$students) {
            if (!empty($students)) {
                $student_list[] = array_shift($students); 
            }
        }
        $students_by_class = array_filter($students_by_class);
    }
    $allocated_count = min(count($student_list), $room_capacity);
    for ($i = 0; $i < $allocated_count; $i++) {
        $student = $student_list[$i];
        $student_code = $student['student_id'];
        $seat_number = $i + 1; 
        $update_seat_query = "UPDATE `$room_id` SET seatno = ? WHERE student_id = ?";
        execute_query($conn, $update_seat_query, [$seat_number, $student_code], 'is');
    }

    return "$allocated_count seats allocated successfully!";
}
?>