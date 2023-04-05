<?php
// Подключаемся к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "testq";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем ответы пользователя
  $user_answers = $_POST['user_answer'];

  // Проверяем, что пользователь дал ответы на вопросы
  if (!empty($user_answers)) {
    // Перебираем ответы и проверяем их на правильность
    $num_correct = 0;
    foreach ($user_answers as $question_id => $answer_id) {
      $sql = "SELECT correct_answer FROM questions WHERE id = $question_id";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      if ($answer_id == $row['correct_answer']) {
        $num_correct++;
      }
    }
    // Выводим результат пользователю
    $result_msg = "Вы ответили правильно на $num_correct из " . count($user_answers) . " вопросов.";
  } else {
    // Если пользователь не дал ответы на вопросы, выводим сообщение об ошибке
    $result_msg = "Вы не дали ответы на вопросы.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Задание соотношений</title>
</head>
<body>
  <h1>Задание соотношений</h1>
  <form action="tablequest.php" method="post"> <!-- исправлено -->
    <?php
    // Получаем список вопросов из базы данных
    $sql = "SELECT * FROM questions";
    $result = mysqli_query($conn, $sql);

    // Выводим каждый вопрос и его ответы в виде выпадающих списков
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<p>" . $row['question'] . "</p>";
      echo "<select name='user_answer[" . $row['id'] . "]'>";
      echo "<option value=''>--Выберите ответ--</option>";
      // Получаем список ответов для данного вопроса из базы данных
      $sql_answers = "SELECT * FROM answers WHERE question_id = " . $row['id'];
      $result_answers = mysqli_query($conn, $sql_answers);
      while ($row_answer = mysqli_fetch_assoc($result_answers)) {
        echo "<option value='" . $row_answer['id'] . "'>" . $row_answer['answer'] . "</option>";
      }
      echo "</select>";
    }
    ?>
    <br><br>
    <input type="submit" value="Отправить">
  </form>
  <?php
  // Выводим результат пользователю, если он уже отправил форму
  if (isset($result_msg)) {
    echo "<p>" . $result_msg . "</p>";
  }
  ?>
</body>
</html>