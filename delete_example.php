<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "ID примера работы не указан.";
    exit();
}

$example_id = (int)$_GET['id'];

// Удаляем пример работы
$query = "DELETE FROM examples WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $example_id);
mysqli_stmt_execute($stmt);

header("Location: manage_examples.php");
exit();
