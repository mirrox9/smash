<?php
    //Подключение шапки
    require_once("header.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Раздел новостей</title>
    <style>
        body {
            background-color: #f2f2f2;
        }

        h1 {
            font-family: Arial, sans-serif;
            text-align: center;
            color: #333;
        }

        .news {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .news-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .news-title {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .news-summary {
            font-size: 15px;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .news-link {
            display: inline-block;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .news-link:hover {
            background-color: #555;
        }

        
    </style>
</head>
<body>
    <h1>Раздел новостей</h1>

    <?php
    $target = $_GET['id']; // Получаем значение id из параметра запроса
    if($target != null)
    {
        // Подключение к базе данных и выполнение запроса для получения новости с указанным id
        $sql = "SELECT id, title, text, review, URL FROM news WHERE id = $target";
        $result = mysql_query($sql);

        if ($row = mysql_fetch_row($result)) {
            $newsTitle = $row[1];
            $newsText = $row[2];
            $newsImage = "./newslist/" . $row[0] . ".jpg";
            ?>
            
            <?php
            echo "<div class='news' style='text-align: left;'>";
            echo "<img class='news-image' style='width:auto;height:auto;text-align: left;' src='$newsImage' alt='$newsTitle'>";
            echo "<div>";
            echo "<h2 class='news-title'>$newsTitle</h2>";
            echo "<p class='news-summary'>$newsText</p>";
            echo "<a href='$row[4]'>Источник</a>";
            echo "</div>";
            echo "</div>";
        } 
        else {
            echo "Новость не найдена";
        }
    }
    else 
    {
        $sql = "SELECT id, title, text, review, URL FROM news";
        $result = mysql_query($sql);

        while ($row = mysql_fetch_row($result)) {
            $newsId = $row[0];
            $newsTitle = $row[1];
            $newsReview = $row[3];
            $newsImage = "./newslist/" . $newsId . ".jpg";
            $newsURL = $row[4];

            echo "<div class='news'>";
            echo "<img class='news-image' src='$newsImage'>";
            echo "<div>";
            echo "<h2 class='news-title'>$newsTitle</h2>";
            echo "<p class='news-summary'>$newsReview</p>";
            echo "<a href='news.php?id=$newsId'>Подробнее</a>";
            echo "</div>";
            echo "</div>";
        }
    }
    ?>

</body>
</html>
