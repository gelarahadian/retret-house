<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

session_start();

$user_id = $_SESSION['user_id'];

$defaultLimit = 10;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : $defaultLimit;
$all = isset($_GET['all']) && $_GET['all'] === 'true'; // Parameter untuk mengambil semua data
$by_user = isset($_GET['by_user']) && $_GET['by_user'] === 'true';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$offset = ($page - 1) * $limit; 

try {
    // Membuat query dengan atau tanpa limit
    if ($all) {
        $sql = "SELECT 
                rs.reservation_id,
                rs.`status`,
                rs.start_date,
                rs.end_date,
                rs.number_of_participants,
                rs.`status`,
                rs.order_date,
                rs.total_price,
                rs.review_id,
                u.name AS user_name,
                r.room_id,
                r.room_code,
                rh.name AS retret_house_name,
                rh.retret_house_id,
                i.image_id AS room_image_id,
                i.url AS room_image_url
                FROM reservations rs
                LEFT JOIN users u ON rs.user_id = u.user_id
                LEFT JOIN rooms r ON rs.room_id = r.room_id
                LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
                LEFT JOIN images i ON r.image_id = i.image_id
                WHERE u.name LIKE ?
                ORDER BY rs.updated_at DESC";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("s", $searchParam);
    }else if($by_user) {
        $sql = "SELECT 
                rs.reservation_id,
                rs.`status`,
                rs.start_date,
                rs.end_date,
                rs.number_of_participants,
                rs.`status`,
                rs.order_date,
                rs.total_price,
                rs.review_id,
                u.user_id,
                u.name AS user_name,
                r.room_id,
                r.room_code,
                rh.name AS retret_house_name,
                rh.retret_house_id,
                i.image_id AS room_image_id,
                i.url AS room_image_url
                FROM reservations rs
                LEFT JOIN users u ON rs.user_id = u.user_id
                LEFT JOIN rooms r ON rs.room_id = r.room_id
                LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
                LEFT JOIN images i ON r.image_id = i.image_id
                WHERE u.user_id LIKE ? AND u.name LIKE ?
                ORDER BY rs.updated_at DESC
                LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("isii", $user_id, $searchParam, $offset, $limit);
    } else {
        $sql = "SELECT 
                rs.reservation_id,
                rs.`status`,
                rs.start_date,
                rs.end_date,
                rs.number_of_participants,
                rs.`status`,
                rs.order_date,
                rs.total_price,
                rs.review_id,
                u.name AS user_name,
                r.room_id,
                r.room_code,
                rh.name AS retret_house_name,
                rh.retret_house_id,
                i.image_id AS room_image_id,
                i.url AS room_image_url
                FROM reservations rs
                LEFT JOIN users u ON rs.user_id = u.user_id
                LEFT JOIN rooms r ON rs.room_id = r.room_id
                LEFT JOIN retret_houses rh ON r.retret_house_id = rh.retret_house_id
                LEFT JOIN images i ON r.image_id = i.image_id
                WHERE u.name LIKE ?
                ORDER BY rs.updated_at DESC
                LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("sii", $searchParam, $offset, $limit);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    if ($all) {
        $totalPages = 1;
    } else {
        $countSql = "SELECT COUNT(*) AS total FROM users WHERE name LIKE ?";
        $countStmt = $conn->prepare($countSql);
        $countStmt->bind_param("s", $searchParam);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalRows = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalRows / $limit); 
    }

    echo json_encode([
        'status' => 'success',
        'data' => $data,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'limit' => $all ? 'all' : $limit,
        'by_user' => $by_user,
    ]);

} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}

$conn->close();
?>
