<?php 
include('../../includes/db.php ');

$name = $_POST['name'];
$facility_type = $_POST['facility_type'];


if(empty($name)) {
    echo json_encode(['status' => 'error', 'message' => "Nama Harus Diisi!"]);
    exit();
}
if(empty($facility_type)) {
    echo json_encode(['status' => 'error', 'message' => "Tipe Harus Diisi!"]);
    exit();
}

try {
    $sql = "INSERT INTO facilities (name, facility_type ) VALUES ( ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $facility_type);
    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Facility Berhasil Di Buat"]);
    }else {
        echo json_encode(['status' => 'error', 'message' => "Error: ". $conn->error]);
    };
}catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}
?>