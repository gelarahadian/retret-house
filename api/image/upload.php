<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/upload/';

    // Pastikan folder upload ada, jika tidak buat
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName; // Path lengkap ke file yang akan diupload

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $url = '/assets/images/upload/' . $fileName;

            $stmt = $conn->prepare("INSERT INTO images (filename, url) VALUES (?, ?)");
            $stmt->bind_param('ss', $fileName, $url);

            if ($stmt->execute()) {
                $imageId = $conn->insert_id;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Gambar berhasil diupload.',
                    'image_id' => $imageId,
                    'url' => $url
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diunggah.']);
    }
}
?>
