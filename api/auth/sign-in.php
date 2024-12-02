<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email dan password harus diisi!']);
        exit();
    }

    $sql = "SELECT user_id, role, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id,$role, $hashed_password);
        $stmt->fetch();

        // Verifikasi password yang dimasukkan dengan hash di database
        if ($hashed_password && password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Ambil data pengguna (selain password) dari database
            $user_sql = "SELECT user_id, name, email, address, phone, gender, role FROM users WHERE user_id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("i", $id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user_data = $user_result->fetch_assoc();

            // Kembalikan data pengguna dalam format JSON
            echo json_encode(['status' => 'success', 'message' => 'Login berhasil!', 'data' => $user_data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan!']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
