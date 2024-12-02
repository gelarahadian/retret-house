<?php
include('../../includes/db.php ');

session_start();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    if (isset($user_id)) {
        try {
            $sql = "SELECT u.*, img.url as img_url
             FROM users u 
             LEFT JOIN images img ON u.image_id = img.image_id
             WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();

                $userData = [];
                $imageData = null;

                $userData = [
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'gender' => $data['gender'], 
                    'role' => $data['role'],
                ];
                if($data['img_url'] != null) {
                    $imageData = [
                        'id' => $data['image_id'],
                        'url' => $data['img_url']
                    ];
                }

                // Tambahkan data image ke dalam user
                $userData['image'] = $imageData;
                echo json_encode(['status' => 'success', 'data' => $userData]);
            } else {
                echo json_encode(["status" => "error", 'message' => 'Data tidak ditemukan.']);
            }
        }catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error' + $e]);
        }

    }else {
        echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk mengakses halaman ini.']);
    }
}

$stmt->close();
$conn->close();
?>