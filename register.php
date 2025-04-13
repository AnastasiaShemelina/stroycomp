<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = 2;

    // Приведение номера к формату +7XXXXXXXXXX
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) == 11 && $phone[0] == '8') {
        $phone[0] = '7';
    }
    $phone = '+'.$phone;

    $query = "INSERT INTO users (login, password, full_name, phone, email, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $login, $password, $full_name, $phone, $email, $role);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['role'] = $role;
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "Ошибка регистрации, попробуйте снова.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles/forms_style.css">
</head>
<body>
    <div class="page-container">
        <a class="back-home-link" href="index.php">Уют-Сервис</a>
        <div class="form-container">
            <h2>Регистрация</h2>
            <form action="register.php" method="POST">
                <input type="text" name="login" placeholder="Логин" required><br>
                <input type="password" name="password" placeholder="Пароль" required><br>
                <input type="text" name="full_name" placeholder="ФИО" required><br>
                <input type="text" name="phone" placeholder="Телефон" required><br>
                <input type="email" name="email" placeholder="Email" required><br>

                <?php if (isset($error)) : ?>
                    <p class="error-message"><?= $error ?></p>
                <?php endif; ?>

                <button type="submit">Зарегистрироваться</button>
            </form>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </div>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        const phoneInput = document.querySelector('input[name="phone"]');
        IMask(phoneInput, {
            mask: '+7 (000) 000-00-00',
            placeholder: '+7 (9', // Это покажет пользователю правильный формат сразу
            prepare: function(value) {
                return value.replace(/^\+?7/, '+7'); // Очищаем ввод от 8 или других символов
            }
        });
    </script>
</body>
</html>
