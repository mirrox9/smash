<?php
    //Подключение шапки
    require_once("header.php");
    
?>

<!DOCTYPE html>
<html>
<head>
<title>ABC XYZ анализ</title>
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
</style>

</head>
<body>
  <?php
  // Проверяем, была ли отправлена форма
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем значения из полей формы
    $numberInput = $_POST['number-input'];
    $abcAnalysis = isset($_POST['abc_analysis']) ? 1 : 0;
    $xyzAnalysis = isset($_POST['xyz_analysis']) ? 1 : 0;
    $recommendations = isset($_POST['recommendations']) ? 1 : 0;
    $pdfGeneration = isset($_POST['pdf_generation']) ? 1 : 0;
    $method = $_POST['input-method'];
  }

  if($numberInput)
  {
    if($method == "manual")
    {
      $products = $_POST['product'];
      $revenues = $_POST['revenue'];
        if($abcAnalysis == 0)
        { ?>
           <div style="margin-left:10px">
           <h2>Распределение выручки</h2>
           <p>Для проведения XYZ анализа, нужно распределить выручку по всем месяцам периода</p>
           <form action="./results.php" method="POST">
           <input type="hidden" name="numberInput" value="<?php echo $numberInput; ?>">
          <input type="hidden" name="abcAnalysis" value="<?php echo $abcAnalysis; ?>">
          <input type="hidden" name="xyzAnalysis" value="<?php echo $xyzAnalysis; ?>">
          <input type="hidden" name="recommendations" value="<?php echo $recommendations; ?>">
          <input type="hidden" name="pdfGeneration" value="<?php echo $pdfGeneration; ?>">
          <input type="hidden" name="method" value="<?php echo $method; ?>">
           <table>
            <thead>
            <tr>
                <th>Продукт</th>
                <?php
                for ($i = 1; $i <= $numberInput; $i++) {
                echo "<th>Месяц $i</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $inputValues = array();
           foreach ($products as $key => $product) {
            $remainingRevenue = $revenues[$key]; // Изначально остаток выручки равен полной выручке продукта
            echo "<tr>";
            echo "<td>$product</td>";
            echo "<input type='hidden' name='products[]' value='$product'>";
            for ($i = 1; $i <= $numberInput; $i++) {
              //$inputName = "revenues[$key][$i]";
              echo "<td><input type='number' step='1' min='0' name='arrayOfDisRevenues[]' oninput='checkNumberRange(this)' onchange='updateRemainingRevenue(this, $key)' /> </td>";
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td colspan='$numberInput'>Остаток выручки: <span id='remaining-revenue-$key'>$remainingRevenue</span></td>";
            echo "</tr>";
          }
            ?>
            </tbody>
        </table>
        <button class="button" type="submit">Анализировать</button>
            </form>
            </div>
        <?php 
        }
        else
        { ?>
        <form id="myForm" action="results.php" method="post">
        <input type="hidden" name="numberInput" value="<?php echo $numberInput; ?>">
        <input type="hidden" name="abcAnalysis" value="<?php echo $abcAnalysis; ?>">
        <input type="hidden" name="xyzAnalysis" value="<?php echo $xyzAnalysis; ?>">
        <input type="hidden" name="recommendations" value="<?php echo $recommendations; ?>">
        <input type="hidden" name="pdfGeneration" value="<?php echo $pdfGeneration; ?>">
        <input type="hidden" name="method" value="<?php echo $method; ?>">
        <?php foreach ($products as $key => $product): ?>
          <input type="hidden" name="products[<?php echo $key; ?>]" value="<?php echo $product; ?>">
          <?php foreach ($revenues as $key => $revenue): ?>
            <input type="hidden" name="revenues[<?php echo $key; ?>]" value="<?php echo $revenue; ?>">
        <?php endforeach; ?>
      <?php endforeach; ?>
    </form>

    <script>
        // Отправка формы при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('myForm').submit();
        });
    </script>
        <?php }  
    }
    else //Если передаём excel файл
    {
      //Загрузка excel файла
      if (isset($_FILES['excel-file'])) {
        // Проверяем наличие ошибок при загрузке файла
        if ($_FILES['excel-file']['error'] === UPLOAD_ERR_OK) {
            // Получаем путь к загруженному временному файлу
            $filePath = $_FILES['excel-file']['tmp_name'];
            // Открываем файл для чтения
            $handle = fopen($filePath, 'r');
            
            // Инициализируем массивы для продуктов и выручки
            $products = array();
            $revenues = array();
            
            // Построчно читаем данные из CSV файла
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $products[] = $data[0]; // Добавляем название продукта в массив $products
                $revenues[] = $data[1]; // Добавляем выручку продукта в массив $revenues
            }
            
            // Закрываем файл
            fclose($handle);                      
        } else {
            echo "<h1>Ошибка при загрузке файла!</h1>";
            exit();
        }
      }

      //Обработка файла
      if($abcAnalysis == 0)
      { ?>
         <div style="margin-left:10px">
         <h2>Распределение выручки</h2>
         <p>Для проведения XYZ анализа, нужно распределить выручку по всем месяцам периода</p>
         <form action="./results.php" method="POST">
         <input type='hidden' name='numberInput' value='<?echo $numberInput?>'>
         <input type='hidden' name='abcAnalysis' value='<?echo $abcAnalysis?>'>
         <input type='hidden' name='xyzAnalysis' value='<?echo $xyzAnalysis?>'>
         <input type='hidden' name='recommendations' value='<?echo $recommendations?>'>
         <input type='hidden' name='pdfGeneration' value='<?echo $pdfGeneration?>'>
         <table>
          <thead>
          <tr>
              <th>Продукт</th>
              <?php
              for ($i = 1; $i <= $numberInput; $i++) {
              echo "<th>Месяц $i</th>";
              }
              ?>
          </tr>
          </thead>
          <tbody>
          <?php
          $inputValues = array();
         foreach ($products as $key => $product) {
          $remainingRevenue = $revenues[$key]; // Изначально остаток выручки равен полной выручке продукта
          echo "<tr>";
          echo "<td>$product</td>";
          echo "<input type='hidden' name='products[]' value='$product'>";
          for ($i = 1; $i <= $numberInput; $i++) {
            //$inputName = "revenues[$key][$i]";
            echo "<td><input type='number' step='1' min='0' name='arrayOfDisRevenues[]' oninput='checkNumberRange(this)' onchange='updateRemainingRevenue(this, $key)' /></td>";
          }
          echo "</tr>";
          echo "<tr>";
          echo "<td colspan='$numberInput'>Остаток выручки: <span id='remaining-revenue-$key'>$remainingRevenue</span></td>";
          echo "<td><input type='hidden' name='ostatki[]' value='$remainingRevenue'></td>";
          echo "</tr>";
        }
          ?>
          </tbody>
      </table>
      <button class="button" type="submit">Анализировать</button>
          </form>
          </div>
      <?php
      } 
     else //Если только ABC анализ
     { ?>
      <form id="myForm" action="results.php" method="post">
      <input type="hidden" name="numberInput" value="<?php echo $numberInput; ?>">
      <input type="hidden" name="abcAnalysis" value="<?php echo $abcAnalysis; ?>">
      <input type="hidden" name="xyzAnalysis" value="<?php echo $xyzAnalysis; ?>">
      <input type="hidden" name="recommendations" value="<?php echo $recommendations; ?>">
      <input type="hidden" name="pdfGeneration" value="<?php echo $pdfGeneration; ?>">
      <input type="hidden" name="method" value="<?php echo $method; ?>">
      <?php foreach ($products as $key => $product): ?>
          <input type="hidden" name="products[<?php echo $key; ?>]" value="<?php echo $product; ?>">
          <?php foreach ($revenues as $key => $revenue): ?>
            <input type="hidden" name="revenues[<?php echo $key; ?>]" value="<?php echo $revenue; ?>">
        <?php endforeach; ?>
      <?php endforeach; ?>
  </form>

  <script>
      // Отправка формы при загрузке страницы
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('myForm').submit();
      });
  </script>
     <?php }  
 
       
    }
  }
  else
  { ?>
 <h1>Некорректный ввод количества месяцев!</h1>
<?php }
  ?>
</body>
</html>
<script>
  function updateRemainingRevenue(input, productIndex) {
    var remainingRevenueElement = document.getElementById('remaining-revenue-' + productIndex);
    var remainingRevenue = parseFloat(remainingRevenueElement.textContent);
    var inputValue = parseFloat(input.value);
    var previousValue = parseFloat(input.getAttribute('data-previous-value'));
    if (!isNaN(previousValue)) {
      remainingRevenue += previousValue; // Вернуть предыдущее значение в остаток выручки
    }
    if (!isNaN(inputValue)) {
      remainingRevenue -= inputValue;
      input.setAttribute('data-previous-value', inputValue); // Сохранить текущее значение в атрибуте
    } else {
      input.setAttribute('data-previous-value', ''); // Сбросить предыдущее значение, если ввод не является числом
    }
    remainingRevenueElement.textContent = remainingRevenue.toFixed(2);
  }

  function checkNumberRange(input) {
        if (input.value < 0) {
            input.value = 1;
        } 
    }
</script>

