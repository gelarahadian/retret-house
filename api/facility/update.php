<?php
include($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    $name = $_PUT['name'];
    $facility_type = $_PUT['facility_type'];
    $id = $_PUT['id'];

    $stmt = $conn->prepare("SELECT * FROM facilities WHERE facility_id = ?");
    $stmt->bind_param("i", $id);  

    $stmt->execute();

    $result = $stmt->get_result();
    $facility = $result->fetch_assoc();

    if (!$facility) {
        echo json_encode(['status' => 'error', 'message' => "Facility tidak ditemukan."]);
        exit;
    }

    $sql = "UPDATE facilities SET name = ?, facility_type = ? WHERE facility_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $facility_type, $id);
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Facility Berhasil Di Edit"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error: " . $conn->error]);
    }
}

?>