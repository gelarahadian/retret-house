<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');


$defaultLimit = 10;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : $defaultLimit;
$all = isset($_GET['all']) && $_GET['all'] === 'true'; // Parameter untuk mengambil semua data

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$offset = ($page - 1) * $limit; // Menghitung offset untuk query

try {
    // Membuat query dengan kondisi pencarian dan pagination
    $sql = "SELECT r.*,
            rh.name AS retret_house_name,
            rh.slug AS retret_house_slug,
            GROUP_CONCAT(f.name) AS facility_names,
            i.url AS image_url
            FROM rooms r
            LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
            LEFT JOIN room_facility rf ON r.room_id = rf.room_id
            LEFT JOIN facilities f ON rf.facility_id = f.facility_id
            LEFT JOIN images i ON r.image_id = i.image_id
            WHERE r.room_code LIKE ?
            GROUP BY r.room_id 
            ORDER BY r.updated_at DESC
            LIMIT ?, ?";
            
    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param("sii", $searchParam, $offset, $limit);
    
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['facility_names'])) {
                $row['facility_names'] = explode(',', $row['facility_names']);
            } else {
                $row['facility_names'] = [];
            }

            $data[] = $row;
        }
    }

    // Menghitung total data untuk pagination
    $countSql = "SELECT COUNT(*) AS total FROM rooms WHERE room_code LIKE ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("s", $searchParam);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit); // Menghitung jumlah halaman

    // Mengirim data JSON
    echo json_encode([
        'status' => 'success',
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);

} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}

$conn->close();
?>
