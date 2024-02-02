<?php
// get_backward_count.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION["userDetails"])) {
    // Return an error response or handle it based on your requirements
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Access user details
$userDetails = $_SESSION["userDetails"];
$employeeID = $userDetails["employeeID"];

// Connect to your database (replace with your actual database credentials)

$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Perform a database query to get the count of occurrences of the user's employeeID
$sql = "SELECT COUNT(*) AS userCount FROM fileuploads WHERE employeeID = '$employeeID'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $userCount = $row['userCount'];

    // Return the count as JSON
    echo json_encode(["userCount" => $userCount]);
} else {
    // Handle the query error
    echo json_encode(["error" => "Query failed"]);
}

// Close the database connection
$conn->close();
?>
