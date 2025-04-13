# Сайт строительной компании

**StroyComp** — это сайт строительной компании, находящийся в стадии разработки. Цель проекта — предоставить пользователям информацию о компании, возможность оставить заявку или отзыв, а администратору — управлять контентом сайта через удобную админ-панель.

## Возможности

- Просмотр информации о компании
- Регистрация и авторизация пользователей
- Отправка заявок и отзывов для авторизованных пользователей
- Админ-панель для:
  - Модерации заявок и отзывов
  - Добавления примеров выполненных работ (отображаются на главной)

## Используемые технологии

- HTML
- CSS
- JavaScript + jQuery
- PHP
- MySQL 

## Установка и запуск (через OpenServer)

1. **Скачайте и установите OpenServer:**  

2. **Поместите проект в папку `domains`**, например:  
   `C:\OpenServer\domains\stroycomp`

3. **Настройте домен:**  
   В OpenServer → Настройки → Домены → добавить:  
   `stroycomp.local`

4. **Запустите OpenServer**, выберите PHP-версию, и нажмите «Старт».

5. **Создайте базу данных:**  
   - Зайдите в phpMyAdmin (`http://localhost/phpmyadmin`)  
   - Создайте БД с именем **`stroycomp`**
   - Импортируйте дамп `stroycomp.sql`

