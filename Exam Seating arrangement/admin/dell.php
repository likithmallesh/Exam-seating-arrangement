<?php

if (isset($_POST['deleteroom'])) {
    $room = intval($_POST['deleteroom']);  
    mysqli_begin_transaction($conn);

    try {
        $deleteBatch = "DELETE FROM batch WHERE room_id = '$room'";
        $deleteBatchQuery = mysqli_query($conn, $deleteBatch);	  
        $deleteRoom = "DELETE FROM room WHERE rid = '$room'";
        $deleteRoomQuery = mysqli_query($conn, $deleteRoom);
	    $dropRoomTable = "DROP TABLE IF EXISTS `$room`";
	    $dropRoomTableQuery = mysqli_query($conn, $dropRoomTable);
        if ($deleteBatchQuery && $dropRoomTableQuery && $deleteRoomQuery) {
            mysqli_commit($conn);
            $_SESSION['delroom'] = "Room and associated data deleted successfully";
        } else {
            mysqli_rollback($conn);
            $_SESSION['delnotroom'] = "Error! Room deletion failed.";
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['delnotroom'] = "Error! Room deletion failed: " . $e->getMessage();
    }
}
?>
