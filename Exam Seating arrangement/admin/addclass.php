<?php
include '../db.php';
session_start();

if(isset($_POST['addclass'])){
	$year = $_POST['year'];
	$year = mysqli_real_escape_string($conn, $year);
	$year = htmlentities($year);
	$dept = $_POST['dept'];
	$dept = mysqli_real_escape_string($conn, $dept);
	$dept = htmlentities($dept);
	$div = $_POST['div'];
	$div = mysqli_real_escape_string($conn, $div);
	$div = htmlentities($div);
		$check_query = "SELECT * FROM class WHERE year='$year' AND dept='$dept' AND division='$div'";
	$check_result = mysqli_query($conn, $check_query);

	if(mysqli_num_rows($check_result) > 0){
		$_SESSION['classnot'] = "Error!! Class with the same year, department, and division already exists.";
	} else {
		$insert = "INSERT INTO class (year, dept, division) VALUES ('$year', '$dept', '$div')";
		$insert_query = mysqli_query($conn, $insert);
		if($insert_query){
			$_SESSION['class'] = "New class added successfully.";
		} else {
			$_SESSION['classnot'] = "Error!! New class not added.";
		}
	}

	header("Location: add_class.php");
}
?>
