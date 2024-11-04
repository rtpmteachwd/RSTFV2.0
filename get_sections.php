<?php
header('Content-Type: application/json');

$grade_id = $_GET['grade_level_id'];
$connection = new mysqli("localhost", "root", "", "spt");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query = "SELECT id, section_name FROM sections WHERE grade_level_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $grade_id);
$stmt->execute();
$result = $stmt->get_result();

$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = [
        'id' => $row['id'],
        'section_name' => $row['section_name']
    ];
}

echo json_encode($sections);

$connection->close();
?>