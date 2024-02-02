<?php
// get_topics.php
session_start();

$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['partialInput'])) {
    $partialInput = $_GET['partialInput'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT DISTINCT topic FROM fileuploads WHERE topic LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeParam = "%{$partialInput}%";
    $stmt->bind_param("s", $likeParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $topicSuggestions = array();

    while ($row = $result->fetch_assoc()) {
        $topicSuggestions[] = $row['topic'];
    }

    $stmt->close();
    $conn->close();

    // Return the suggestions as JSON
    header('Content-Type: application/json');
    echo json_encode($topicSuggestions);
} else {
    // Handle invalid or missing input
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid or missing input'));
}
?>
