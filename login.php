<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 1) {
        header("Location: admin_panel.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = $_POST['login_or_email_or_phone'];
    $password = $_POST['password'];

    // Приведение к единому виду, если это телефон
    if (preg_match('/^\+?\d[\d\s\-\(\)]{9,}$/', $login_input)) {
        $cleaned = preg_replace('/\D/', '', $login_input);
        if (strlen($cleaned) == 11 && $cleaned[0] == '8') {
            $cleaned[0] = '7';
        }
        $login_input = '+'.$cleaned;
    }

    $query = "SELECT id, password, role FROM users WHERE login = ? OR email = ? OR phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $login_input, $login_input, $login_input);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            if ($role == 1) {
                header("Location: admin_panel.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error_message = "Неверный логин или пароль.";
        }
    } else {
        $error_message = "Пользователь с таким логином, email или телефоном не найден.";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="styles/forms_style.css">
</head>
<body>
    <div class="page-container">
        <a class="back-home-link" href="index.php">Уют-Сервис</a>
        <div class="form-container">
            <h2>Вход</h2>
            <form method="POST">
                <input type="text" name="login_or_email_or_phone" placeholder="Логин или Email" required><br>
                <input type="password" name="password" placeholder="Пароль" required><br>

                <?php if (isset($error_message)) : ?>
                    <p class="error-message"><?= $error_message ?></p>
                <?php endif; ?>

                <button type="submit">Войти</button>
            </form>
            <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </div>
</body>
</html>
