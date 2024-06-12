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
    $date = mysql_fetch_row(mysql_query("SELECT NOW() AS current_datetime"));
    $products = $_POST['products'];
    $abc_group = $_POST['abc_group'];
    $xyz_group = $_POST['xyz_group'];
    if(count($abc_group)>0)
    {
        if(count($xyz_group)>0)
        {
            $type = "ABC XYZ";
        }
        else
        {
            $type = "ABC";
        }
    }
    else
    {
        $type = "XYZ";
    }

    $addAn = mysql_query("INSERT INTO `smash`.`an_list` (`userid`, `type`, `date`) VALUES ('$userid', '$type', '$date[0]')");
    $nextPrimaryKey = mysql_insert_id();
    for($i = 0; $i < count($products); $i++)
    {
        switch ($abc_group[$i])
        {
            case 1:
                $abc = "A";
                break;
            case 2:
                $abc = "B";
                break;
            case 3:
                $abc = "C";
                break;
        }

        switch ($xyz_group[$i])
        {
            case 1:
                $xyz = "X";
                break;
            case 2:
                $xyz = "Y";
                break;
            case 3:
                $xyz = "Z";
                break;
        }

        switch ($abc_group[$i])
        {
            case "A":
                $abc = "A";
                break;
            case "B":
                $abc = "B";
                break;
            case "C":
                $abc = "C";
                break;
        }

        switch ($xyz_group[$i])
        {
            case "X":
                $xyz = "X";
                break;
            case "Y":
                $xyz = "Y";
                break;
            case "Z":
                $xyz = "Z";
                break;
        }
        mysql_query("INSERT INTO `smash`.`an_el` (`anid`, `name`, `ABC`, `XYZ`) VALUES ('$nextPrimaryKey', '$products[$i]', '$abc', '$xyz');");
    }


    if(mysql_affected_rows() > 0)
    {
        echo "<h2>Анализ сохранен!<h2>";
    }
    else
    {
        echo "<h2>Ошибка при сохранении!<h2>";
    }
?>
 <html>
    <head>
        <title>Сохранение анализа</title>
    </head>
 </html>