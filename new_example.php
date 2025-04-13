<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) { // Проверка на роль администратора
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);

    // Обработка загрузки фото
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Чтение файла в бинарном формате
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_data = file_get_contents($photo_tmp_name); // Считываем содержимое файла в переменную

        $photo = $photo_data; // Указываем содержимое файла в переменную
    } else {
        echo "Фото не загружено или ошибка при загрузке.";
    }

    // Вставка данных в базу данных
    $query = "INSERT INTO examples (title, description, work_type, photo) 
              VALUES ('$title', '$description', '$work_type', ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $photo); // связываем переменную с параметром запроса (фото)

    if (mysqli_stmt_execute($stmt)) {
        header("Location: manage_examples.php"); // Перенаправление на страницу с примерами работ
        exit();
    } else {
        echo "Ошибка при создании примера работы.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать пример работы</title>
    <link rel="stylesheet" href="styles/user_dashboard_style.css">
</head>
<body>
    <header>
        <h1><a href="admin_panel.php">Админ-панель</a></h1>
        <nav>
            <a href="manage_examples.php">Примеры работ</a>
            <a href="logout.php" class="logout-button">Выйти</a>
        </nav>
    </header>

    <div class="content">
        <h2>Создать новый пример работы</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Название</label>
            <input type="text" name="title" id="title" required class="input-field">

            <label for="description">Описание</label>
            <textarea name="description" id="description" required class="input-field"></textarea>

            <label for="work_type">Тип работ</label>
            <select name="work_type" id="work_type" required class="input-field">
                <option value="">Выберите тип работ</option>
                <?php
                // Загружаем типы работ из базы данных
                $query = "SELECT * FROM work_types";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
            </select><br>

            <label for="photo">Фото</label>
            <input type="file" name="photo" id="photo" class="input-field">

            <button type="submit" class="create-new-example">Создать пример работы</button>
        </form>
    </div>
</body>
</html>
