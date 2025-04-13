<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$query = "SELECT examples.*, work_types.title AS work_type_title 
          FROM examples 
          JOIN work_types ON examples.work_type = work_types.id
          ORDER BY examples.id DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $no_examples = true;
} else {
    $no_examples = false;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление примерами работ</title>
    <link rel="stylesheet" href="styles/admin_style.css">
</head>
<body>
    <header>
        <h1><a href="admin_panel.php">Админ-панель</a></h1>
        <nav>
            <a href="admin_panel.php">Заявки</a>
            <a href="manage_feedbacks.php">Отзывы</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>

    <div class="content">
        <h2>Примеры работ</h2>
        
        <a href="new_example.php" class="create-new-example">Добавить новый пример</a>

        <?php if ($no_examples): ?>
            <p>Нет примеров работ</p>
        <?php else: ?>
            <div class="example-cards">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="example-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p>Тип работ: <?php echo htmlspecialchars($row['work_type_title']); ?></p> <!-- Изменено на work_type_title -->
                        <a href="view_example.php?id=<?php echo $row['id']; ?>" class="view-more">Подробнее</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
