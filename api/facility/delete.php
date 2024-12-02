<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_GET['id'] ?? null;    

    try {
        $sql = "DELETE FROM facilities WHERE facility_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => "Fasilitas Berhasil Dihapus"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
        }
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
    }
}
?>