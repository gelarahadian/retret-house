<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $image_ids = $_PUT['image_ids'] ?? [];
    $name = $_PUT['name'];
    $address = $_PUT['address'];
    $phone = $_PUT['phone'];
    $id = $_PUT['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM retret_houses WHERE retret_house_id = ?");
        $stmt->bind_param("i", $id);  // Mengikat parameter $id dengan tipe integer ('i')

        $stmt->execute();

        $result = $stmt->get_result();
        $retret_house = $result->fetch_assoc();

        if (!$retret_house) {
            echo json_encode(['status' => 'error', 'message' => "Pengguna tidak ditemukan."]);
            exit;
        }

        $sql = "UPDATE retret_houses SET name = ?, address = ?, phone = ?  WHERE retret_house_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $address, $phone, $id);
        
        if($stmt->execute()) {
            if(!empty($image_ids)) {
                foreach ($image_ids as $image_id) {
                    $sql_check_image = "SELECT image_id FROM images WHERE image_id = ?";
                    $stmt_check_image = $conn->prepare($sql_check_image);
                    $stmt_check_image->bind_param("i", $image_id);
                    $stmt_check_image->execute();
                    $result = $stmt_check_image->get_result();
                    
                    if ($result->num_rows > 0) {
                            $sql_relasi = "INSERT INTO retret_houses_images (retret_house_id, image_id) VALUES (?,?)";
                            $stmt_relasi = $conn->prepare($sql_relasi);
                            $stmt_relasi->bind_param("ii", $id, $image_id);
                            $stmt_relasi->execute();
                    }
                }
            }

            echo json_encode(['status' => 'success', 'message' => "Rumah Retret Berhasil Di Edit", 'image_ids' => $image_ids]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
        }
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
        exit();
    }
}

?>