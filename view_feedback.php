<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "Отзыв не найден.";
    exit();
}

$feedback_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$query = "SELECT feedbacks.*, statuses.title AS status_title 
          FROM feedbacks 
          JOIN statuses ON feedbacks.status = statuses.id 
          WHERE feedbacks.id = $feedback_id AND feedbacks.user = $user_id";
$result = mysqli_query($conn, $query);

if (!$row = mysqli_fetch_assoc($result)) {
    echo "Отзыв не найден или у вас нет прав на его просмотр.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр отзыва</title>
    <link rel="stylesheet" href="styles/view_item_style.css">
</head>
<body>
    <header>
        <h1><a href="user_dashboard.php">Личный кабинет</a></h1>
        <nav>
            <a href="my_feedbacks.php">Мои Отзывы</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>

    <div class="content">
        <div class="item-card">
            <h2 class="item-title"><?php echo htmlspecialchars($row['title']); ?></h2>
            <div class="item-details">
                <p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <p><strong>Статус:</strong> <?php echo htmlspecialchars($row['status_title']); ?></p>
            </div>

            <!-- Если есть фото, отображаем его -->
            <?php if (!empty($row['photo'])): ?>
                <div class="item-photo">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" alt="Фото отзыва" class="item-image">
                </div>
            <?php endif; ?>

            <!-- Кнопки: Редактировать, Удалить, Назад -->
            <div class="request-actions">
                <a href="edit_feedback.php?id=<?php echo $row['id']; ?>" class="edit-button">Редактировать</a>
                <a href="delete_feedback.php?id=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Удалить отзыв?');">Удалить</a>
            </div>

            <a href="my_feedbacks.php" class="back-button">← Назад</a>
        </div>
    </div>
</body>
</html>
