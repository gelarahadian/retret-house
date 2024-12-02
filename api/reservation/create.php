<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

session_start();

$user_id = $_SESSION['user_id'];
$room_id = $_POST['room_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$number_of_participants = $_POST['number_of_participants'];
$total_price = $_POST['total_price'];


// Validasi input
if(!isset($_SESSION['user_id'])){
    echo json_encode(['status' => 'error', 'message' => "Anda Harus Login Terlebih Dahulu"]);
    exit();
}

try {
    $sql = "INSERT INTO reservations (user_id, room_id, start_date, end_date, number_of_participants, total_price) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissii", $user_id, $room_id, $start_date, $end_date, $number_of_participants, $total_price);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Rumah Retret Berhasil Dibuat"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    };
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
    exit();
}
?>
