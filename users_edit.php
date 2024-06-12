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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_users'])) {
        $id = $_POST['id'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $date = $_POST['date'];

        $sql = "INSERT INTO users (login, password, email, date) VALUES ('$login', '$password', '$email','$date')";
        $result = mysql_query($sql);

        if ($result) {
            echo "Пользователь успешно добавлен";
        } else {
            echo "Ошибка при добавлении пользователя: " . mysql_error();
        }
    }

    // Редактирование новости
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_users'])) {
        $id = $_POST['userid'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $date = $_POST['date'];

        $sql = "UPDATE users SET userid='$id', login='$login', password='$password', email='$email', date='$date' WHERE userid=$id";
        $result = mysql_query($sql);

        if ($result) {
            echo "Данные пользователя успешно обновлены";
        } else {
            echo "Ошибка при обновлении данных пользователя: " . mysql_error();
        }
    }

    // Удаление новости
    if (isset($_GET['delete_users'])) {
        $id = $_GET['delete_users'];

        $sql = "DELETE FROM users WHERE userid=$id";
        $result = mysql_query($sql);

        if ($result) {
            echo "Пользователь успешно удален";
        } else {
            echo "Ошибка при удалении пользователя: " . mysql_error();
        }
    }
    ?>

    <!-- Форма добавления и редактирования новости -->
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <?php
        if (isset($_GET['edit_users'])) {
            $id = $_GET['edit_users'];
            $sql = "SELECT * FROM users WHERE userid=$id";
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);
        ?>
        <input type="hidden" name="users_id" value="<?php echo $row[0]; ?>">
        <label>ID:</label><br>
        <input type="text" name="userid" value="<?php echo $row['userid']; ?>"><br>
        <label>Логин:</label><br>
        <textarea name="login"><?php echo $row['login']; ?></textarea><br>
        <label>Пароль:</label><br>
        <textarea name="password"><?php echo $row['password']; ?></textarea><br>
        <label>Почта:</label><br>
        <textarea name="email"><?php echo $row['email']; ?></textarea><br>
        <label>Дата регистрации:</label><br>
        <textarea name="date"><?php echo $row['date']; ?></textarea><br>
        <input type="submit" style="margin-top: 10px;" name="edit_users" value="Обновить данные пользователя">
        <?php
        } else {
        ?>
            <h1>Добавление пользователя</h1>
            <label>ID:</label><br>
            <input type="text" name="id"><br>
            <label>Логин:</label><br>
            <input type="text" name="login"><br>
            <label>Пароль:</label><br>
            <input type="text" name="password"><br>
            <label>Почта:</label><br>
            <input type="text" name="email"><br>
            <label>Дата регистрации:</label><br>
            <input type="text" name="date"><br>
            <input type="submit" style="margin-top: 10px;" name="add_users" value="Добавить пользователя" >
        <?php
        }
        ?>
    </form>

    <!-- Таблица новостей -->
    <table>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Пароль</th>
            <th>Почта</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['userid']."</td>";
            echo "<td>".$row['login']."</td>";
            echo "<td>".$row['password']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td>".$row['date']."</td>";
            echo "<td><a href='users_edit.php?edit_users=".$row['userid']."'>Редактировать</a> | <a href='users_edit.php?delete_users=".$row['userid']."' onclick='return confirm(\"Вы уверены, что хотите удалить эту пользователя?\")'>Удалить</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>
