<?php
header('Content-Type: application/json');

$connection = new mysqli("localhost", "root", "", "spt");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$input = json_decode(file_get_contents('php://input'), true);
$grade = $input['grade'];

// First get the grade_level_id using the grade level number
$query = "SELECT id FROM grade_levels WHERE grade_level = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $grade);
$stmt->execute();
$result = $stmt->get_result();
$grade_data = $result->fetch_assoc();

if (!$grade_data) {
    echo json_encode(['success' => false, 'message' => 'Grade level not found']);
    exit;
}

$grade_level_id = $grade_data['id'];

// Now insert the new section
$section = $input['section'];
$query = "INSERT INTO sections (grade_level_id, section_name) VALUES (?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("is", $grade_level_id, $section);

if ($stmt->execute()) {
    $section_id = $stmt->insert_id;
    echo json_encode([
        'success' => true,
        'section_id' => $section_id,
        'section_name' => $section
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add section.']);
}

$connection->close();
