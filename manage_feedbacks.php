<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$query = "SELECT feedbacks.*, users.full_name, statuses.title AS status_title 
          FROM feedbacks 
          JOIN users ON feedbacks.user = users.id
          JOIN statuses ON feedbacks.status = statuses.id
          ORDER BY feedbacks.datetime DESC";

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
    <title>Модерация отзывов</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>
    <header>
        <h1><a href="admin_panel.php">Админ-панель</a></h1>
        <nav>
            <a href="admin_panel.php">Заявки</a>
            <a href="manage_examples.php">Примеры работ</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>

    <div class="content">
        <h2>Модерация отзывов</h2>
        
        <?php if ($no_feedbacks): ?>
            <p>Нет отзывов</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Отзыв</th>
                    <th>Пользователь</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo date('d.m.Y', strtotime($row['datetime'])); ?></td>
                        <td><?php echo htmlspecialchars($row['status_title']); ?></td>
                        <td>
                            <?php if ($row['status'] == 7): // Новый (На проверке) ?>
                                <a href="approve_feedback.php?id=<?php echo $row['id']; ?>">Опубликовать</a> | 
                                <a href="reject_feedback.php?id=<?php echo $row['id']; ?>">Отклонить</a>
                            <?php elseif ($row['status'] == 1): // Опубликован ?>
                                <a href="reject_feedback.php?id=<?php echo $row['id']; ?>">Отклонить</a>
                            <?php elseif ($row['status'] == 2): // Отклонён ?>
                                <a href="approve_feedback.php?id=<?php echo $row['id']; ?>">Опубликовать</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
