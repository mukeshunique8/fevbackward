<?php
session_start();

if (!isset($_SESSION["userDetails"])) {
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

$userDetails = $_SESSION["userDetails"];
$user_id = $userDetails["employeeID"];

if (!isset($_POST["documentId"]) || !isset($_POST["likeStatus"]) || !isset($_POST["topic"])) {
    echo json_encode(array('error' => 'Missing parameters'));
    exit();
}

$documentId = $_POST["documentId"];
$likeStatus = $_POST["likeStatus"];
$topic = $_POST["topic"];

$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(array('error' => 'Connection failed: ' . $conn->connect_error));
    exit();
}

// Check if the user has already liked the document
$checkLikeQuery = "SELECT * FROM user_likes WHERE user_id = ? AND document_id = ?";
$checkLikeStmt = $conn->prepare($checkLikeQuery);
$checkLikeStmt->bind_param("ii", $user_id, $documentId);
$checkLikeStmt->execute();
$likeResult = $checkLikeStmt->get_result();
$checkLikeStmt->close();

if ($likeResult->num_rows > 0) {
    // User has already liked the document, return an error response
    echo json_encode(array('error' => 'User has already liked this document'));
    exit();
}

// Update the like count for the document
$updateLikeCountQuery = "UPDATE fileuploads SET likes = likes + 1 WHERE id = ?";
$updateLikeCountStmt = $conn->prepare($updateLikeCountQuery);
$updateLikeCountStmt->bind_param("s", $documentId);
$updateLikeCountStmt->execute();
$updateLikeCountStmt->close();

// Get the updated like count for the document
$getLikeCountQuery = "SELECT likes FROM fileuploads WHERE id = ?";
$getLikeCountStmt = $conn->prepare($getLikeCountQuery);
$getLikeCountStmt->bind_param("s", $documentId);
$getLikeCountStmt->execute();
$likeCountResult = $getLikeCountStmt->get_result();
$getLikeCountStmt->close();

if ($likeCountResult->num_rows > 0) {
    $likeCount = $likeCountResult->fetch_assoc()['likes'];

    // Store the user's like status in the user_likes table
    $insertLikeQuery = "INSERT INTO user_likes (user_id, document_id, like_status) VALUES (?, ?, ?)";
    $insertLikeStmt = $conn->prepare($insertLikeQuery);
    $insertLikeStmt->bind_param("iis", $user_id, $documentId, $likeStatus);
    $insertLikeStmt->execute();
    $insertLikeStmt->close();

    // Return a success response with updated like count
    echo json_encode(array('success' => true, 'likes' => $likeCount));
} else {
    echo json_encode(array('error' => 'Failed to get updated like count'));
}

$conn->close();
?>
