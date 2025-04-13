<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Отзыв не найден.";
    exit();
}

$feedback_id = intval($_GET['id']);

// Получаем текущие данные отзыва
$query = "SELECT * FROM feedbacks WHERE id = $feedback_id AND user = $user_id";
$result = mysqli_query($conn, $query);
if (!$feedback = mysqli_fetch_assoc($result)) {
    echo "Отзыв не найден или у вас нет прав на редактирование.";
    exit();
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $update = "UPDATE feedbacks SET title = '$title', description = '$description' WHERE id = $feedback_id AND user = $user_id";
    if (mysqli_query($conn, $update)) {
        header("Location: view_feedback.php?id=$feedback_id");
        exit();
    } else {
        echo "Ошибка при обновлении отзыва.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать отзыв</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
</head>
<body>
    <header>
        <h1><a href="user_dashboard.php">Личный кабинет</a></h1>
    </header>

    <div class="content">
        <h2>Редактировать отзыв</h2>
        <form method="POST">
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" required value="<?php echo htmlspecialchars($feedback['title']); ?>" class="input-field">

            <label for="description">Описание</label>
            <textarea name="description" id="description" required class="input-field" rows="5"><?php echo htmlspecialchars($feedback['description']); ?></textarea>

            <button type="submit" class="btn">Сохранить изменения</button>
            <a href="view_feedback.php?id=<?php echo $feedback_id; ?>" class="btn">Отмена</a>
        </form>
    </div>
</body>
</html>
