<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$query = "SELECT requests.id, users.full_name AS user_name, requests.title, requests.description, statuses.title AS status_text
          FROM requests
          JOIN users ON requests.user = users.id
          JOIN statuses ON requests.status = statuses.id
          ORDER BY requests.datetime DESC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
</head>
<body>
    <header>
        <h1><a href="admin_panel.php">Админ-панель</a></h1>
        <nav>
            <a href="manage_examples.php">Примеры работ</a>
            <a href="manage_feedbacks.php">Отзывы</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>
    <div class="content">
        <h2>Заявки пользователей</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="request-cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="request-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><strong>Пользователь:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                        <p><strong>Статус:</strong> <?php echo htmlspecialchars($row['status_text']); ?></p>
                        <div class="card-footer">
                            <a href="view_request.php?id=<?= $row['id'] ?>" class="view-more">Подробнее</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Нет заявок.</p>
        <?php endif; ?>
    </div>
</body>
</html>
