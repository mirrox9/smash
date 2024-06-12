<?php
 require_once("header.php");
    
 session_start();//  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
 mysql_connect('localhost', 'root','');
 mysql_query("SET NAMES utf8"); 
 mysql_select_db('smash');  
 if(mysql_errno())
 {
   echo 'Ошибка! : Не удалось установить соединение с базой данных.';
   exit;
 } 
 $_SESSION['auth'] = false;
 ?>
<script>
        function loadPage()
        {
            
            document.location.href = "./index.php";
    
        }
        window.onload = loadPage();
    </script>