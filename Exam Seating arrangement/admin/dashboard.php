<?php
session_start();
?>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="common.css">
    <?php include'../link.php' ?>
</head>
<body>
<?php
if (isset($_POST['deletebatch'])) {
    $batch = $_POST['deletebatch'];
    $get_batch_query = "SELECT room_id, startno, endno, class_id, total FROM batch WHERE batch_id = '$batch'";
    $batch_result = mysqli_query($conn, $get_batch_query);

    if ($batch_row = mysqli_fetch_assoc($batch_result)) {
        $room_id = $batch_row['room_id'];    
        $start_roll = $batch_row['startno'];  
        $end_roll = $batch_row['endno'];     
        $class = $batch_row['class_id'];         
        $total_students = $batch_row['total']; 
        $delete_batch_query = "DELETE FROM batch WHERE batch_id = '$batch'";
        $delete_batch_result = mysqli_query($conn, $delete_batch_query);

        if ($delete_batch_result) {
            $update_vacancy_query = "
                UPDATE room 
                SET vacancy = vacancy + $total_students 
                WHERE rid = '$room_id'";
            $update_vacancy_result = mysqli_query($conn, $update_vacancy_query);

            if ($update_vacancy_result) {
                $delete_students_query = "DELETE FROM `$room_id` WHERE rollno BETWEEN '$start_roll' AND '$end_roll' AND class = '$class'";
                $delete_students_result = mysqli_query($conn, $delete_students_query);

                if ($delete_students_result) {
                    $_SESSION['delbatch'] = "Batch and students deleted successfully, vacancy updated.";
                } else {
                    $_SESSION['delnotbatch'] = "Batch deleted, but error occurred while deleting students from the room.";
                }
            } else {
                $_SESSION['delnotbatch'] = "Error updating the room's vacancy.";
            }
        } else {
            $_SESSION['delnotbatch'] = "Error deleting the batch.";
        }
    } else {
        $_SESSION['delnotbatch'] = "Batch not found.";
    }
}
?>


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
                <a href="add_student.php"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a>
            </li>
            <li>
                <a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a>
            </li>
            <li>
                <a href="dashboard.php" class="active_link"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
            </li>
            <li>
                <a href="examseat.php"><img width="30" height="30" src="https://img.icons8.com/ios-filled/50/FFFFFF/shuffle.png"/>View Allotment</a>
            </li>
        </ul>
    </nav>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button><span class="page-name"> Allotment</span>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
        <div class="main-content">
            <?php
            if (isset($_SESSION['batch'])) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>" . $_SESSION['batch'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['batch']);
            }
            if (isset($_SESSION['batchnot'])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . $_SESSION['batchnot'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['batchnot']);
            }

            if (isset($_SESSION['delbatch'])) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>" . $_SESSION['delbatch'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['delbatch']);
            }
            if (isset($_SESSION['delnotbatch'])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . $_SESSION['delnotbatch'] . "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['delnotbatch']);
            }
            ?>
            <div class="table-responsive border">
                <table class="table table-hover text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Room & Floor</th>
                            <th>Class</th>
                            <th>Start Roll No.</th>
                            <th>End Roll No.</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="addallot.php" method="post">
                            <tr>
                                <th class="py-3 bg-light">
                                    <select name="room" class="form-control" required>
                                        <?php
                                        $select_rooms = "SELECT rid, room_no, floor, capacity, sum(total) as filled from batch right JOIN room on batch.room_id=room.rid group by rid";
                                        $select_rooms_query = mysqli_query($conn, $select_rooms);
                                        if (mysqli_num_rows($select_rooms_query) > 0) {
                                            echo "<option>--select--</option> ";
                                            while ($row = mysqli_fetch_assoc($select_rooms_query)) {
                                                if ($row['capacity'] > $row['filled']) {
                                                    echo "<option value=\"" . $row['rid'] . "\">Room-" . $row['room_no'] . " & Floor-" . $row['floor'] . " </option>";
                                                }
                                            }
                                        } else {
                                            echo "<option>No Rooms</option>";
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th class="py-3 bg-light">
                                    <select id="sem" name="class" class="form-control" required>
                                        <?php
                                        $selectclass = "select * from class order by year, dept, division";
                                        $selectclassQuery = mysqli_query($conn, $selectclass);
                                        if ($selectclassQuery) {
                                            echo "<option>--select--</option>";
                                            while ($row = mysqli_fetch_assoc($selectclassQuery)) {
                                                echo "<option value=" . $row['class_id'] . ">" . $row['year'] . " " . $row['dept'] . " " . $row['division'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='No options'>no</option>";
                                        }
                                        ?>
                                    </select>
                                </th>
                                <th class="py-3 bg-light"><input type="number" name="start" class="form-control" size=4 required></th>
                                <th class="py-3 bg-light"><input type="number" name="end" class="form-control" size=4 required></th>
                                <th class="py-3 bg-light"></th>
                                <th class="py-3 bg-light"><button class="btn btn-info form-control" name="addallotment">Add</button></th>
                            </tr>
                        </form>
                        <?php
                        $batch = "SELECT room_id, year, dept, division, startno, endno, total, batch_id, room_no, floor from batch JOIN class on batch.class_id=class.class_id JOIN room on batch.room_id=room.rid order by room_no, year, dept, division";
                        $batch_query = mysqli_query($conn, $batch);
                        if (mysqli_num_rows($batch_query) > 0) {
                            while ($row = mysqli_fetch_assoc($batch_query)) {
                                echo "<tr>";
                                echo "<td>Room-" . $row['room_no'] . " & Floor-" . $row['floor'] . "</td>";
                                echo "<td>" . $row['year'] . " " . $row['dept'] . " " . $row['division'] . "</td>";
                                echo "<td>" . $row['startno'] . "</td>";
                                echo "<td>" . $row['endno'] . "</td>";
                                echo "<td>" . $row['total'] . "</td>";
                                echo "<form method='post'>
                                        <td><button class='btn btn-light px-1 py-0' type='submit' value='".$row['batch_id']."' name='deletebatch'>
                                           <img src='https://img.icons8.com/color/25/000000/delete-forever.png'/>
                                           </button>
                                        </td>
                                     </form>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php include'footer.php' ?>
