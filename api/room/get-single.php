<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    $room_code = $_GET['room_code'] ?? null;

    try {
        if($id) {
            $sql = "SELECT r.*,
                    rh.name AS retret_house_name,
                    GROUP_CONCAT(f.name) AS facility_names,
                    GROUP_CONCAT(f.facility_id) AS facility_ids, 
                    i.url AS image_url
                    FROM rooms r
                    LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
                    LEFT JOIN room_facility rf ON r.room_id = rf.room_id
                    LEFT JOIN facilities f ON rf.facility_id = f.facility_id
                    LEFT JOIN images i ON r.image_id = i.image_id
                    WHERE r.room_id = ?
                    GROUP BY r.room_id ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
        }else if($room_code){
            $sql = "SELECT r.*,
                    rh.name AS retret_house_name,
                    GROUP_CONCAT(f.name) AS facility_names,
                    GROUP_CONCAT(f.facility_id) AS facility_ids, 
                    i.url AS image_url
                    FROM rooms r
                    LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
                    LEFT JOIN room_facility rf ON r.room_id = rf.room_id
                    LEFT JOIN facilities f ON rf.facility_id = f.facility_id
                    LEFT JOIN images i ON r.image_id = i.image_id
                    WHERE r.room_code = ?
                    GROUP BY r.room_id";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $room_code);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if (!empty($data['facility_names'])) {
                $data['facility_names'] = explode(',', $data['facility_names']);
                $data['facility_ids'] = explode(',', $data['facility_ids']);
            } else {
                $data['facility_names'] = [];
                $data['facility_ids'] = [];
            }
            echo json_encode(['status' => 'success', 'data' => $data]);

        }else {
            echo json_encode(["status" => "error", 'message' => 'Data tidak ditemukan.', 'room_code' => $room_code]);
        }
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
        exit();
    }

}

$conn->close();
?>
