<?php

//Database Connection 
$conn = new mysqli("localhost", "root", "", "school_admission");

//check connection
if($conn->connect_error) {
    die("connection field: " . $conn->connect_error);
}

//handle from submission 
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = $_POST["parent_id" ];
    $child_name = $_POST["child_name"];
    $email = $_POST["email"];
    $address = $_POST["address"];

    // prevent sql injection
    $parent_id = $conn->real_escape_string($parent_id);
    $child_name = $conn->real_escape_string($child_name);
    $email = $conn->real_escape_string($email);
    $address = $conn->real_escape_string($address);

    //confirm if they exist for the Parent ID and the Name Child
    $check_query = "SELECT * FROM parents WHERE parent_id = '$parent_id' AND child_name = '$child_name' ";
    $result = $conn->query($check_query);

    if($result->num_rows > 0) {
        //record is not there update it
        $update_query = "UPDATE parents SET email='$email', address= '$address' WHERE parent_id = '$parent_id' AND child_name = '$child_name' ";

        if($conn->query($update_query) === TRUE) {
            echo "Record Updated Successfully";
        } else {
            echo "Error Updating Record: " .$conn->error;
        }
    } else {
        //if record is not there insert new one
        $insert_query = "INSERT INTO parents (parent_id, child_name, email, address) VALUES ('$parent_id', '$child_name', '$email', '$address')";
        
        
        if($conn->query($insert_query) ===TRUE ) {
            echo "Registration successfully!" ;
        } else {
            echo "Error: ".$insert_query . "<br>" .$conn->error;
        }
    }

}

//close connection
$conn->close();
?>