<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

$image_ids = $_POST['image_ids'];
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// Validasi input
if (empty($name)) {
    echo json_encode(['status' => 'error', 'message' => "Nama Harus Diisi!"]);
    exit();
}

// Fungsi untuk membuat slug
function createSlug($string) {
    $slug = strtolower(trim($string)); 
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug); 
    return rtrim($slug, '-'); 
}

$slug = createSlug($name);

try {
    $sql = "INSERT INTO retret_houses (name, slug, address, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $slug, $address, $phone);
    if ($stmt->execute()) {
        $retret_house_id = $stmt->insert_id;  
        
        if (!empty($image_ids)) {
            foreach ($image_ids as $image_id) {
                $sql_check_image = "SELECT image_id FROM images WHERE image_id = ?";
                $stmt_check_image = $conn->prepare($sql_check_image);
                $stmt_check_image->bind_param("i", $image_id);
                $stmt_check_image->execute();
                $result = $stmt_check_image->get_result();

                if ($result->num_rows > 0) {
                    $sql_relasi = "INSERT INTO retret_houses_images (retret_house_id, image_id) VALUES (?, ?)";
                    $stmt_relasi = $conn->prepare($sql_relasi);
                    $stmt_relasi->bind_param("ii", $retret_house_id, $image_id);
                    $stmt_relasi->execute();
                }
            }
        }
        echo json_encode(['status' => 'success', 'message' => "Rumah Retret Berhasil Dibuat"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    };
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
    exit();
}
?>
