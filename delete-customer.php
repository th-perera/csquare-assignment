<?php 
include('config/dbcon.php'); 

if (isset($_POST['delete-btn'])) {

    $id = $_POST['id'];
    
    $query = "DELETE FROM customer WHERE id = '$id'";
    
    if(mysqli_query($conn, $query)) {
            echo "deleted";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        };  
}


?>