<?php
include '../db.php';
session_start();

if (isset($_POST['addallotment'])) {
    $roomno = mysqli_real_escape_string($conn, htmlentities($_POST['room']));
    $class = mysqli_real_escape_string($conn, htmlentities($_POST['class']));
    $start = (int) mysqli_real_escape_string($conn, htmlentities($_POST['start']));
    $end = (int) mysqli_real_escape_string($conn, htmlentities($_POST['end']));
	$seatno=0;
   
    // Calculate the number of students being allotted
    if($end!=0){
	$total_students = $end - $start + 1;
	}
	else{
	$total_students =1;
	$end=$start;
	}

    // Step 1: Check room vacancy
    $vacancy_query = "SELECT vacancy FROM room WHERE rid = '$roomno'";
    $vacancy_result = mysqli_query($conn, $vacancy_query);
    if ($vacancy_result) {
        $vacancy_data = mysqli_fetch_assoc($vacancy_result);
        $current_vacancy = (int) $vacancy_data['vacancy'];

        // If the vacancy is less than the number of students, do not proceed with the allotment
        if ($current_vacancy < $total_students) {
			
            $_SESSION['batchnot'] = "Error!! Not enough vacancy in room `$current_vacancy`--`$total_students`--'$roomno'. Available seats: $current_vacancy.";
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['batchnot'] = "Error!! Could not fetch room vacancy.";
        header("Location: dashboard.php");
        exit();
    }

    // Step 2: Check for overlapping roll number ranges
    $check_overlap = "
        SELECT COUNT(*) as count 
        FROM batch 
        WHERE class_id = '$class' 
        AND (
            (startno <= $end AND endno >= $start)  -- Overlap condition
        )";
    $overlap_result = mysqli_query($conn, $check_overlap);
    $overlap_count = mysqli_fetch_assoc($overlap_result)['count'];

    if ($overlap_count > 0) {
        $_SESSION['batchnot'] = "Error!! The roll numbers are already allocated.";
        header("Location: dashboard.php");
        exit();
    }

    // Step 3: Check if all students within the roll number range exist in the `students` table
    $fetch_students = "SELECT COUNT(*) as count FROM students WHERE class = '$class' AND rollno BETWEEN $start AND $end";
    $students_result = mysqli_query($conn, $fetch_students);
    $students_count = mysqli_fetch_assoc($students_result)['count'];

    // If the number of students found doesn't match the expected number, stop the process
    if ($students_count < $total_students or $start > $end) {
        $_SESSION['batchnot'] = "Error!! Only $students_count students found in the roll number range $start to $end. Allocation cannot proceed.";
        header("Location: dashboard.php");
        exit();
    }

    // Step 4: Insert the new allotment into the batch table
    $insert = "INSERT INTO batch (class_id, room_id, startno, endno) VALUES ('$class', '$roomno', '$start', '$end')";
    $insert_query = mysqli_query($conn, $insert);

    if ($insert_query) {
        // Step 5: Fetch students based on roll number range and class
        $fetch_students = "SELECT student_id, name,email, class, rollno FROM students WHERE class = '$class' AND rollno BETWEEN $start AND $end";
        $result = mysqli_query($conn, $fetch_students);

        if ($result) {
            while ($student = mysqli_fetch_assoc($result)) {
                $student_id = $student['student_id'];
                $name = $student['name'];
                $class = $student['class'];
                $rollno = $student['rollno'];
                $email= $student['email'];

                // Insert students into the dynamically named table (room-specific)
                $insert_students = "INSERT INTO `$roomno` (student_id, name, class, rollno, seatno,email) VALUES ('$student_id', '$name', '$class', '$rollno','$seatno','$email')";
                $insert_students_query = mysqli_query($conn, $insert_students);

                if (!$insert_students_query) {
                    $_SESSION['batchnot'] = "Error!! Some students could not be placed in table '$roomno'.";
                    header("Location: dashboard.php");
                    exit();
                }
            }

            // Step 6: Update room vacancy after allotment
            $new_vacancy = $current_vacancy - $total_students;
            $update_vacancy = "UPDATE room SET vacancy = $new_vacancy WHERE rid = '$roomno'";
            $vacancy_update_query = mysqli_query($conn, $update_vacancy);

            if (!$vacancy_update_query) {
                $_SESSION['batchnot'] = "Error!! Could not update the room vacancy.";
                header("Location: dashboard.php");
                exit();
            }

            $_SESSION['batch'] = "New allotment placed successfully and students assigned. Room vacancy updated.";
        } else {
            $_SESSION['batchnot'] = "Error!! Could not fetch students.";
        }
    } else {
        $_SESSION['batchnot'] = "Error!! New allotment not placed.";
    }
	
    header("Location: dashboard.php");
}
?>
                   