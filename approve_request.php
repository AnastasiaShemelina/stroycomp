<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $request_id = (int)$_GET['id'];
    // Обновляем статус заявки на "В работе"
    $query = "UPDATE requests SET status = 5 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $request_id);

    if ($stmt->execute()) {
        header("Location: admin_panel.php");
        exit();
    } else {
        echo "Ошибка при обновлении статуса заявки.";
    }
} else {
    echo "Некорректный запрос.";
}
?>
