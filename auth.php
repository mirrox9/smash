<?php
    //Подключение шапки
    require_once("header.php");
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
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="headerstyle.css">
    </head>
    <body>
    <div id="loginForm" style="display:block" class="form">
      <h2>Вход</h2>
      <span class="close-btn"></span>
      <form action="./auth.php?check=true" method="POST">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="password" placeholder="Пароль">
        <button>Войти</button>
        <?php
                $login = $_POST['login'];
		        $password = $_POST['password'];
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
                else
                {
                    echo "<h3>Неправильный логин или пароль!</h3>";
                }           
            ?>
      </form>
    </div>
    </body>
</html>

