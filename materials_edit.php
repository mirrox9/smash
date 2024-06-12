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

<!DOCTYPE html>
<html>
<head>
    <title>Административная панель</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <h1>Административная панель</h1>

    <?php
    // Добавление новости
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_articles'])) {
        $title = $_POST['title'];
        $text = $_POST['text'];
        $review = $_POST['review'];

        $sql = "INSERT INTO articles (title, text, review) VALUES ('$title', '$text', '$review')";
        $result = mysql_query($sql);

        if ($result) {
            echo "Статья успешно добавлена";
        } else {
            echo "Ошибка при добавлении новости: " . mysql_error();
        }
    }

    // Редактирование новости
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_articles'])) {
        $id = $_POST['articles_id'];
        $title = $_POST['title'];
        $text = $_POST['text'];
        $review = $_POST['review'];

        $sql = "UPDATE articles SET title='$title', text='$text', review='$review' WHERE id=$id";
        $result = mysql_query($sql);

        if ($result) {
            echo "Статья успешно обновлена";
        } else {
            echo "Ошибка при обновлении новости: " . mysql_error();
        }
    }

    // Удаление новости
    if (isset($_GET['delete_articles'])) {
        $id = $_GET['delete_articles'];

        $sql = "DELETE FROM articles WHERE id=$id";
        $result = mysql_query($sql);

        if ($result) {
            echo "Статья успешно удалена";
        } else {
            echo "Ошибка при удалении новости: " . mysql_error();
        }
    }
    ?>

    <!-- Форма добавления и редактирования новости -->
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <?php
        if (isset($_GET['edit_articles'])) {
            $id = $_GET['edit_articles'];
            $sql = "SELECT * FROM articles WHERE id=$id";
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);
        ?>
        <input type="hidden" name="articles_id" value="<?php echo $row['id']; ?>">
        <label>Заголовок:</label><br>
        <input type="text" name="title" value="<?php echo $row['title']; ?>"><br>
        <label>Текст:</label><br>
        <textarea name="text"><?php echo $row['text']; ?></textarea><br>
        <label>Обзор:</label><br>
        <textarea name="review"><?php echo $row['review']; ?></textarea><br>
        <input type="submit" style="margin-top: 10px;" name="edit_articles" value="Обновить статью">
        <?php
        } else {
        ?>
            <h1>Добавление статьи</h1>
            <label>Заголовок:</label><br>
            <input type="text" name="title"><br>
            <label>Текст:</label><br>
            <textarea name="text"></textarea><br>
            <label>Обзор:</label><br>
            <textarea name="review"></textarea><br>
            <input type="submit" style="margin-top: 10px;" name="add_articles" value="Добавить статью" >
        <?php
        }
        ?>
    </form>

    <!-- Таблица новостей -->
    <table>
        <tr>
            <th>ID</th>
            <th>Заголовок</th>
            <th>Текст</th>
            <th>Обзор</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM articles";
        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['text']."</td>";
            echo "<td>".$row['review']."</td>";
            echo "<td><a href='materials_edit.php?edit_articles=".$row['id']."'>Редактировать</a> | <a href='materials_edit.php?delete_articles=".$row['id']."' onclick='return confirm(\"Вы уверены, что хотите удалить эту статью?\")'>Удалить</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>
