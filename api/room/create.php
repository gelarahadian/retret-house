<?php 
include('../../includes/db.php ');

$image_id = isset($_POST['image_id']) && $_POST['image_id'] !== '' ? (int)$_POST['image_id'] : null;
$phone = $_POST['phone'];
$capacity = $_POST['capacity'];
$pricePerPerson = $_POST['price-per-person'];
$retretHouseId = $_POST['retret-house-id'];
$facility_ids = $_POST['facility_ids'] ?? [];


if(empty($capacity)){
    echo json_encode(['status' => 'error', 'message' => "Kapasitas Harus Diisi!"]);
    exit();
}
if(empty($pricePerPerson)){
    echo json_encode(['status' => 'error', 'message' => "Harga Harus Diisi!"]);
    exit();
}
if(empty($retretHouseId)){
    echo json_encode(['status' => 'error', 'message' => "Rumah Retret Harus Diisi!"]);
    exit();
}
if(empty($facility_ids)){
    echo json_encode(['status' => 'error', 'message' => "Fasilitas Harus Diisi!"]);
    exit();
}

// Ambil nama retret house berdasarkan ID
$sqlHouse = "SELECT name FROM retret_houses WHERE retret_house_id = ?";
$stmtHouse = $conn->prepare($sqlHouse);
$stmtHouse->bind_param("i", $retretHouseId);
$stmtHouse->execute();
$resultHouse = $stmtHouse->get_result();
$retretHouse = $resultHouse->fetch_assoc();

if (!$retretHouse) {
    echo json_encode(['status' => 'error', 'message' => "Rumah Retret Tidak Ditemukan"]);
    exit();
}

// Ekstrak huruf pertama dari setiap kata di nama retret house
$retretHouseName = $retretHouse['name'];
$initials = implode('', array_map(function($word) {
    return strtoupper($word[0]);
}, explode(' ', $retretHouseName)));

// Hitung jumlah kamar yang sudah ada untuk retret house ini
$sqlRoomCount = "SELECT COUNT(*) as room_count FROM rooms WHERE retret_house_id = ?";
$stmtRoomCount = $conn->prepare($sqlRoomCount);
$stmtRoomCount->bind_param("i", $retretHouseId);
$stmtRoomCount->execute();
$resultRoomCount = $stmtRoomCount->get_result();
$roomCountData = $resultRoomCount->fetch_assoc();
$roomCount = $roomCountData['room_count'];

$newRoomNumber = $roomCount + 1;
$roomCode = sprintf("%s-%03d", $initials, $newRoomNumber);

$conn->begin_transaction();
try {
    // Langkah 1: Masukkan ke tabel rooms
    $sql = "INSERT INTO rooms (image_id, room_code, phone, capacity, price_per_person, retret_house_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiii", $image_id, $roomCode, $phone, $capacity, $pricePerPerson, $retretHouseId);
    $stmt->execute();
    
    $roomId = $conn->insert_id;

    $sqlFacility = "INSERT INTO room_facility (room_id, facility_id) VALUES (?, ?)";
    $stmtFacility = $conn->prepare($sqlFacility);
    foreach ($facility_ids as $facilityId) {
        $stmtFacility->bind_param("ii", $roomId, $facilityId);
        $stmtFacility->execute();
    }

    // Commit transaksi
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => "Kamar Berhasil Disimpan!", 'facility_ids' => $facility_ids, 'image_id' => $image_id]);
} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}
?>