<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
</head>
<body>
    <header>
        <h1><a href="user_dashboard.php">Личный кабинет</a></h1>
        <nav>
            <a href="my_requests.php">Мои Заявки</a>
            <a href="my_feedbacks.php">Мои Отзывы</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>
    <div class="content">
        <h2>Добро пожаловать в личный кабинет!</h2>
        <p>Вы можете управлять своими заявками и отзывами.</p>
    </div>
</body>
</html>
