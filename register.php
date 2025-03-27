<?php
header("Content-Type: application/json");

// Database Connection
$conn = new mysqli("localhost", "root", "", "school_admission");

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "Error", "message" => "Database connection failed!", "type" => "error"]);
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : '';
    $child_name = isset($_POST['child_name']) ? trim($_POST['child_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';

    // Validate input fields
    if (empty($parent_id) || empty($child_name) || empty($email) || empty($address)) {
        echo json_encode(["status" => "Warning", "message" => "All fields are required.", "type" => "warning"]);
        exit();
    }

    // Prevent SQL injection
    $parent_id = $conn->real_escape_string($parent_id);
    $child_name = $conn->real_escape_string($child_name);
    $email = $conn->real_escape_string($email);
    $address = $conn->real_escape_string($address);

    // Check if Parent ID and Child Name already exist
    $check_query = "SELECT * FROM parents WHERE parent_id = '$parent_id' AND child_name = '$child_name'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // If record exists, update it
        $update_query = "UPDATE parents SET email = '$email', address = '$address' WHERE parent_id = '$parent_id' AND child_name = '$child_name'";
        if ($conn->query($update_query) === TRUE) {
            echo json_encode(["status" => "Success", "message" => "Record updated successfully.", "type" => "success"]);
        } else {
            echo json_encode(["status" => "Error", "message" => "Failed to update record.", "type" => "error"]);
        }
    } else {
        // If record does not exist, insert a new one
        $insert_query = "INSERT INTO parents (parent_id, child_name, email, address) VALUES ('$parent_id', '$child_name', '$email', '$address')";
        if ($conn->query($insert_query) === TRUE) {
            echo json_encode(["status" => "Success", "message" => "Registration successful!", "type" => "success"]);
        } else {
            echo json_encode(["status" => "Error", "message" => "Failed to register.", "type" => "error"]);
        }
    }
}

// Close connection
$conn->close();
?>
