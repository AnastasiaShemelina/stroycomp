<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "ID заявки не указан.";
    exit();
}

$request_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Удаляем только свою заявку
$query = "DELETE FROM requests WHERE id = ? AND user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $request_id, $user_id);
mysqli_stmt_execute($stmt);

header("Location: my_requests.php");
exit();
