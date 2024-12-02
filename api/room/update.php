<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $image_id = isset($_PUT['image_id']) && $_PUT['image_id'] !== '' ? (int)$_PUT['image_id'] : null;
    $roomId = $_PUT['id'];
    $phone = $_PUT['phone'];
    $capacity = $_PUT['capacity'];
    $pricePerPerson = $_PUT['price-per-person'];
    $retretHouseId = $_PUT['retret-house-id'];
    $facility_ids = $_PUT['facility_ids'] ?? [];


    if (empty($roomId)) {
        echo json_encode(['status' => 'error', 'message' => "ID Kamar Harus Diisi!"]);
        exit();
    }
    if (empty($capacity)) {
        echo json_encode(['status' => 'error', 'message' => "Kapasitas Harus Diisi!"]);
        exit();
    }
    if (empty($pricePerPerson)) {
        echo json_encode(['status' => 'error', 'message' => "Harga Harus Diisi!"]);
        exit();
    }
    if (empty($retretHouseId)) {
        echo json_encode(['status' => 'error', 'message' => "Rumah Retret Harus Diisi!"]);
        exit();
    }



    $conn->begin_transaction();
    try {
        // Langkah 1: Update data kamar
        $sqlUpdateRoom = "UPDATE rooms 
                        SET image_id = ?, phone = ?, capacity = ?, price_per_person = ?, retret_house_id = ? 
                        WHERE room_id = ?";
        $stmtUpdateRoom = $conn->prepare($sqlUpdateRoom);
        $stmtUpdateRoom->bind_param("isiiii", $image_id, $phone, $capacity, $pricePerPerson, $retretHouseId, $roomId);
        $stmtUpdateRoom->execute();

        // Langkah 2: Ambil fasilitas lama
        $sqlGetOldFacilities = "SELECT facility_id FROM room_facility WHERE room_id = ?";
        $stmtGetOldFacilities = $conn->prepare($sqlGetOldFacilities);
        $stmtGetOldFacilities->bind_param("i", $roomId);
        $stmtGetOldFacilities->execute();
        $resultOldFacilities = $stmtGetOldFacilities->get_result();
        $oldFacilityIds = [];
        while ($row = $resultOldFacilities->fetch_assoc()) {
            $oldFacilityIds[] = $row['facility_id'];
        }

        // Langkah 3: Bandingkan fasilitas lama dan baru
        sort($oldFacilityIds); // Urutkan untuk perbandingan
        sort($facility_ids);   // Urutkan untuk perbandingan
        if ($oldFacilityIds !== $facility_ids) {
            // Jika fasilitas berubah, hapus yang lama
            $sqlDeleteFacilities = "DELETE FROM room_facility WHERE room_id = ?";
            $stmtDeleteFacilities = $conn->prepare($sqlDeleteFacilities);
            $stmtDeleteFacilities->bind_param("i", $roomId);
            $stmtDeleteFacilities->execute();

            // Masukkan fasilitas baru
            $sqlInsertFacility = "INSERT INTO room_facility (room_id, facility_id) VALUES (?, ?)";
            $stmtInsertFacility = $conn->prepare($sqlInsertFacility);
            foreach ($facility_ids as $facilityId) {
                $stmtInsertFacility->bind_param("ii", $roomId, $facilityId);
                $stmtInsertFacility->execute();
            }
        }

        // Commit transaksi
        $conn->commit();

        echo json_encode(['status' => 'success', 'message' => "Kamar Berhasil Diperbarui!"]);
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
        exit();
    }

}

?>
