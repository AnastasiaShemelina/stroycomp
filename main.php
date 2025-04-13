<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
</head>
<body>
    <h1>Добро пожаловать в ваш аккаунт!</h1>
    <p>Здесь будет ваш контент.</p>
    <a href="logout.php">Выйти</a>
</body>
</html>
