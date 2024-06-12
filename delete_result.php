<?php
    //Подключение шапки
    require_once("header.php");
    $list = $_SERVER['REQUEST_URI'];
    $userid = $_SESSION['userid'];
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
    <title>Удаление анализа</title>          
</style>
    </head>
    <body>
    <?php
    if (isset($_GET['id'])) {
        $anid= $_GET['id'];
        $check = mysql_fetch_row(mysql_query("SELECT * FROM an_list WHERE anid = '$anid' and userid = '$userid'"));
        if(count($check)>1)
        {
            mysql_query("DELETE FROM an_list WHERE anid = '$anid'");
            mysql_query("DELETE FROM an_el WHERE anid = '$anid'");
            if(mysql_affected_rows() > 0)
            {
                echo "<h2>Анализ удален!<h2>";
            }
            else
            {
                echo "<h2>Ошибка при удалении!<h2>";
            }
        }
        else 
        {
            echo "<h2>Детали анализа не доступны для вас!<h2>";
        }
    } 
    else {
    echo "<h2>ID анализа не указан!<h2>";
    }
    ?>

    </body>
</html>