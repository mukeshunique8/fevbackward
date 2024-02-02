<?php
session_start(); // Start the session

$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Something Went Wrong" . $conn->connect_error);
}

$hashed_password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeID = $_POST["employeeID"];
    $password = $_POST["password"];
    $hashed_password = hash("sha256", $password);

    $sql = "SELECT * FROM tutordetails WHERE employeeID = '$employeeID' AND hashed_password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $userDetails = $result->fetch_assoc();

        // Store all user details in session
        $_SESSION["userDetails"] = $userDetails;

        header("Location: cntlib.php");
        exit();
    } else {
        echo "Invalid User";
    }
}

$conn->close();
?>
