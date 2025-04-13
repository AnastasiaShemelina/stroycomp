<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT feedbacks.*, statuses.title AS status_title FROM feedbacks 
          JOIN statuses ON feedbacks.status = statuses.id 
          WHERE user = $user_id ORDER BY datetime DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $no_feedbacks = true;
} else {
    $no_feedbacks = false;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои отзывы</title>
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
        <h2>Мои отзывы</h2>

        <!-- Кнопка оставить отзыв -->
        <a href="new_feedback.php" class="create-new-feedback">Оставить отзыв</a>

        <?php if ($no_feedbacks): ?>
            <p>Нет отзывов</p>
        <?php else: ?>
            <div class="feedback-cards">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="feedback-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        <div class="card-footer">
                            <p><strong>Статус:</strong> <?php echo htmlspecialchars($row['status_title']); ?></p>
                            <a href="view_feedback.php?id=<?php echo $row['id']; ?>" class="view-more">Подробнее</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
