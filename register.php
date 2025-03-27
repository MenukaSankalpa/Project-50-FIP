<?php

//Database Connection 
$conn = new mysqli("localhost", "root", "", "school_admission");

//check connection
if($conn->connect_error) {
    die("connection field: " . $conn->connect_error);
}




?>