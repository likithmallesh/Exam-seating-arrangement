<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader for PHPMailer
require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seating";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to send room details via email
function sendRoomDetails($email, $name, $room_no, $floor, $seatno) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'seatingexam@gmail.com';
        $mail->Password = 'snbu xnti upka lcma';  // Use your actual email credentials
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('seatingexam@gmail.com', 'Exam Seating');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Seating Details';
        $mail->Body = "Dear $name,<br><br>
                       Your seating details are as follows:<br>
                       Room Number: <b>$room_no</b><br>
                       Floor: <b>$floor</b><br>
                       Seat Number: <b>$seatno</b><br><br>
                       Best Regards,<br>Exam Committee";

        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

// Check if the "Send Seat Details" button is clicked
if (isset($_POST['send_details'])) {
    // Fetch all records from the jeffrin table
    $sql = "SELECT * FROM jeffrin";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Send email to each student
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $room_no = $row['room_no'];
            $floor = $row['floor'];
            $seatno = $row['seatno'];

            sendRoomDetails($email, $name, $room_no, $floor, $seatno);
        }
        echo '<script>alert("email sent");</script>';
    } else {
        echo "<p>No records found in jeffrin table.</p>";
    }
}

?>
<html>  
<head>
    <link rel="stylesheet" href="common.css">
    <title>Generate</title>
    <?php include'../link.php' ?>
    <style>
    .table-container {
        margin-top: 60px; /* Adjust the value to increase or decrease the space */
    }
</style>
</head>
<body>
<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4>DASHBOARD</h4>   
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="add_class.php"><img src="https://img.icons8.com/windows/28/ffffff/google-classroom.png"/> Classes</a>
            </li>
            <li>
                <a href="add_student.php"><img src="https://img.icons8.com/ios-filled/26/ffffff/student-registration.png"/> Students</a>
            </li>
            <li>
               <a href="add_room.php"><img src="https://img.icons8.com/metro/24/ffffff/building.png"/> Rooms</a>
            </li>
            <li>
                <a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
            </li>
            <li>
                <a href="examseat.php"><img width="30" height="30" src="https://img.icons8.com/ios-filled/50/FFFFFF/shuffle.png"/> View Allotment</a>
            </li>
            <li>
                <a href="generate.php" class="active_link"><img  width="30" height="30"src="https://img.icons8.com/ios/50/ffffff/email-open.png"/> Generate</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button>
                <span class="page-name"> Generate</span>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        
                    <li class="nav-item active">
    <form method="POST" class="mr-2"> <!-- Added margin class here -->
        <button type="submit" name="send_details" class="btn btn-info form-control">
            Send Seat Details
        </button>
    </form>
</li>

<li class="nav-item active">
    <a class="nav-link" href="../logout.php">Logout</a>
</li>


                    </ul>
                </div>
            </div>
        </nav>

        <div class="container table-container">
            <?php
            // Truncate the jeffrin table at the start
            $truncate_sql = "TRUNCATE TABLE jeffrin";
            $conn->query($truncate_sql);

            // Step 1: Fetch all distinct room_ids from the batch table
            $sql = "SELECT DISTINCT room_id FROM batch";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Step 2: For each room_id, use it as a table name and fetch records from that table
                while($row = $result->fetch_assoc()) {
                    $room_id = $row['room_id'];

                    // Step 3: Dynamically use the room_id as the table name and fetch records
                    $sql_room = "
                    SELECT r.room_no, r.floor, t.*
                    FROM `$room_id` t
                    JOIN room r ON r.rid = '$room_id' ";
                    $result_room = $conn->query($sql_room);
                    
                    if ($result_room->num_rows > 0) {
                        echo "<div class='table-responsive border'>";
                        echo "<table class='table table-hover text-center'>";
                        echo "<thead class='thead-light'><tr><th>Room & Floor</th><th>Name</th><th>Email</th><th>Seat no</th></tr></thead><tbody>";
                        
                        // Step 4: Display the records from the room_id table
                        while($room_row = $result_room->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>Room-" . $room_row['room_no'] . " & Floor-" . $room_row['floor'] . "</td>";
                            echo "<td>" . $room_row['name'] . "</td>";
                            echo "<td>" . $room_row['email'] . "</td>";
                            echo "<td>" . $room_row['seatno'] . "</td>";
                            echo "</tr>";

                            // Step 5: Insert the fetched records into the jeffrin table
                            $sql_insert = "INSERT INTO jeffrin (name, email, room_no, floor, seatno) 
                                VALUES ('" . $room_row['name'] . "', '" . $room_row['email'] . "', '" . $room_row['room_no'] . "', '" . $room_row['floor'] . "', '" . $room_row['seatno'] . "')";
                            $conn->query($sql_insert);
                        }
                        echo "</tbody></table>";
                        echo "</div>";
                    } else {
                        echo "<p>No records found in the table for Room ID: " . $room_id . "</p>";
                    }
                    echo "<br>";
                }
            } else {
                echo "<p>No room IDs found in the batch table.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>
</div>

</body>
</html>
<?php include'footer.php' ?> 
