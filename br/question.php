<?php
// подключаемся к базе данных и выбираем случайный вопрос
$db = new mysqli("localhost", "root", "root", "mytest");
$result = $db->query("SELECT * FROM questions ORDER BY RAND() LIMIT 1");
$question = $result->fetch_assoc();

// выбираем случайные варианты ответов
$answers = array($question['correct_answer'], $question['wrong_answer1'], $question['wrong_answer2'], $question['wrong_answer3']);
shuffle($answers);
?>

<h3><?php echo htmlspecialchars($question['question']); ?></h3>
<?php foreach ($answers as $answer) { ?>
    <input type="radio" name="choice" value="<?php echo htmlspecialchars($answer); ?>" required> <?php echo nl2br(htmlspecialchars($answer)); ?><br>
<?php } ?>