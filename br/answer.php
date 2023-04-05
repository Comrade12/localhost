<?php
session_start();
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}

// добавляем ответ в сессию
$answer = $_POST['answer'];
array_push($_SESSION['answers'], $answer);

// проверяем, сколько вопросов было отвечено
$db = new mysqli("localhost", "root", "root", "mytest");
$result = $db->query("SELECT COUNT(*) AS count FROM questions");
$row = $result->fetch_assoc();
$question_count = $row['count'];

// проверяем, отвечены ли все вопросы
if (count($_SESSION['answers']) == $question_count) {
    // вычисляем количество правильных ответов
    $correct_answers = 0;
    foreach ($_SESSION['answers'] as $answer) {
        $result = $db->query("SELECT * FROM questions WHERE correct_answer='$answer'");
        if ($result->num_rows > 0) {
            $correct_answers++;
        }
    }

    // сохраняем результаты и переходим на страницу с результатами
    $score = round($correct_answers / $question_count * 100);
    $db->query("INSERT INTO results (score) VALUES ($score)");
    unset($_SESSION['answers']);
    $response = array("status" => "finished");
} else {
    $response = array("status" => "success");
}


echo json_encode($response);
