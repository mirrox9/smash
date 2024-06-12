<?php
session_start();
mysql_connect('localhost', 'root','');
mysql_query("SET NAMES utf8"); 
mysql_select_db('smash');  
if(mysql_errno())
{
echo 'Ошибка! : Не удалось установить соединение с базой данных.';
exit;
} 
error_reporting(0);
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="headerstyle.css">
  </head>
  <body>
    <header>
      <nav>
        <div class="logo">
          <a class="logo" href="./index.php"><img class="logo" src="SMASH.svg" alt="Logo"></a>
        </div>
        <div class="menu">
          <ul>
          <li class="dropdown">
            <a href="#" class="dropbtn">ABC XYZ анализ</a>
            <div class="dropdown-content">
              <a href="./abcxyz.php">Обычный анализ</a>
              <a href="./iianalysis.php">Анализ с помощью ИИ</a>
              <a href="./prognoz_data.php">Прогнозирование значений</a>
            </div>
          </li>
            <li><a href="./news.php">Новости</a></li>
            <li><a href="./materials.php">Полезные материалы</a></li>
            <li><a href="./about.php">О нас</a></li>
          </ul>
        </div>
        <div class="buttons">
          <?
          $auth =  $_SESSION['auth']; 
          if(!$auth) { ?>
          <ul>
            <li><a href="#" id="registerBtn">Регистрация</a></li>
            <li><a href="#" id="loginBtn">Авторизация</a></li>
          </ul>
          <? } 
          else
          { ?>
          <ul>
            <li><a href="./old_user.php"> Мой профиль</a></li>
            <li><a href="./logout.php"> Выход</a></li>
          </ul>
         <?php } ?>
        </div>
      </nav>
    </header> 
    
    <!-- Форма авторизации -->
    <div class="overlay"></div>
    <div id="loginForm" class="form">
      <h2>Вход</h2>
      <span class="close-btn"></span>
      <form action="./auth.php?check=true" method="POST">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="password" placeholder="Пароль">
        <button>Войти</button>
      </form>
    </div>

    <!-- Форма регистрации -->
    <div id="registerForm" class="form">
      <h2>Регистрация</h2>
      <span class="close-btn"></span>
      <form action="./registration.php?check=true" method="POST">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="password" placeholder="Пароль">
        <input type="email" name="email" placeholder="Email">
        <button>Зарегистрироваться</button>
      </form>
    </div>

  </body>
</html>

<script>
  const overlay = document.querySelector(".overlay");
  // Получаем кнопки входа и регистрации
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const closeBtns = document.querySelectorAll('.close-btn');

// Получаем формы входа и регистрации
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');

// Добавляем обработчики событий на кнопки
loginBtn.addEventListener('click', () => {
  loginForm.style.display = 'block';
  overlay.style.display = "block";
});

registerBtn.addEventListener('click', () => {
  registerForm.style.display = 'block';
  overlay.style.display = "block";
});

// Закрываем формы при клике вне формы
window.addEventListener('click', (e) => {
  if (e.target == loginForm) {
    loginForm.style.display = 'none';
    overlay.style.display = "none";
  }

  if (e.target == registerForm) {
    registerForm.style.display = 'none';
    overlay.style.display = "none";
  }
});


closeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      loginForm.style.display = 'none';
      registerForm.style.display = 'none';
      overlay.style.display = "none";
    });
  });
</script>


