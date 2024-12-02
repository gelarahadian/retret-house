<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null; 
    try {
        $sql = "SELECT * FROM facilities WHERE facility_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(["status" => "error", 'message' => 'Data tidak ditemukan.']);
        }
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error' + $e]);
    }
}

$stmt->close();
$conn->close();
?>