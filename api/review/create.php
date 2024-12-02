<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

session_start();

$user_id = $_SESSION['user_id'];
$reservation_id = $_POST['reservation_id'];
$rate = $_POST['rate'];
$message = $_POST['message'];

if (empty($rate)) {
    echo json_encode(['status' => 'error', 'message' => "Rating Harus Diisi!"]);
    exit();
}

try {
    $sql = "INSERT INTO reviews (user_id, rate, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $rate, $message);
    if ($stmt->execute()) {
        $review_id = $conn->insert_id;

        $sql = "UPDATE reservations SET review_id = ? WHERE reservation_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii",  $review_id, $reservation_id);

        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => "Pesanan Berhasil Di Beri Ulasan"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    };
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
    exit();
}
?>
