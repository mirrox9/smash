<?php
    //Подключение шапки
    require_once("header.php");
    $list = $_SERVER['REQUEST_URI'];

    function transposeMatrix($matrix) {
        $transposedMatrix = array();
        foreach ($matrix as $rowIndex => $row) {
            foreach ($row as $columnIndex => $value) {
                $transposedMatrix[$columnIndex][$rowIndex] = $value;
            }
        }
        return $transposedMatrix;
    }

    function exponential_smoothing($data, $alpha) {
        $smoothed_data = array();
        $smoothed_data[0] = $data[0]; // Инициализация первого значения в результирующем массиве как первого значения в исходном массиве
        
        // Применение метода экспоненциального сглаживания
        for ($i = 1; $i < count($data); $i++) {
            $smoothed_data[$i] = $alpha * $data[$i-1] + (1 - $alpha) * $smoothed_data[$i - 1];
        }
        $forecast = $alpha * $data[count($data) - 1] + (1 - $alpha) * $smoothed_data[count($smoothed_data) - 1];
        array_push($forecast,$smoothed_data);
        return $smoothed_data;
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Результаты прогноза</title>

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

        p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
            }
</style>

<style>
   button[type="submit"] {
  background-color: #4caf50;
  color: white;
  cursor: pointer;
  height: 40px;
  border: 1px solid #ccc;
  border-radius: 3px;
  font-size: 14px;
}

button[type="submit"]:hover {
  background-color: #45a049;
}
</style>
</head>
<body>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем, был ли загружен файл
    if (isset($_FILES['excel-file']) && $_FILES['excel-file']['error'] === UPLOAD_ERR_OK || strpos($list,'?draw=true')) {
        // Путь для сохранения файла на сервере
        $uploadDirectory = './uploads/';
        $uploadedFileName = $uploadDirectory . 'prognoz.xlsx'; // Указываем имя файла

        // Перемещаем загруженный файл в указанную директорию
        if (move_uploaded_file($_FILES['excel-file']['tmp_name'], $uploadedFileName)) {
            // Конвертируем файл в формат UTF-8
            echo '<div style="margin-left:10px">';
            echo '<h3>Файл успешно загружен.</h3>';
            echo '</div>';
            $pythonScriptPath = 'prognoz_data_reader.py';
            $command = "python $pythonScriptPath";
    
            $result = shell_exec($command);

            // Имя текстового файла
            $file = "uploads/prognoz_data.txt";

            // Проверка существования файла
            if (file_exists($file)) {
                // Чтение файла построчно
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                // Создание массива для хранения данных
                $data = array();
                $current_column = "";

                // Проход по каждой строке файла
                foreach ($lines as $line) {
                    // Проверка, является ли строка названием показателя
                    if (substr($line, -1) === ":") {
                        $current_column = rtrim($line, ":");
                        $data[$current_column] = array();
                    } else {
                        // Добавление значения в массив данных текущего показателя
                        $data[$current_column][] = $line;
                    }
                }

                // Вывод массива данных для проверки             
               // Выводим таблицу с данными о продуктах
               echo '<div style="margin-left:10px">';
               echo '<h2>Показатели продукта</h2>';
               echo '<table border="1">';
               echo '<tr>';
               // Добавляем подписи к столбцам
               $labels = array("Продажи", "Прибыль", "Маржа", "Количество продаж", "Коэффициент конверсии", 
               "Среднее время жизни клиента", "Доля рынка", "Частота покупок");
               echo '<th>Месяц</th>';        
               foreach ($labels as $label) {
                   echo '<th>' . $label . '</th>';
               }
               echo '</tr>';
               $month = 1;
               $result_data = transposeMatrix($data);
               foreach ($result_data as $product) {
                echo '<tr>';
                echo '<td>' . $month . '</td>';
                foreach ($product as $value) {                    
                    echo '<td>' . $value . '</td>';
                }
                $month+=1;
                echo '</tr>';
                }
               echo '</table>';
               echo '</div>';




            } else {
                echo "Файл не найден.";
            }
        } 
        else 
        {
            if(strpos($list,'?draw=true'))
            {
                $file = "uploads/prognoz_data.txt";
                if (file_exists($file)) {
                    // Чтение файла построчно
                    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    // Создание массива для хранения данных
                    $data = array();
                    $current_column = "";
    
                    // Проход по каждой строке файла
                    foreach ($lines as $line) {
                        // Проверка, является ли строка названием показателя
                        if (substr($line, -1) === ":") {
                            $current_column = rtrim($line, ":");
                            $data[$current_column] = array();
                        } else {
                            // Добавление значения в массив данных текущего показателя
                            $data[$current_column][] = $line;
                        }
                    }
    
                    // Вывод массива данных для проверки             
                   // Выводим таблицу с данными о продуктах
                   echo '<div style="margin-left:10px">';
                   echo '<h2>Показатели продукта</h2>';
                   echo '<table border="1">';
                   echo '<tr>';
                   // Добавляем подписи к столбцам
                   $labels = array("Продажи", "Прибыль", "Маржа", "Количество продаж", "Коэффициент конверсии", 
                   "Среднее время жизни клиента", "Доля рынка", "Частота покупок");
                   echo '<th>Месяц</th>';        
                   foreach ($labels as $label) {
                       echo '<th>' . $label . '</th>';
                   }
                   echo '</tr>';
                   $month = 1;
                   $result_data = transposeMatrix($data);
                   foreach ($result_data as $product) {
                    echo '<tr>';
                    echo '<td>' . $month . '</td>';
                    foreach ($product as $value) {                    
                        echo '<td>' . $value . '</td>';
                    }
                    $month+=1;
                    echo '</tr>';
                    }
                   echo '</table>';
                   echo '</div>';
    
    
    
    
                } else {
                    echo "Файл не найден.";
                }
            }
        else
            echo '<h3>Произошла ошибка при сохранении файла на сервере.</h3>';
        }
    } 
    else 
    {
        echo '<h3>Ошибка загрузки файла.</h3>';
    }
}
?>
<div style="margin-left:10px">
 <h2>Обзор данных</h3>
 <h3>Выберите один из показателей чтобы отобразить данные на графике</h3>
 <?$type  = $_POST['type'];
 if ($type == NULL)
 {
    $type = 1;
 }
 
 ?>
 <form action="prognoz_step2.php?draw=true" method="post">
 <select name="type">
        <option value=1>Продажи</option>
        <option value=2>Прибыль</option>
        <option value=3>Маржа</option>
        <option value=4>Количество продаж</option>
        <option value=5>Коэффициент конверсии</option>
        <option value=6>Среднее время жизни клиента</option>
        <option value=7>Доля рынка</option>
        <option value=8>Частота покупок</option>
    </select>
    <button type="submit">Построить график</button>
</form>
<?php

$InPrognozData = array();

$col = 1; 
foreach ($result_data as $row) {
    foreach ($result_data as $product) {
            foreach ($product as $value) {                  
                if($col == $type)
                {
                    array_push($InPrognozData , $value);
                    $m+=1;
                    if ($m > 12)
                    {
                        break 3;
                    }
                    $col = 1; break;
                }
                $col+=1;
                
            }

        }  
}

function calculateForecastAccuracy($actual, $forecast) {
    $n = count($actual);
    $sum = 0;

    for ($i = 0; $i < $n; $i++) {
        $sum += abs($actual[$i] - $forecast[$i]) / $actual[$i];
    }

    $accuracy = (1 - ($sum / $n)) * 100;
    return round($accuracy, 2); // Округляем до двух знаков после запятой
}

$pred_data = exponential_smoothing($InPrognozData, 0.4); 
$acc = calculateForecastAccuracy($InPrognozData, $pred_data);
echo "Точность: $acc";

$str = "";
$m = 1;
$arr1 = array();
$col = 1; 

foreach ($result_data as $row) {
        foreach ($result_data as $product) {
                foreach ($product as $value) {                  
                    if($col == $type)
                    {
                        $str = '["' . $m . '",' . $value . ',' . $pred_data[$m-1] . '],';
                        array_push($arr1, $str);
                        $m+=1;               
                        if ($m > 12)
                        {
                            $str = '["' . $m . '",' . NULL . ',' . $pred_data[$m-1] . '],';
                            array_push($arr1, $str);
                            break 3;
                        }
                        $col = 1; break;
                    }
                    $col+=1;
                    
                }

            }  
}


$str = implode("", $arr1);  


switch($type)
{
    default:
        $title ="График продаж";
        $stat_title = "Продажи";
    break;

    case 2:
        $title ="График прибыли";
        $stat_title = "Прибыль";
    break;

    case 3:
        $title ="График маржи";
        $stat_title = "Маржа";
    break;

    case 4:
        $title ="График количества продаж";
        $stat_title = "Количество продаж";
    break;

    case 5:
        $title ="График коэффициента конверсии";
        $stat_title = "Коэффициент конверсии";
    break;

    case 6:
        $title ="График среднего времени жизни клиента";
        $stat_title = "Среднее время жизни клиента";
    break;

    case 7:
        $title ="График доли рынка";
        $stat_title = "Доля рынка";
    break;

    case 8:
        $title ="График частоты покупок";
        $stat_title = "Частота покупок";
    break;
}
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {           
                    var data = google.visualization.arrayToDataTable([
                    ['Месяц', '<?php echo $stat_title ?>','Спронозированное значение'],
                    <?php echo $str?> 
                   
                    ]);

                    var options = {
                        title: '<?php echo $title ?>',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        colors: ['blue', 'red']
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);
                
                }
                </script>
                <div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>