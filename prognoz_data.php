<?php
    //Подключение шапки
    require_once("header.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Прогнозирование данных</title>
  <link rel="stylesheet" type="text/css" href="abcxyz_style.css">

  <style>
    p {
        font-size: 18px;
  line-height: 1.5;
  margin-bottom: 20px;
}
  </style>
</head>
<body>
    <div style="margin-top: 10px; margin-left: 10px;">
    <h1>Прогнозирование данных ABC XYZ анализа</h1>
    <button class="button" onclick="downloadExcel()">Пример Excel файла</button>
    <p>Добро пожаловать в раздел прогнозирования данных через метод экспоненциального сглаживания! Здесь вы 
        сможете провести прогнозирование данных ваших программных продуктов на 
        основе статистики за прошедшее время. Для прогнозирования вам следует 
        загрузить excel файл со значениями параметров программного продукта за последние 12 месяцев. 
        Пример файла можно скачать нажав на кнопку «Пример Excel файла».</p>
        <form action="./prognoz_step2.php" method="POST" enctype="multipart/form-data">
        <div id="excel-input" class="input-section" style="display: block;">
        <h4>Загрузка файла Excel:</h4>
        <input type="file" name="excel-file">
        </div>
        <button class="button" type="submit">Продолжить</button>
        </form>
    </div>

	<div id="example-excel-popup" class="popup">
		<h2>Пример Excel файла</h2>
		<div>
        <p>Ниже приведен пример Excel файла, содержащего информацию о продажах продукции в течение года. Файл имеет следующую структуру:</p>
        <h2>Скачать пример Excel файла</h2>
        <p>Для скачивания примера Excel файла, <a href="#" download>нажмите здесь</a>.</p>
        </div>
		<button class="button" onclick="hidePopup('example-excel')">Закрыть</button>
	</div>


</body>
</html>
	<script>
        function downloadExcel() {
            var link = document.createElement('a');
            link.href = '/SMASH/example/prognoz_example.xlsx'; // Полный путь к файлу
            link.download = 'prognoz_example.xlsx'; // Имя файла для скачивания
            link.click();
        }
	</script>
