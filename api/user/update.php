<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $id = $_PUT['id'];
    $name = $_PUT['name'];
    $email = $_PUT['email'];
    $address = $_PUT['address'];
    $phone = $_PUT['phone'];
    $gender  = $_PUT['gender'];
    $role = $_PUT['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);  

    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => "Pengguna tidak ditemukan."]);
        exit;
    }

    $sql = "UPDATE users SET name = ?, email = ?, address = ?, phone = ?, gender = ?, role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $email,$address, $phone, $gender, $role, $id);
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Data Pengguna Berhasil Di Rubah"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    }
}

?>