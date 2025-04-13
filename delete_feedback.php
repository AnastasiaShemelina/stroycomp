<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = intval($_POST['id']);
    $user_id = $_SESSION['user_id'];

    $delete = "DELETE FROM feedbacks WHERE id = $feedback_id AND user = $user_id";
    if (mysqli_query($conn, $delete)) {
        header("Location: my_feedbacks.php");
        exit();
    } else {
        echo "Ошибка при удалении отзыва.";
    }
} else {
    echo "Неверный метод запроса.";
}
?>
