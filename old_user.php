<?php
    //Подключение шапки
    require_once("header.php");
    $auth =  $_SESSION['auth'];
    if(!$auth)
    {
        ?>     
            <script>
            function loadPage()
            {
                <?php
                    echo "document.location.href = './auth.php';"
                ?>
            }
           window.onload = loadPage();
            </script>
        <?php
    }
?>
<html>
<head>
<title>Личный кабинет</title>
<link rel="stylesheet" type="text/css" href="old_user_style.css">
<style>
    .buttons {
  display: inline-block;
  margin-right: 10px;
}

</style>
</head>
<body>
    <div class="content">
        <div class="panel">
            <h1>Личный кабинет</h1>
            
            <?php
            $userid = $_SESSION['userid'];
            $u = mysql_query("SELECT * FROM users WHERE userid = '$userid'");
            $user = mysql_fetch_row($u);
            ?>
            
            <h2>Информация об аккаунте:</h2>
            <p><strong>ID:</strong> <?php echo $user[0]; ?></p>
            <p><strong>Логин:</strong> <?php echo $user[1]; ?></p>
            <p><strong>Email:</strong> <?php echo $user[3]; ?></p>
            <p><strong>Дата регистрации:</strong> <?php echo $user[4]; ?></p>
            
            <h2>Смена пароля:</h2>
            <form action="" method="POST">
            <label for="current_password">Текущий пароль:</label>
            <input type="password" id="current_password" name="current_password" required><br>

            <label for="new_password">Новый пароль:</label>
            <input type="password" id="new_password" name="new_password" required><br>

            <label for="confirm_password">Подтвердите новый пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" required><br>

            <input type="submit" value="Сменить пароль">
            </form>
            <?
            if($userid == 1)
            {?>
                <form action="./news_edit.php" method="POST">
                <input type="submit" value="Управление новостями">
                </form>
                <form action="./materials_edit.php" method="POST">
                <input type="submit" value="Управление материалами">
                </form>
                <form action="./users_edit.php" method="POST">
                <input type="submit" value="Управление пользователями">
                </form>
            <?} ?>

            <?php
            // Проверка отправки формы
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Получение данных из формы
                $currentPassword = $_POST['current_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];

                // Проверка текущего пароля и совпадения нового пароля с подтверждением
                if ($currentPassword == $user[2] && $newPassword == $confirmPassword) {
                    // Обновление пароля пользователя в базе данных
                    $update_sql = mysql_query("UPDATE `smash`.`users` SET `password` = '$newPassword' WHERE `users`.`userid` = $userid;");
                    mysql_fetch_row($update_sql );
                    echo "<p>Пароль успешно изменен.</p>";
                } else {
                    echo "<p>Ошибка при смене пароля. Пожалуйста, проверьте введенные данные.</p>";
                }
            }
            ?>
        </div>

        <div class="analysis-block">
        <h2>Список анализов</h2>
        
        <?php
        // Получение анализов пользователя
        $analyses = mysql_query("SELECT * FROM an_list WHERE userid = '$userid'");   
        // Вывод анализов пользователя
        while ($analysis = mysql_fetch_row($analyses)) {
            echo '<p><strong>ID анализа:</strong> ' . $analysis[0] . '</p>';
            echo '<p><strong>ID пользователя:</strong> ' . $analysis[1] . '</p>';
            echo '<p><strong>Тип анализа:</strong> ' . $analysis[2] . '</p>';
            echo '<p><strong>Дата анализа:</strong> ' . $analysis[3] . '</p>';
            echo "<span class='buttons'><a href='target_result.php?id=$analysis[0]' target='_blank'>Подробнее</a></span>";
            echo "<span class='buttons'><a href='delete_result.php?id=$analysis[0]' target='_blank'>Удалить</a></span>";            
            echo '<hr>';
        }
        ?>
        </div>
    </div>
</body>
</html>