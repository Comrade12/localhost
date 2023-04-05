<?php
session_start();

// подключение к базе данных
$db_host = 'localhost';
$db_name = 'mytest';
$db_user = 'root';
$db_pass = 'root';
$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

// получение результата теста из сессии
if (isset($_SESSION['score'])) {
    $score = $_SESSION['score'];
    
    // добавление результата в базу данных
    $stmt = $db->prepare("INSERT INTO results (score) VALUES (:score)");
    $stmt->bindParam(':score', $score);
    $stmt->execute();

    // вывод результата пользователю
    echo "<h1>Your score is: $score</h1>";

    // завершение сессии
    session_unset();
} else {
    echo "Ошибка: результаты теста не найдены.";
}
?>