<?php
include '../db.php';
include 'seat_allocation.php'; 
session_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Allocation</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php'; ?>
    <style>
    .seat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 20px;
    justify-items: center;
    }

    .seat-box {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
        text-align: center;
        width: 150px;
        transition: transform 0.3s ease; 
    }

    .seat-box p {
        margin: 5px 0;
    }

    .seat-box:hover {
        transform: scale(1.1); 
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
            <li><a href="add_class.php"><img src="https://img.icons8.com/windows/28/ffffff/google-classroom.png"/> Classes</a></li>
            <li><a href="add_student.php"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a></li>
            <li><a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a></li>
            <li><a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a></li>
            <li><a href="examseat.php" class="active_link"><img width="30" height="30" src="https://img.icons8.com/ios-filled/50/FFFFFF/shuffle.png"/> View Allotment</a></li>
            <li><a href="generate.php"><img  width="30" height="30"src="https://img.icons8.com/ios/50/ffffff/email-open.png"/> Generate</a></li>

        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button>
                <span class="page-name"> View Allotment</span>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="d-flex justify-content-center" style="margin-top: 20px;">
            <div class="table-responsive border" style="width: auto;">
                <form method="POST" action="">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">Select Room</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="py-3 bg-light">
                                <select id="sem" name="sclass" class="form-control" style="display: inline-block; width: auto;" onchange="this.form.submit()">
                                    <?php
                                    $selectroom = "SELECT rid, room_no, floor FROM room";
                                    $selectroomQuery = mysqli_query($conn, $selectroom);

                                    if ($selectroomQuery) {
                                        echo "<option value=''>--Select--</option>";
                                        while ($row = mysqli_fetch_assoc($selectroomQuery)) {
                                            $selected = isset($_POST['sclass']) && $_POST['sclass'] == $row['rid'] ? 'selected' : '';
                                            echo "<option value='" . $row['rid'] . "' $selected>Room-" . $row['room_no'] . " & Floor-" . $row['floor'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

        <div class="seating-container">
            <?php
            if (isset($_POST['sclass']) && !empty($_POST['sclass'])) {
                $selected_room_id = $_POST['sclass'];
                $selected_room_id = mysqli_real_escape_string($conn, $selected_room_id);
				echo "<script>console.log('PHP Variable: " . addslashes($selected_room_id) . "');</script>";
                $allocation_message = allocate_seats($selected_room_id);  
                echo "<p>$allocation_message</p>";
                $room_table_name = $selected_room_id;
                $roomRecordsQuery = "SELECT * FROM `$room_table_name` ORDER BY seatno ASC";  
                $roomRecordsResult = mysqli_query($conn, $roomRecordsQuery);

              if ($roomRecordsResult && mysqli_num_rows($roomRecordsResult) > 0) {
                    echo "<div class='seat-grid'>"; 

                    while ($record = mysqli_fetch_assoc($roomRecordsResult)) {
                        echo "<div class='seat-box'>"; 
                        echo "<p>Seat No: " . $record['seatno'] . "</p>";
                        echo "<p>Name: " . $record['name'] . "</p>";
                        echo "<p>Roll No: " . $record['rollno'] . "</p>";
                        echo "</div>";  
                    }
                    echo "</div>";  
                } else {
                    echo "<p>No records found for the selected room.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php include'footer.php' ?>
