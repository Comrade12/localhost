<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Для НИИ ALPHA 1.0</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            var count = 0;
            var timer = setInterval(function() {
                count++;
                if (count == 600) { // 10 минут
                    clearInterval(timer);
                    window.location.href = "timeout.php";
                } else {
                    $("#timer").text(600 - count);
                }
            }, 1000);
            showQuestion();
        });

        function showQuestion() {
            $.ajax({
                url: "question.php",
                type: "GET",
                dataType: "html",
                success: function(data) {
                    $("#question").html(data);
                }
            });
        }

        function submitAnswer() {
            var answer = $("input[name='answer']:checked").val();
            if (!answer) {
                alert("Выберите один вариант ответа!");
            } else {
                $.ajax({
                    url: "answer.php",
                    type: "POST",
                    data: {"answer": answer},
                    dataType: "json",
                    success: function(data) {
                        if (data.status == "success") {
                            showQuestion();
                        } else if (data.status == "finished") {
                            clearInterval(timer);
                            window.location.href = "result.php";
                        } else {
                            alert("Произошла ошибка при сохранении ответа!");
                        }
                    }
                });
            }
        }
    </script>
    <style>
        #question {
            margin: 0 auto;
            width: 50%;
        }
    </style>
</head>
<body>
    <div id="timer">600</div>
    <div id="question"></div>
    <button onclick="submitAnswer()">Ответить</button>
</body>
</html>
