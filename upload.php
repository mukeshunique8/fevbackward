<?php
session_start();

$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Access user details from session
$userDetails = $_SESSION["userDetails"];
$employeeID = $userDetails["employeeID"];
$pseudoname = $userDetails["pseudoname"];
$tutorname = $userDetails["tutorname"];
$mailID = $userDetails["mailID"];

// Get input from the form
$grade = $_POST["grade"];
$topic = $_POST["topic"];

// File upload handling
$targetDirectory = "Uploaded_Documents/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDirectory . $fileName;

// Check for file upload errors
if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
    die("File upload failed with error code: " . $_FILES["file"]["error"]);
}

// Move the uploaded file to the target path
if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
    die("File move failed");
}

// Insert data into the fileuploads table using prepared statement
$sql = "INSERT INTO fileuploads (employeeID, pseudoname, tutorname, mailID, grade, topic, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepared statement failed: " . $conn->error);
}

$stmt->bind_param("sssssss", $employeeID, $pseudoname, $tutorname, $mailID, $grade, $topic, $targetFilePath);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$stmt->close();
$conn->close();

header("Location: cntlib.php");
exit();
?>
