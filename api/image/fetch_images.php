<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');
$result = $conn->query("SELECT * FROM images");

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = [
        'id' => $row['id'],
        'url' => $row['url']
    ];
}

echo json_encode(['status' => 'success', 'images' => $images]);
?>
