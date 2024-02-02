<?php
// Replace these values with your actual database credentials
$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tutorname = $_POST["tutorname"];
    $pseudoname = $_POST["pseudoname"];
    $employeeID = $_POST["employeeID"];
    $mailID = $_POST["mailID"];
    $password = $_POST["password"];

    $hashed_password = hash("sha256", $password);

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $profileImageTmpPath = $_FILES['profile_image']['tmp_name'];
        $profileImageName = $_FILES['profile_image']['name'];
        $profileImagePath = "uploads/" . $profileImageName; 
        move_uploaded_file($profileImageTmpPath, $profileImagePath);
    } else {
        $profileImagePath = "uploads/default_profile_image.jpg";

    }

    $stmt = $conn->prepare("INSERT INTO tutordetails (tutorname, pseudoname, employeeID, mailID, hashed_password, profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssisss", $tutorname, $pseudoname, $employeeID, $mailID, $hashed_password, $profileImagePath);

        if ($stmt->execute()) {
            header("Location: index.html");
            exit();
        } else {
            // Error in registration
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Error in prepared statement
        echo "Error in prepared statement: " . $conn->error;
    }
}

$conn->close();
?>
