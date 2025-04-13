<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    // Если пользователь не администратор, перенаправляем на главную панель админа или страницу входа
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "Пример работы не найден.";
    exit();
}

$example_id = (int)$_GET['id'];

$query = "SELECT examples.*, work_types.title AS work_type_title
          FROM examples 
          JOIN work_types ON examples.work_type = work_types.id
          WHERE examples.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $example_id);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Пример работы не найден.";
    exit();
}

$example = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали примера работы</title>
    <link rel="stylesheet" href="styles/view_item_style.css">
</head>
<body>
<header>
    <h1>
        <a href="admin_panel.php">Админ-панель</a>
    </h1>
    <nav>
        <a href="manage_examples.php">Примеры работ</a>
        <a href="manage_feedbacks.php">Отзывы</a>
        <a href="logout.php" class="logout-button">Выйти</a>
    </nav>
</header>

<div class="content">
    <h2>Детали примера работы</h2>

    <div class="request-details-wrapper">
        <!-- Метки типа работ (перемещены внутрь карточки) -->
        <div class="request-tags">
            <span class="tag work-type"><?= htmlspecialchars($example['work_type_title']) ?></span>
        </div>

        <div class="request-text">
            <p><strong>Заголовок:</strong> <?= htmlspecialchars($example['title']) ?></p>
            <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($example['description'])) ?></p>
        </div>

        <?php if (!empty($example['photo'])): ?>
            <div class="request-photo">
                <img src="data:image/jpeg;base64,<?= base64_encode($example['photo']) ?>" alt="Фото примера работы">
            </div>
        <?php endif; ?>
    </div>

    <!-- Кнопки редактирования и удаления -->
    <div class="request-actions">
        <a href="edit_example.php?id=<?= $example['id'] ?>" class="edit-button">Редактировать</a>
        <a href="delete_example.php?id=<?= $example['id'] ?>" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить этот пример работы?');">Удалить</a>
    </div>

    <a href="manage_examples.php" class="back-button">← Назад</a>
</div>

</body>
</html>
