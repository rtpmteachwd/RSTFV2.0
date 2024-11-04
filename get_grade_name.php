<?php
// get_grade_name.php
header('Content-Type: application/json');

$grade_id = $_GET['grade_id'];
$connection = new mysqli("localhost", "root", "", "spt");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query = "SELECT grade_level FROM grade_levels WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $grade_id);
$stmt->execute();
$result = $stmt->get_result();
$grade = $result->fetch_assoc();

echo json_encode($grade);

$connection->close();
?>