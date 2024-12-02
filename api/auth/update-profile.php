<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

session_start();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    
    
    $image_id = isset($_PUT['image_id']) && $_PUT['image_id'] !== '' ? (int)$_PUT['image_id'] : null;
    $name = $_PUT['name'];
    $email = $_PUT['email'];
    $address = $_PUT['address'];
    $phone = $_PUT['phone'];
    $gender  = $_PUT['gender'];



    try {

        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);  

        $stmt->execute();

        $result = $stmt->get_result();
        $facility = $result->fetch_assoc();


        if (!$facility) {
            echo json_encode(['status' => 'error', 'message' => "Facility tidak ditemukan."]);
            exit;
        }


        $sql = "UPDATE users SET name = ?, email = ?, address = ?, phone = ?, gender = ?, image_id = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssii", $name, $email,$address, $phone, $gender,$image_id, $user_id);
        
        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => "Data Anda Berhasil Di Rubah"]);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
            exit();
        }
    }catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error' . $e]);
    }
}

?>