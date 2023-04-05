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

// получение вопросов и ответов из базы данных
$sql = "SELECT q.id, q.question, a.id AS answer_id, a.answer, a.is_correct FROM questions q JOIN answers a ON q.id = a.question_id ORDER BY q.id, a.id";
$result = $conn->query($sql);

// группировка вопросов и ответов
$questions = array();
while ($row = $result->fetch_assoc()) {
  if (!isset($questions[$row['id']])) {
    $questions[$row['id']] = array(
      'id' => $row['id'],
      'question' => $row['question'],
      'answers' => array()
    );
  }
  $questions[$row['id']]['answers'][] = array(
    'id' => $row['answer_id'],
    'answer' => $row['answer'],
    'is_correct' => $row['is_correct']
  );
}


//ОБРАБОТКА ОТПРАВЛЕННОЙ ФОРМЫ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $score = 0;
  foreach ($questions as $question) {
    $num_correct_answers = 0;
    $num_selected_answers = 0;
    $num_correct_selected_answers = 0;

    foreach ($question['answers'] as $answer) {
      $is_selected = isset($_POST['answer_' . $answer['id']]);
      $is_correct = $answer['is_correct'] == 1;

      if ($is_correct) {
        $num_correct_answers++;
      }

      if ($is_selected) {
        $num_selected_answers++;

        if ($is_correct) {
          $num_correct_selected_answers++;
        }
      }
    }


    // обработка вопросов с одним правильным ответом
    if ($num_correct_answers == 1) {
      if ($num_correct_selected_answers == 1 && $num_selected_answers == 1) {
        $score++;
      }
    } else if ($num_correct_answers == 2) {
      if ($num_correct_selected_answers == 2 && $num_selected_answers >= 2 && $num_selected_answers <= $num_correct_answers) {
        $score++;
      }
    } else if ($num_correct_answers == 3) {
      if ($num_correct_selected_answers == 3 && $num_selected_answers >= 3 && $num_selected_answers <= $num_correct_answers) {
        $score++;
      }
    } else if ($num_correct_answers == 4) {
      if ($num_correct_selected_answers == 4 && $num_selected_answers >= 4 && $num_selected_answers <= $num_correct_answers) {
        $score++;
      }
    }
  }

  echo "Количество правильных ответов: $score / " . count($questions);
  exit();
}

// вывод вопросов и ответов
?>
<form method="POST">
  <?php foreach ($questions as $question): ?>
    <div class="test-question"><?= $question['question'] ?></div>
    <?php foreach ($question['answers'] as $answer): ?>
      <div class="test-answer">
        <input type="checkbox" name="answer_<?= $answer['id'] ?>" value="<?= $answer['answer'] ?>">
        <label><?= $answer['answer'] ?></label>
      </div>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <button type="submit">Проверить</button>
</form>
<a href="create.php">АОАОАОАОАО</a>