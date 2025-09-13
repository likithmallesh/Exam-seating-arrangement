<?php 
session_start();
?>
<html>
<head>
    <title>Manage Student</title>
    <link rel="stylesheet" href="common.css">
    <?php include'../link.php' ?>
    <style type="text/css">
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
                    <a href="add_student.php" class="active_link"><img src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png"/> Students</a>
                </li>
                <li>
                    <a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png"/> Rooms</a>
                </li>
                <li>
                    <a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png"/> Allotment</a>
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
                    </button>
                    <span class="page-name"> Manage Students</span>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <img src="https://img.icons8.com/ios-filled/20/ffffff/menu--v3.png"/>
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
                if(isset($_SESSION['student'])){
                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$_SESSION['student']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    unset($_SESSION['student']);
                }
                if(isset($_SESSION['studentnot'])){
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$_SESSION['studentnot']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    unset($_SESSION['studentnot']);
                }
                if(isset($_SESSION['delstudent'])){
                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$_SESSION['delstudent']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    unset($_SESSION['delstudent']);
                }
                if(isset($_SESSION['delnotstudent'])){
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$_SESSION['delnotstudent']."<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                    unset($_SESSION['delnotstudent']);
                }
                ?>
                <div class="table-responsive border">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Roll No.</th>
                                <th>Email</th>
                                <th>Password</th> 
                                <th>Actions</th>
                            </tr>   
                        </thead>
                        <tbody>
                            <tr>
                                <form action="addstudent.php" method="post">
                                    <th class="py-3 bg-light">
                                        <input class="form-control" type="text" name="sname" placeholder="Enter Name" required>
                                    </th>
                                    <th class="py-3 bg-light">
                                        <select id="sem" name="sclass" class="form-control" required>
                                            <?php 
                                            $selectclass = "SELECT * FROM class ORDER BY year, dept, division";
                                            $selectclassQuery = mysqli_query($conn, $selectclass);
                                            if($selectclassQuery){
                                                echo "<option value=''>--select--</option>";
                                                while($row = mysqli_fetch_assoc($selectclassQuery)){
                                                    echo "<option value=".$row['class_id'].">".$row['year']." ".$row['dept']." ".$row['division']."</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No options</option>";
                                            }
                                            ?>
                                        </select>
                                    </th>
                                    <th class="py-3 bg-light">
                                        <input class="form-control" type="number" name="sroll"  required>
                                    </th>
                                    
                                    <th class="py-3 bg-light">
                                        <input class="form-control" type="email" name="semail"  required> 
                                    </th>
                                    <th class="py-3 bg-light">
                                        <input class="form-control" type="password" name="spwd"  required>
                                    </th>
                                    <th class="py-3 bg-light">
                                        <button class="btn btn-info form-control" name="addstudent">Add</button>
                                    </th>
                                </form>
                            </tr>

                            <?php
                            $selectclass = "SELECT * FROM students, class WHERE students.class = class.class_id ORDER BY year, dept, division, rollno,email";
                            $selectclassquery = mysqli_query($conn, $selectclass);
                            if($selectclassquery){
                                while ($row = mysqli_fetch_assoc($selectclassquery)) {
                                    echo "<tr>
                                    <td>".$row['name']."</td>
                                    <td>".$row['year']." ".$row['dept']." ".$row['division']."</td>
                                    <td>".$row['rollno']."</td>
                                    <td>".$row['email']."</td> <!-- Displaying Email -->
                                    <td>-</td>
                                    <td>
                                    </td>
                                    </tr>";
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