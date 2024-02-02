<?php
// Example of database connection
$servername = "sql209.infinityfree.com";
$username = "if0_35847501";
$password = "vTgU5zD3d405Ka8";
$database = "if0_35847501_contentlibrary";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['topic'])) {
    $searchTerm = $_GET['topic'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM fileuploads";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = array();

    while ($row = $result->fetch_assoc()) {
        // Case-insensitive comparison for topics
        if (stripos($row['topic'], $searchTerm) !== false) {
            $searchResults[] = array(
                'documentId' => $row['id'],
                'topic' => $row['topic'],
                'uploadedBy' => $row['pseudoname'],
                'uploadedOn' => $row['upload_time'],
                'filePath' => $row['file_path'],
                'grade' => $row['grade'],
                'likes' => $row['likes'],  
                // Added likes count to the result
            );
        }
    }

    $stmt->close();
    $conn->close();

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($searchResults);
} else {
    // Handle invalid or missing search term
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid or missing search term'));
}
?>
