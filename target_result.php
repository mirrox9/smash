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
    <title>Результат анализа</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type='number'] {
            width: 80px;
        }

        button[type='submit'] {
            margin-top: 10px;
        }

        .button {
        background-color: #2786eb;
        color: #fff;
        display: block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
        }

        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
            }
</style>
    </head>
    <body>
    <?php
    if (isset($_GET['id'])) {
        $anid= $_GET['id'];
        $check = mysql_fetch_row(mysql_query("SELECT * FROM an_list WHERE anid = '$anid' and userid = '$userid'"));
        if(count($check)>1)
        {
            $result = mysql_query("SELECT * FROM an_el WHERE anid = '$anid'");
            echo "<h2>Результат анализа $anid<h2>";
            ?>
             <table>
                <thead>
                    <tr>
                    <th>Продукт</th>
                    <th>ABC категория</th>
                    <th>XYZ категория</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                      while ($row= mysql_fetch_row($result))
                      {
                          echo "<tr>";
                          echo "<td>$row[2]</td>";
                          echo "<td>$row[3]</td>";
                          echo "<td>$row[4]</td>";
                          echo "</tr>";
                      }
                        ?>
                </tbody>
                </table>
            <?php
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