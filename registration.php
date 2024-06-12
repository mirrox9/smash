<?php
    //Подключение шапки
    require_once("header.php");
    $list = $_SERVER['REQUEST_URI'];
    $auth =  $_SESSION['auth'];
    if($auth)
    {
        ?>     
            <script>
            function loadPage()
            {
                <?php
                    echo "document.location.href = './old_user.php';"
                ?>
            }
           window.onload = loadPage();
            </script>
        <?php
    }
?>

<html>
    <head>
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="headerstyle.css">
    </head>
    <body>
    <div id="registerForm" style="display:block" class="form">
      <h2>Регистрация</h2>
      <form action="./registration.php" method="POST">
        <input type="text" placeholder="Логин">
        <input type="password" placeholder="Пароль">
        <input type="email" placeholder="Email">
        <button>Зарегистрироваться</button>
        <?php
         $login = $_POST['login'];
         $password = $_POST['password'];
         $email = $_POST['email'];  
        if (empty($login) || empty($password) || empty($email)) {
            // Вывод сообщения об ошибке
            echo "<h3> Пожалуйста, заполните все поля </h3>";
        } else {
            // Проверка соответствия паттерну электронной почты
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Вывод сообщения об ошибке
                echo "<h3> Пожалуйста, введите корректный адрес электронной почты </h3>";
            } else 
            {
               // Подготовка SQL-запроса для проверки наличия пользователя с указанным логином         
                    $result = mysql_query("SELECT * FROM users WHERE login = '$login'");
                    $date = mysql_fetch_row(mysql_query("SELECT NOW() AS current_datetime"));
                    // Проверка наличия результатов
                    if (mysql_fetch_row($result)) {
                        // Вывод сообщения о существующем пользователе
                        echo "<h3> Пользователь с таким логином уже существует </h3>";
                    } else {
                        // Подготовка SQL-запроса для добавления нового пользователя
                        // Выполнение SQL-запроса
                        $add = mysql_query("INSERT INTO users (login, password, email, date) VALUES ('$login', '$password', '$email', '$date[0]')");
                        mysql_fetch_row($add);
                        $check = mysql_query("SELECT * FROM users WHERE login = '$login'");
                        if (mysql_fetch_row($check)) {
                            //Переход в личный кабинет
                            $u = mysql_query("SELECT * FROM users WHERE login='$login' AND password='$password'");
                            $user = mysql_fetch_row($u);
                            if (!empty($user)) 
                            {      
                                ?>     
                                 <script>
                                function loadPage()
                                {
                                    <?php
                                    echo "document.location.href = './old_user.php';"
                                    ?>
                                }
                                window.onload = loadPage();
                                </script>
                                <?php
                                $_SESSION['auth'] = true;
                                $_SESSION['userid'] = $user[0];

                            }
                        } 
                        else {
                            echo "Ошибка при регистрации";
                        }
                    }
            }
        }
        ?>
      </form>
    </div>   
    </body>
</html>