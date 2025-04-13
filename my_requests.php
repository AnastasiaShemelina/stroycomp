<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT requests.*, statuses.title AS status_title, work_types.title AS work_type_title 
          FROM requests 
          JOIN statuses ON requests.status = statuses.id
          JOIN work_types ON requests.work_type = work_types.id
          WHERE requests.user = $user_id 
          ORDER BY requests.datetime DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $no_requests = true;
} else {
    $no_requests = false;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
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
        <h2>Мои заявки</h2>
        
        <a href="new_request.php" class="create-new-request">Создать новую заявку</a>

        <?php if ($no_requests): ?>
            <p>Нет заявок</p>
        <?php else: ?>
            <div class="request-cards">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="request-card">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="work-type">Тип работ: <?php echo htmlspecialchars($row['work_type_title']); ?></p>
                        <p class="request-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="card-footer">
                            <p>Статус: <?php echo htmlspecialchars($row['status_title']); ?></p>
                            <a href="view_request.php?id=<?php echo $row['id']; ?>" class="view-more">Подробнее</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
