<?php 
include('../../includes/db.php');

$defaultLimit = 10;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : $defaultLimit;
$all = isset($_GET['all']) && $_GET['all'] === 'true'; // Parameter untuk mengambil semua data

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$offset = ($page - 1) * $limit; 

try {
    // Membuat query dengan atau tanpa limit
    if ($all) {
        $sql = "SELECT * FROM users
                WHERE name LIKE ?
                ORDER BY updated_at DESC";
        $stmt = $conn->prepare($sql);
        $searchParam = '%' . $search . '%';
        $stmt->bind_param("s", $searchParam);
    } else {
        $sql = "SELECT * FROM users
                WHERE name LIKE ? 
                ORDER BY updated_at DESC
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
        'limit' => $all ? 'all' : $limit 
    ]);

} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error: ". $e->getMessage()]);
    exit();
}

$conn->close();
?>
