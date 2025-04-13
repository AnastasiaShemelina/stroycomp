<?php
// Подключение к базе данных
include 'config.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ООО "Уют-Сервис"</title>
    <link rel="stylesheet" href="styles/main_style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <nav>
        <a href="register.php">Регистрация</a>
        <a href="login.php">Вход</a>
    </nav>
</header>

<main>
    <!-- Блок данных о компании -->
    <section class="company-info">
        <div class="company-slogan">
            <h1>ВАШ УЮТ –</h1>
            <h1>НАША ЗАБОТА</h1>
        </div>
        <div class="company-description">
            <h2>О компании</h2>
            <p>ООО "Уют-Сервис" — компания, предоставляющая услуги по инженерным коммуникациям и общестроительным работам. Мы обеспечиваем качественное выполнение работ по доступным ценам.</p>
            <h3>История компании</h3>
            <p>Мы начали свою деятельность в 2010 году с целью предоставить качественные строительные и инженерные услуги в Удмуртии. С тех пор мы уверенно развиваемся, зарекомендовав себя как надежный партнер для множества клиентов.</p>
            <h3>Чем мы занимаемся</h3>
            <p>Наши услуги включают монтаж сантехники, электрики, установку потолков, дверей и другие общестроительные работы. Мы работаем с частными клиентами и корпоративными заказами.</p>
        </div>
    </section>

    <!-- Примеры работ -->
    <section class="examples">
        <h2>Примеры наших работ</h2>
        <div class="example-slider">
            <?php
            $query = "SELECT examples.id, examples.title, examples.description, examples.photo, work_types.title AS work_type
                      FROM examples
                      JOIN work_types ON examples.work_type = work_types.id";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='example-slide card'>
                        <h3 class='card-title'>" . htmlspecialchars($row['title']) . "</h3>";
            
                if (!empty($row['photo'])) {
                    $image_id = $row['id']; 
                    echo "<div class='card-image'>
                            <img src='get_image.php?id=$image_id' alt='Пример работы'>
                          </div>";
                }
            
                echo "<p class='card-description'>" . htmlspecialchars($row['description']) . "</p>
                    </div>";
            }
                    
            ?>
        </div>
        <div class="slider-controls">
            <button class="prev" id="prev-example">←</button>
            <button class="next" id="next-example">→</button>
        </div>
    </section>

    <!-- Отзывы -->
    <section class="feedbacks">
        <h2>Отзывы о компании</h2>
        <div class="feedback-slider">
            <?php
            $query = "SELECT feedbacks.id, feedbacks.title, feedbacks.description, feedbacks.datetime, users.full_name
                    FROM feedbacks
                    JOIN users ON feedbacks.user = users.id
                    WHERE feedbacks.status = 1";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $formatted_date = date("d.m.Y", strtotime($row['datetime']));
                echo "<div class='feedback-slide card'>
                        <h4 class='card-title'>" . htmlspecialchars($row['title']) . "</h4>
                        <p class='card-description'>" . htmlspecialchars($row['description']) . "</p>
                        <div class='card-footer'>
                            <p class='card-user'>" . htmlspecialchars($row['full_name']) . "</p>
                            <p class='card-date'>" . $formatted_date . "</p>
                        </div>
                    </div>";
            }
            ?>
        </div>
        <div class="slider-controls">
            <button class="prev" id="prev-feedback">←</button>
            <button class="next" id="next-feedback">→</button>
        </div>
    </section>


    <!-- Контактная информация -->
    <section class="contacts">
        <h2>Контактная информация</h2>
        <div class="contacts-info">
            <div class="map">
                <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ad09af845a971bda336b5d5df27fb7f0f6545aa34979f006ba321b1d522371c21&amp;source=constructor" width="743" height="400" frameborder="0"></iframe>
            </div>
            <div class="info">
                <p><strong>Телефон:</strong> +7 123 456 78 90</p>
                <p><strong>Email:</strong> info@uyutservice.ru</p>
                <p><strong>Наши офисы:</strong> г. Ижевск, ул. Строителей, 15</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 ООО "Уют-Сервис" | Все права защищены</p>
    </div>
</footer>

<script>
    $(document).ready(function() {
        let exampleIndex = 0;
        let feedbackIndex = 0;

        function updateSlider(slider, index) {
            const slides = slider.find('.example-slide, .feedback-slide');
            const totalSlides = slides.length;
            if (index >= totalSlides) index = 0;
            if (index < 0) index = totalSlides - 1;
            slides.stop(true, true).fadeOut(200);
            slides.eq(index).fadeIn(200);
        }

        $('#prev-example').click(function() {
            exampleIndex--;
            updateSlider($('.example-slider'), exampleIndex);
        });

        $('#next-example').click(function() {
            exampleIndex++;
            updateSlider($('.example-slider'), exampleIndex);
        });

        $('#prev-feedback').click(function() {
            feedbackIndex--;
            updateSlider($('.feedback-slider'), feedbackIndex);
        });

        $('#next-feedback').click(function() {
            feedbackIndex++;
            updateSlider($('.feedback-slider'), feedbackIndex);
        });

        updateSlider($('.example-slider'), exampleIndex);
        updateSlider($('.feedback-slider'), feedbackIndex);
    });
</script>
</body>
</html>
