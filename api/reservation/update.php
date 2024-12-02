<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $id = $_PUT['id'];
    $cancel = isset($_PUT['cancel']) ? true : false;
    $checkin = isset($_PUT['checkin']) ? true : false;
    $finish = isset($_PUT['finish']) ? true : false;

    $stmt = $conn->prepare("SELECT * FROM reservations WHERE reservation_id = ?");
    $stmt->bind_param("i", $id);  

    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => "Pengguna tidak ditemukan."]);
        exit;
    }

    $sql = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    if($checkin) {
        $status = 'checkin';
    }else if($cancel) {
        $status = 'cancelled';
    }else if($finish) {
        $status = 'finished';
    }    
    $stmt->bind_param("si",  $status, $id);

    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Data Pengguna Berhasil Di Rubah"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    }
}

?>