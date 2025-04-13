<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $theme = mysqli_real_escape_string($conn, $_POST['theme']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
    $status = 3; // Новая заявка

    $query = "INSERT INTO requests (user, datetime, title, description, work_type, photo, status) 
              VALUES (?, NOW(), ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    $photo = '';
    mysqli_stmt_bind_param($stmt, 'isssbi', $user_id, $theme, $description, $work_type, $photo, $status);

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_data = file_get_contents($photo_tmp_name);

        mysqli_stmt_send_long_data($stmt, 4, $photo_data);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: my_requests.php");
        exit();
    } else {
        echo "Ошибка при создании заявки: " . mysqli_stmt_error($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать заявку</title>
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
        <h2>Создать новую заявку</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="theme">Тема</label>
            <input type="text" name="theme" id="theme" required class="input-field">

            <label for="description">Описание</label>
            <textarea name="description" id="description" required class="input-field" rows="5"></textarea>

            <label for="work_type">Тип работ</label>
            <select name="work_type" id="work_type" required class="input-field">
                <option value="">Выберите тип работ</option>
                <?php
                $query = "SELECT * FROM work_types";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
            </select><br>

            <label for="photo">Фото</label>
            <input type="file" name="photo" id="photo" class="input-field">

            <button type="submit" class="create-new-request">Создать заявку</button>
        </form>
    </div>
</body>
</html>
