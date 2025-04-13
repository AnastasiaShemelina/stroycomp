<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

// Подключаем базу данных
include 'config.php';

// Получаем ID отзыва из параметра запроса
if (isset($_GET['id'])) {
    $feedback_id = intval($_GET['id']);
    
    // Обновляем статус отзыва на "Отклонён" (status = 2)
    $query = "UPDATE feedbacks SET status = 2 WHERE id = $feedback_id";
    
    if (mysqli_query($conn, $query)) {
        // Успех, редиректим обратно на страницу с отзывами
        header("Location: manage_feedbacks.php");
    } else {
        // Если ошибка, выводим сообщение
        echo "Ошибка при обновлении статуса отзыва.";
    }
} else {
    echo "Отзыв не найден.";
}
?>
