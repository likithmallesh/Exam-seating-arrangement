<?php

$conn = mysqli_connect("localhost","root","","seating");
if($conn){
	// echo "Successfully";
}
else{
    die("no conn" . mysqli_connect_error());
}

?>