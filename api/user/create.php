<?php 
include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $gender  = $_POST['gender'];
    $role = $_POST['role'];

    $password = password_hash($email, PASSWORD_BCRYPT);

    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => "Nama Harus Diisi!"]);
        exit();
    }

    try {
        $sql = "INSERT INTO users (name, email, password, address, phone, gender, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $email, $password, $address, $phone, $gender, $role);
        if ($stmt->execute()) {            
            echo json_encode(['status' => 'success', 'message' => "Pengguna Berhasil Tambahkan"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
        };
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $e->getMessage()]);
        exit();
    }
}
?>
