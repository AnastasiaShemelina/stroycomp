<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $user_id = $_SESSION['user_id'];  // ID пользователя из сессии
    $title = mysqli_real_escape_string($conn, $_POST['title']);  // Заголовок отзыва
    $description = mysqli_real_escape_string($conn, $_POST['content']);  // Контент отзыва

    // Вставляем новый отзыв в таблицу
    $query = "INSERT INTO feedbacks (user, datetime, title, description, status) 
              VALUES ('$user_id', NOW(), '$title', '$description', 7)";  // Статус "На проверке" имеет ID = 7

    if (mysqli_query($conn, $query)) {
        header("Location: my_feedbacks.php");  // После успешного добавления, перенаправляем на страницу с отзывами
        exit();
    } else {
        echo "Ошибка при создании отзыва.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оставить отзыв</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
</head>
<body>
    <header>
        <h1><a href="user_dashboard.php">Личный кабинет</a></h1>
        <nav>
            <a href="my_requests.php">Мои Заявки</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>

    <div class="content">
        <h2>Оставить отзыв</h2>
        <form method="POST">
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" required>

            <label for="content">Текст отзыва</label>
            <textarea name="content" id="content" rows="5" required></textarea>

            <button type="submit">Оставить отзыв</button>
        </form>
    </div>
</body>
</html>
