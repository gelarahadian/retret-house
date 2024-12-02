<?php
session_start();

$dirname = dirname($_SERVER['PHP_SELF']);
$dirname = str_replace('\\', '/', $_SERVER['REQUEST_URI']);

if (isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') {
         if (strpos($dirname, '/dashboard/admin') !== 0 && $dirname !== '/dashboard/admin') {
            header('Location: /dashboard/admin');
            exit();
        }
    }else if($_SESSION['role'] == 'visitor'){
        if ($_SESSION['role'] === 'visitor') {
            if (strpos($dirname, '/dashboard/admin') === 0) {
                header('Location: /dashboard');
                exit();
            }
        }
    }
//     if (basename(dirname($_SERVER['PHP_SELF'])) !== 'dashboard') {
//         header('Location: /dashboard');
//         exit();
//     }
}else {
    if($dirname == '/dashboard' || $dirname == '/dashboard/admin') {
        header('Location: /sign-in.php');
        exit();
    }
}
?>