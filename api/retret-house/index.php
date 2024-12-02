<?php 
include('../../includes/db.php');

// Jumlah data per halaman dengan nilai default
$defaultLimit = 10;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : $defaultLimit;
$all = isset($_GET['all']) && $_GET['all'] === 'true'; // Parameter untuk mengambil semua data

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$offset = ($page - 1) * $limit; // Menghitung offset untuk query

try {
    // Membuat query dengan atau tanpa limit
    if ($all) {
        $sql = "SELECT 
                    rh.retret_house_id,
                    rh.name,
                    rh.slug,
                    rh.address,
                    rh.phone, 
                    GROUP_CONCAT(i.url) AS image_urls, 
                    GROUP_CONCAT(i.image_id) AS image_ids
                FROM retret_houses rh
                LEFT JOIN retret_houses_images rhi ON rhi.retret_house_id = rh.retret_house_id
                LEFT JOIN images i ON i.image_id = rhi.image_id
                WHERE name LIKE ?
                GROUP BY rh.retret_house_id
                ORDER BY updated_at DESC";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("s", $searchParam);
    } else {
        $sql = "SELECT 
                    rh.retret_house_id,
                    rh.name,
                    rh.slug,
                    rh.address,
                    rh.phone, 
                    GROUP_CONCAT(i.url) AS image_urls, 
                    GROUP_CONCAT(i.image_id) AS image_ids
                FROM retret_houses rh
                LEFT JOIN retret_houses_images rhi ON rhi.retret_house_id = rh.retret_house_id
                LEFT JOIN images i ON i.image_id = rhi.image_id
                WHERE name LIKE ?
                GROUP BY rh.retret_house_id
                ORDER BY updated_at DESC
                LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("sii", $searchParam, $offset, $limit);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $retret_houses = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['retret_house_id'];

            // Jika retret_house belum ada di array, tambahkan
            if (!isset($retret_houses[$id])) {
                $retret_houses[$id] = [
                    'retret_house_id' => $row['retret_house_id'],
                    'name' => $row['name'],
                    'address' => $row['address'],
                    'phone' => $row['phone'],
                    'slug' => $row['slug'],
                    'images' => [],
                ];

                $image_ids = $row['image_ids'] ? explode(',', $row['image_ids']) : [];
                $image_urls = $row['image_urls'] ? explode(',', $row['image_urls']) : [];
            }

            if(count($image_ids) === count($image_urls)) {
                foreach ($image_ids as $index => $image_id) {
                    $retret_houses[$id]['images'][] = [
                        'image_id' => $image_id,
                        'url' => $image_urls[$index]
                    ];
                }
            }
        }
    }

    // Menghitung total data hanya jika tidak mengambil semua data
    if ($all) {
        $totalPages = 1;
    } else {
        $countSql = "SELECT COUNT(*) AS total FROM retret_houses WHERE name LIKE ?";
        $countStmt = $conn->prepare($countSql);
        $countStmt->bind_param("s", $searchParam);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalRows = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalRows / $limit); // Menghitung jumlah halaman
    }

    $retret_houses = array_values($retret_houses);

    // Mengirim data JSON
    echo json_encode([
        'status' => 'success',
        'data' => $retret_houses,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'limit' => $all ? 'all' : $limit // Menunjukkan bahwa semua data diambil
    ]);

} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}

$conn->close();
?>
