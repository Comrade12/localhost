<?php
// подключаемся к базе данных
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// получаем список вопросов и ответов из базы данных
$sql = "SELECT id, question_text, user_answer, correct_answer FROM tablee";
$result = $conn->query($sql);

$tablee = array();
while ($row = $result->fetch_assoc()) {
  $tablee[$row["id"]] = array(
    "text" => $row["question_text"],
    "user_answer" => $row["user_answer"],
    "correct_answer" => $row["correct_answer"]
  );
}

// закрываем соединение с базой данных
$conn->close();
?>

<!-- начало Html-кода для вывода результатов теста -->
<table>
  <thead>
    <tr>
      <th>Вопрос</th>
      <th>Ответ пользователя</th>
      <th>Правильный ответ</th>
      <th>Результат</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tablee as $id => $question) { ?>
      <tr>
        <td><?php echo $question["text"]; ?></td>
        <td><?php echo $question["user_answer"]; ?></td>
        <td><?php echo $question["correct_answer"]; ?></td>
        <td><?php if ($question["user_answer"] == $question["correct_answer"]) {
                  echo "Верно";
                } else {
                  echo "Неверно";
                } ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<!-- конец Html-кода для вывода результатов теста -->