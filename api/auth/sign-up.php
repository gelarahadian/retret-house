<?php
include('../../includes/db.php ');

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];
$name = trim($firstName . ' ' . $lastName);


if(empty($name)){
    echo json_encode(['status' => 'error', 'message' => "Nama Harus Di isi!"]);
    exit();
}
if(empty($email)){
    echo json_encode(['status' => 'error', 'message' => "Email Harus Di isi!"]);
    exit();

}
if(empty($password)){
    echo json_encode(['status' => 'error', 'message' => "Password Harus Di isi!"]);
    exit();
}

$password = password_hash($password, PASSWORD_BCRYPT);


// Check if username exists
$sql = "SELECT email FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => "Email sudah ada!"]);
    exit();
} else {
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Pendaftaran Berhasil!"]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $stmt->error]);
        exit();
    }
}

$stmt->close();
$conn->close();
?>
