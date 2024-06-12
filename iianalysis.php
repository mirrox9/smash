<?php
    //Подключение шапки
    require_once("header.php");

?>

<!DOCTYPE html>
<html>
<head>
  <title>ABC XYZ анализ с помощью ИИ</title>
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
    <h1>ABC XYZ анализ с помощью ИИ</h1>
    <button class="button" onclick="downloadExcel()">Пример Excel файла</button>
	<button class="button" onclick="showPopup('category-info')">Информация о группах и категориях</button>
    <p>Добро пожаловать в раздел проведения ABC XYZ анализа с помощью искусственного интеллекта! Здесь вы сможете провести анализ ваших программных продуктов на основе таких параметров как продажи, прибыль, маржа, 
        количество продаж, коэффициент конверсии, среднее время жизни клиента, доля рынка и частота покупок. Для проведения анализа вам следует загрузить excel файл со значениями вышеперечисленных параметров программных продуктов. 
        Пример файла можно скачать нажав на кнопку «Пример Excel файла».</p>
        <form action="./ii_step2.php" method="POST" enctype="multipart/form-data">
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

	<div id="category-info-popup" class="popup">
		<h2>Общие рекомендации</h2>
		<div>
        <p><strong>Группа А -</strong> товары, сумма долей с накопительным итогом которых составляет первые 50% от общей суммы товаров. Эти объекты требуют тщательного планирования, постоянного (возможно, даже ежедневного), скрупулезного учета и контроля. Эти товары составляют 50% вашего оборота или прибыли и соответственно чем выше стоимость товара, тем дороже обходятся ошибки в их анализе. Необходим периодический подсчет запасов с жесткими допусками.</p>
        <p><strong>Группа B -</strong> следующие за группой А товары, сумма долей с накопительным итогом которых составляет от 50 до 80% от общей суммы товаров. Эти объекты стабильны в прибыли и обороте для компании и требуют обычного контроля, налаженного учета (возможно, ежемесячного). Для них применяются те же меры, что и для категории А, но они осуществляются реже и с большими приемлемыми допусками.</p>
        <p><strong>Группа C -</strong> остальные товары, сумма долей с накопительным итогом которых составляет от 80 до 100% от общей суммы товаров. Эти товары характеризуются упрощенными методами планирования, учета и контроля. Однако несмотря на их кажущуюся малоценность, они составляют 20% оборота (или прибыли) и требуют периодического контроля.</p>
        <p><strong>Категория X -</strong> товары характеризуются стабильностью продаж и как следствие - высокими возможностями прогноза продаж. Коэффициент вариации не превышает 10% - колебания спроса незначительны, спрос на них устойчив, следовательно, по этим товарам можно делать оптимальные запасы на складе магазина.</p>
        <p><strong>Категория Y -</strong> товары, имеющие колебания в спросе и как следствие средний прогноз продаж. Коэффициент вариации составляет 10 - 25% - отклонение от средней величины продаж существует, но оно колеблется в разумных пределах (в пределах 25%).</p>
        <p><strong>Категория Z -</strong> товары с нерегулярным потреблением, какие-либо тенденции отсутствуют, точность прогноза продаж невысокая. Коэффициент вариации превышает 25% и может быть более 100% - это может быть группа товаров, доставляемая по заказу клиентов или недавно поступившая в продажу.</p>
        </div>
		<button class="button" onclick="hidePopup('category-info')">Закрыть</button>
	</div>

</body>
</html>
	<script>
		function showPopup(id) {
			document.getElementById(id + "-popup").style.display = "block";
		}

		function hidePopup(id) {
			document.getElementById(id + "-popup").style.display = "none";
		}

        function downloadExcel() {
            const link = document.createElement('a');
            link.href = '/SMASH/example'; // Полный путь к файлу
            link.download = 'ii_example.csv'; // Имя файла для скачивания
            link.click();
        }
	</script>



