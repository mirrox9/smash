<?php
    //Подключение шапки
    require_once("header.php");
?>

<!DOCTYPE html>
<html>
<head>
  <title>ABC XYZ анализ</title>
  <link rel="stylesheet" type="text/css" href="abcxyz_style.css">
  </head>
<body>
    <div style="margin-top: 10px; margin-left: 10px;">
    <h1>ABC XYZ анализ</h1>
    <button class="button" onclick="downloadExcel()">Пример Excel файла</button>
	<button class="button" onclick="showPopup('example-data')">Пример таблицы данных</button>
	<button class="button" onclick="showPopup('category-info')">Информация о группах и категориях</button>
    </div>

	<div id="example-excel-popup" class="popup">
		<h2>Пример Excel файла</h2>
		<div>
        <p>Ниже приведен пример Excel файла, содержащего информацию о продажах продукции в течение года. Файл имеет следующую структуру:</p>
        <h2>Скачать пример Excel файла</h2>
        <p>Для скачивания примера Excel файла, <a href="example.xlsx" download>нажмите здесь</a>.</p>
        </div>
		<button class="button" onclick="hidePopup('example-excel')">Закрыть</button>
	</div>

	<div id="example-data-popup" class="popup">
		<h2>Пример таблицы данных</h2>
		<table>
		<thead>
			<tr>
				<th>Название продукта</th>
				<th>Выручка за период</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Продукт 1</td>
				<td>800</td>
			</tr>
			<tr>
				<td>Продукт 2</td>
				<td>200</td>
			</tr>
			<tr>
				<td>Продукт 3</td>
				<td>150</td>
			</tr>
            <tr>
				<td>Продукт 4</td>
				<td>320</td>
			</tr>
            <tr>
				<td>Продукт 5</td>
				<td>450</td>
			</tr>
            <tr>
				<td>Продукт 6</td>
				<td>50</td>
			</tr>
            <tr>
				<td>Продукт 7</td>
				<td>700</td>
			</tr>
            <tr>
				<td>Продукт 8</td>
				<td>610</td>
			</tr>
            <tr>
				<td>Продукт 9</td>
				<td>380</td>
			</tr>
			<tr>
				<td>Продукт 10</td>
				<td>180</td>
			</tr>
		</tbody>
	</table>
		<button class="button" onclick="hidePopup('example-data')">Закрыть</button>
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

    <form action="./abcxyz_step2.php" method="POST" enctype="multipart/form-data">
    <div class="input-container">
    <label for="number-input">Количество месяцев для расчета:</label>
    <input type="number" id="number-input" name="number-input" min="1" max="12" oninput="checkNumberRange(this)">
    </div>

    <div class="panel">
    <label for="input-method">Выберите способ ввода данных:</label>
    <select id="input-method" name="input-method">
      <option value="manual">Ввести данные вручную</option>
      <option value="excel">Загрузить файл Excel</option>
    </select>


    <div id="manual-input" class="input-section">
      <h4>Ввод данных вручную:</h4>
      <div id="product-list">
        <div class="product-input">
          <input type="text" name="product[]" placeholder="Название продукта">
          <input type="number" name="revenue[]" placeholder="Выручка продукта">
        </div>
      </div>
      <button class="button" type="button" id="add-product">Добавить продукт</button>
      <button class="button" type="button" id="remove-product">Убрать продукт</button>
    </div>

    <div id="excel-input" class="input-section" style="display: none;">
      <h4>Загрузка файла Excel:</h4>
      <input type="file" name="excel-file">
    </div>
  </div>

  <div class="settings">
    <h3>Дополнительные настройки анализа</h3>
    <label>
      <input type="checkbox" id="abc-analysis" name="abc_analysis" value="abc">
      Проводить только ABC анализ
    </label>
    <br>
    <label>
      <input type="checkbox" id="xyz-analysis" name="xyz_analysis" value="xyz">
      Проводить только XYZ анализ
    </label>
    <br>
    <label>
      <input type="checkbox" name="recommendations" value="recommend">
      Выдавать рекомендации на основе анализа
    </label>
    <br>
    <!-- <label>
      <input type="checkbox" name="pdf_generation" value="pdf">
      Формировать PDF файл с результатами анализа
    </label> -->
    <button class="button" type="submit">Продолжить</button>
  </div>
</form>

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
            link.download = 'example.csv'; // Имя файла для скачивания
            link.click();
        }

        const inputMethodSelect = document.getElementById('input-method');
        const manualInputSection = document.getElementById('manual-input');
        const excelInputSection = document.getElementById('excel-input');
        const addProductBtn = document.getElementById('add-product');
        const removeProductBtn = document.getElementById('remove-product');

        inputMethodSelect.addEventListener('change', function() {
            if (this.value === 'manual') {
            manualInputSection.style.display = 'block';
            excelInputSection.style.display = 'none';
            } else if (this.value === 'excel') {
            manualInputSection.style.display = 'none';
            excelInputSection.style.display = 'block';
            }
        });

        let productCount = 1;

        addProductBtn.addEventListener('click', function() {
            if (productCount < 15) {
            productCount++;
            const productDiv = document.createElement('div');
            productDiv.className = 'product-input';
            productDiv.innerHTML = `
                <input type="text" name="product[]" placeholder="Название продукта">
                <input type="number" name="revenue[]" placeholder="Выручка продукта">
            `;
            document.getElementById('product-list').appendChild(productDiv);
            }
        });

        removeProductBtn.addEventListener('click', function() {
            if (productCount > 1) {
            const lastProductDiv = document.getElementById('product-list').lastElementChild;
            lastProductDiv.parentNode.removeChild(lastProductDiv);
            productCount--;
            }
        });

	</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Обработчик события изменения чекбоксов
      $('input[type="checkbox"]').change(function() {
        var abcCheckbox = $('#abc-analysis');
        var xyzCheckbox = $('#xyz-analysis');

        // Если выбран чекбокс "Проводить только ABC анализ",
        // отключаем чекбокс "Проводить только XYZ анализ"
        if (abcCheckbox.prop('checked')) {
          xyzCheckbox.prop('checked', false);
        }

        // Если выбран чекбокс "Проводить только XYZ анализ",
        // отключаем чекбокс "Проводить только ABC анализ"
        if (xyzCheckbox.prop('checked')) {
          abcCheckbox.prop('checked', false);
        }
      });
    });

    function checkNumberRange(input) {
        if (input.value < 1) {
            input.value = 1;
        } else if (input.value > 12) {
            input.value = 12;
        }
    }

  </script>


