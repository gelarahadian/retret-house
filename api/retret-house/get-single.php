<?php
include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil parameter `id` atau `slug` dari request
    $id = $_GET['id'] ?? null;
    $slug = $_GET['slug'] ?? null;

    try {
        // Query SQL untuk mengambil data rumah retret dan kamar
        if ($id) {
            $sql = "SELECT	
                        rh.retret_house_id, 
                        rh.name, 
                        rh.slug, 
                        rh.address, 
                        rh.phone,
                        r.room_id,
                        r.room_code,
                        r.phone AS room_phone,
                        r.capacity,
                        r.price_per_person,
                        r.image AS room_image,
                        i.image_id,
                        i.url AS image_url,
                        ri.url AS image_room_url,
                        ri.image_id AS image_room_id,
                        rev.review_id,
                        rev.rate AS review_rate,
                        rev.message AS review_message, 
                        u.name AS review_name
                    FROM retret_houses rh
                    LEFT JOIN rooms r ON rh.retret_house_id = r.retret_house_id
                    LEFT JOIN retret_houses_images rhi ON rh.retret_house_id = rhi.retret_house_id
                    LEFT JOIN images i ON rhi.image_id = i.image_id
                    LEFT JOIN images ri ON r.image_id = ri.image_id
                    LEFT JOIN reservations rs ON r.room_id = rs.room_id
                    LEFT JOIN reviews rev ON rs.review_id = rev.review_id
                    LEFT JOIN users u ON rev.user_id = u.user_id
                    WHERE rh.retret_house_id = ?
                    ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
        } elseif ($slug) {
            $sql = "SELECT 	
                        rh.retret_house_id, 
                        rh.name, 
                        rh.slug, 
                        rh.address, 
                        rh.phone,
                        r.room_id,
                        r.room_code,
                        r.phone AS room_phone,
                        r.capacity,
                        r.price_per_person,
                        r.image AS room_image,
                        i.image_id,
                        i.url AS image_url,
                        ri.url AS image_room_url,
                        ri.image_id AS image_room_id,
                        rev.review_id,
                        rev.rate AS review_rate,
                        rev.message AS review_message, 
                        u.name AS review_user
                    FROM retret_houses rh
                    LEFT JOIN rooms r ON rh.retret_house_id = r.retret_house_id
                    LEFT JOIN retret_houses_images rhi ON rh.retret_house_id = rhi.retret_house_id
                    LEFT JOIN images i ON rhi.image_id = i.image_id
                    LEFT JOIN images ri ON r.image_id = ri.image_id
                    LEFT JOIN reservations rs ON r.room_id = rs.room_id
                    LEFT JOIN reviews rev ON rs.review_id = rev.review_id
                    LEFT JOIN users u ON rev.user_id = u.user_id
                    WHERE rh.slug = ?
                    ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $slug);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID atau Slug harus disertakan']);
            exit();
        }

        // Eksekusi query
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika hasil ditemukan, proses data
        if ($result->num_rows > 0) {
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $id = $row['retret_house_id'];

                if (empty($data)) {
                    // Ambil data rumah retret hanya sekali
                    $data = [
                        'retret_house_id' => $row['retret_house_id'],
                        'name' => $row['name'],
                        'slug' => $row['slug'],
                        'address' => $row['address'],
                        'phone' => $row['phone'],
                        'rooms' => [],
                        'images' => [],
                        'reviews' => [],
                    ];
                }

                // Tambahkan kamar ke dalam array rooms
                if ($row['room_id'] && !array_key_exists($row['room_id'], $data['rooms'])) {
                    $data['rooms'][$row['room_id']] = [
                        'id' => $row['room_id'],
                        'room_code' => $row['room_code'],
                        'phone' => $row['room_phone'],
                        'capacity' => $row['capacity'],
                        'price_per_person' => $row['price_per_person'],
                        'image_url' => $row['image_room_url'],
                        'image_id' => $row['image_room_id']
                    ];
                }

                if (!empty($row['image_id']) && !array_key_exists($row['image_id'], $data['images'])) {
                    $data['images'][$row['image_id']] = [
                        'id' => $row['image_id'],
                        'url' => $row['image_url']
                    ];
                }

                if (!empty($row['review_id']) && !array_key_exists($row['review_id'], $data['reviews'])) {
                    $data['reviews'][$row['review_id']] = [
                        'review_id' => $row['review_id'],
                        'rate' => $row['review_rate'],
                        'message' => $row['review_message'],
                        'user' => $row['review_user'],
                    ];
                }
            }

            $data['rooms'] = array_values($data['rooms']);
            $data['images'] = array_values($data['images']);
            $data['reviews'] = array_values($data['reviews']);


            // Kirim respons JSON
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
}
?>
