<?php
// Подключение к базе данных
include('config.php'); // Путь к вашему конфигу с подключением к базе

// Получаем ID изображения (или другой параметр для уникальности)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Запрос на получение изображения из базы данных
    $query = "SELECT photo FROM examples WHERE id = $id";
    $result = mysqli_query($conn, $query);

    // Если нашли изображение
    if ($row = mysqli_fetch_assoc($result)) {
        $photo_data = $row['photo'];

        // Устанавливаем заголовки для изображения
        header("Content-Type: image/jpeg"); // Если у вас jpeg изображение
        echo $photo_data; // Выводим изображение
    } else {
        // Если изображение не найдено
        header("HTTP/1.0 404 Not Found");
    }
} else {
    // Если ID не передан
    header("HTTP/1.0 400 Bad Request");
}
?>
