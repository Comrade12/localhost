<?php
// установка соединения с базой данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $question = $_POST['question'];
  $answers = $_POST['answers'];
  $correct_answers = $_POST['correct_answers'];

  // вставка вопроса в базу данных
  $sql = "INSERT INTO questions (question) VALUES ('$question')";
  if ($conn->query($sql) === TRUE) {
    $question_id = $conn->insert_id;

    // вставка ответов в базу данных
    foreach ($answers as $index => $answer) {
      $is_correct = in_array($index, $correct_answers) ? 1 : 0;
      $sql = "INSERT INTO answers (question_id, answer, is_correct) VALUES ($question_id, '$answer', $is_correct)";
      $conn->query($sql);
    }

    echo "Вопрос успешно создан";
  } else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
  }
}

// вывод формы для создания тестов
?>

<form method="POST">
  <label for="question">Вопрос:</label>
  <input type="text" id="question" name="question" required><br>
<label>Ответы:</label><br>

  <div id="answers-container">
    <div>
      <input type="text" name="answers[]" required>
      <input type="checkbox" name="correct_answers[]" value="0">
      <button type="button" onclick="removeAnswer(this)">Удалить</button>
    </div>
  </div>
  <button type="button" onclick="addAnswer()">Добавить ответ</button><br>
<button type="submit">Создать вопрос</button>

</form>
<script>
function addAnswer() {
  const container = document.getElementById('answers-container');
  const answerCount = container.children.length;
  const div = document.createElement('div');
  div.innerHTML = `
    <input type="text" name="answers[]" required>
    <input type="checkbox" name="correct_answers[]" value="${answerCount}">
    <button type="button" onclick="removeAnswer(this)">Удалить</button>
  `;
  container.appendChild(div);
}

function removeAnswer(button) {
  button.parentNode.remove();
}
</script>
<a href="index.php">ВЕРНУТТСЯ</a>