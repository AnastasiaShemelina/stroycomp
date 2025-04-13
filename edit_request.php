<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_GET['id'])) {
    echo "ID заявки не указан.";
    exit();
}

$request_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = mysqli_real_escape_string($conn, $_POST['theme']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $work_type = (int)$_POST['work_type'];

    // Обработка загрузки фото
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = basename($_FILES['photo']['name']);
        $photo_path = 'uploads/' . $photo_name;

        // Перемещаем фото в папку uploads
        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            $photo_sql = ", photo = '$photo_path'";
        } else {
            $photo_sql = "";
        }
    } else {
        $photo_sql = "";
    }

    // Обновление заявки в базе данных
    $query = "UPDATE requests SET title = ?, description = ?, work_type = ? $photo_sql WHERE id = ? AND user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssiii', $theme, $description, $work_type, $request_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: view_request.php?id=$request_id");
        exit();
    } else {
        echo "Ошибка при обновлении заявки.";
    }
} else {
    // Загрузка текущих данных заявки
    $query = "SELECT * FROM requests WHERE id = ? AND user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $request_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        echo "Заявка не найдена или вы не имеете к ней доступ.";
        exit();
    }

    $request = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать заявку</title>
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
        <h2>Редактировать заявку</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="theme">Тема</label>
            <input type="text" name="theme" id="theme" required class="input-field" value="<?php echo htmlspecialchars($request['title']); ?>">

            <label for="description">Описание</label>
            <textarea name="description" id="description" required class="input-field"><?php echo htmlspecialchars($request['description']); ?></textarea>

            <label for="work_type">Тип работ</label>
            <select name="work_type" id="work_type" required class="input-field">
                <?php
                $types_query = "SELECT * FROM work_types";
                $types_result = mysqli_query($conn, $types_query);
                while ($row = mysqli_fetch_assoc($types_result)) {
                    $selected = $row['id'] == $request['work_type'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
            </select><br>

            <!-- Поле для загрузки фото -->
            <label for="photo">Изменить фото</label>
            <input type="file" name="photo" id="photo" class="input-field">

            <button type="submit" class="create-new-request">Сохранить изменения</button>
            <a href="view_request.php?id=<?php echo $request_id; ?>" class="btn">Отмена</a>
        </form>
    </div>
</body>
</html>
