<?php 
session_start(); 

unset($_SESSION['user_id']);
unset($_SESSION['role']);

session_destroy();

echo json_encode(["status" => "success", "message" => "Anda Berhasil Keluar!"]);
?>