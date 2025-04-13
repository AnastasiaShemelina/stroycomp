<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "Заявка не найдена.";
    exit();
}

$request_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 2) {
    // Пользователь может смотреть только свои заявки
    $query = "SELECT requests.*, statuses.title AS status_title, work_types.title AS work_type_title, users.full_name
              FROM requests 
              JOIN statuses ON requests.status = statuses.id
              JOIN work_types ON requests.work_type = work_types.id
              JOIN users ON requests.user = users.id
              WHERE requests.id = ? AND requests.user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $request_id, $user_id);
} else {
    // Админ может смотреть любую заявку
    $query = "SELECT requests.*, statuses.title AS status_title, work_types.title AS work_type_title, users.full_name
              FROM requests 
              JOIN statuses ON requests.status = statuses.id
              JOIN work_types ON requests.work_type = work_types.id
              JOIN users ON requests.user = users.id
              WHERE requests.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $request_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "Заявка не найдена или у вас нет к ней доступа.";
    exit();
}

$request = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали заявки</title>
    <link rel="stylesheet" href="styles/view_item_style.css">
</head>
<body>
<header>
    <h1>
        <?php if ($role == 1): ?>
            <a href="admin_panel.php">Админ-панель</a>
        <?php else: ?>
            <a href="user_dashboard.php">Личный кабинет</a>
        <?php endif; ?>
    </h1>
    <nav>
        <?php if ($role == 1): ?>
            <a href="manage_examples.php">Примеры работ</a>
            <a href="manage_feedbacks.php">Отзывы</a>
        <?php else: ?>
            <a href="my_requests.php">Мои Заявки</a>
        <?php endif; ?>
        <a href="logout.php" class="logout-button">Выйти</a>
    </nav>
</header>

<div class="content">
    <h2>Детали заявки</h2>

    <div class="request-details-wrapper">
        <!-- Метки статуса и типа работ (перемещены внутрь карточки) -->
        <div class="request-tags">
            <span class="tag work-type"><?= htmlspecialchars($request['work_type_title']) ?></span>
            <span class="tag status <?= strtolower(str_replace(' ', '', $request['status_title'])) ?>">
                <?= htmlspecialchars($request['status_title']) ?>
            </span>
        </div>

        <div class="request-text">
            <p><strong>Тема:</strong> <?= htmlspecialchars($request['title']) ?></p>
            <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($request['description'])) ?></p>
            <p><strong>Пользователь:</strong> <?= htmlspecialchars($request['full_name']) ?></p>
            <p><strong>Дата создания:</strong> <?= date("d.m.Y H:i:s", strtotime($request['datetime'])) ?></p>
        </div>

        <?php if (!empty($request['photo'])): ?>
            <div class="request-photo">
                <img src="data:image/jpeg;base64,<?= base64_encode($request['photo']) ?>" alt="Фото заявки">
            </div>
        <?php endif; ?>
    </div>

    <div class="request-actions">
        <?php if ($role == 2): ?>
            <a href="edit_request.php?id=<?= $request['id'] ?>" class="edit-button">Редактировать</a>
            <a href="delete_request.php?id=<?= $request['id'] ?>" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?');">Удалить</a>
        <?php elseif ($role == 1): ?>
            <a href="approve_request.php?id=<?= $request['id'] ?>" class="approve-button">В работу</a>
            <a href="reject_request.php?id=<?= $request['id'] ?>" class="reject-button" onclick="return confirm('Отклонить заявку?');">Отклонить</a>
        <?php endif; ?>
    </div>

    <a href="<?= $role == 1 ? 'admin_panel.php' : 'my_requests.php' ?>" class="back-button">← Назад</a>
</div>

</body>
</html>
