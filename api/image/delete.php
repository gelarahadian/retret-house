<?php
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    include('../../includes/db.php ');
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'] ?? null;   

    // Ambil file berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM images WHERE image_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();

    if ($image) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $image['url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $stmt = $conn->prepare("DELETE FROM images WHERE image_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Gambar berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus gambar dari database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gambar tidak ditemukan.', 'id' => $id]);
    }
}
?>
