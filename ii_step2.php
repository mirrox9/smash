<?php
    //Подключение шапки
    require_once("header.php");
?>



<!DOCTYPE html>
<html>
<head>
<title>ABC XYZ анализ с помощью ИИ</title>
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
        if (isset($_FILES['excel-file'])) {
            if ($_FILES['excel-file']['error'] === UPLOAD_ERR_OK) {
                $filePath = $_FILES['excel-file']['tmp_name'];
                $handle = fopen($filePath, 'r');
                
               
                $products = array();
                
             
                $productCounter = 1;
              
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                   
                    $products[] = array(
                        'Продукт' => 'Продукт ' . $productCounter++,
                        'Продажи' => $data[0],
                        'Прибыль' => $data[1],
                        'Маржа' => $data[2],
                        'Количество продаж' => $data[3],
                        'Коэффициент конверсии' => $data[4],
                        'Среднее время жизни клиента' => $data[5],
                        'Доля рынка' => $data[6],
                        'Частота покупок' => $data[7]
                    );
                }
                
             
                fclose($handle);

                echo '<div style="margin-left:10px">';
                echo '<h2>Данные о загруженных продуктах</h2>';
                echo '<table border="1">';
                echo '<tr>';
                foreach ($products[0] as $key => $value) {
                    echo '<th>' . $key . '</th>';
                }
                echo '</tr>';
                foreach ($products as $product) {
                    echo '<tr>';
                    foreach ($product as $value) {
                        echo '<td>' . $value . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo "<h1>Ошибка при загрузке файла!</h1>";
                exit();
            }
        }
        echo '<h2>Обработка файла</h2>';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            if (isset($_FILES['excel-file']) && $_FILES['excel-file']['error'] === UPLOAD_ERR_OK) {
               
                $uploadDirectory = './uploads/';
                $uploadedFileName = $uploadDirectory . 'ii_file.txt'; 
        
                // Перемещаем загруженный файл в указанную директорию
                if (move_uploaded_file($_FILES['excel-file']['tmp_name'], $uploadedFileName)) {
                    // Конвертируем файл в формат UTF-8
                    $content = file_get_contents($uploadedFileName);
                    file_put_contents($uploadedFileName, utf8_encode($content));
        
                    echo '<h3>Файл успешно загружен.</h3>';
                
                } else {
                    echo '<h3>Произошла ошибка при сохранении файла на сервере.</h3>';
                }
            } else {
                echo '<h3>Ошибка загрузки файла.</h3>';
            }
        }
      
        $pythonScriptPath = 'ii.py';
        $command = "python $pythonScriptPath";

        $result = shell_exec($command);

    echo '<div>';
    echo '<p>' . nl2br($result) . '</p>'; 
    
    $file_path = './uploads/result.txt  ';

    if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);

        $lines = explode("\n", $file_content);
    
        
        $products = array();;
        $current_product = array();;
    
      
        foreach ($lines as $line) {
           
            $trimmed_line = trim($line);
    
           
            if (!empty($trimmed_line)) {
               
                $current_product[] = $trimmed_line;
    
              
                if (count($current_product) === 3) {
                 
                    $products[] = $current_product;
    
                  
                    $current_product = array();;
                }
            }
        }
    
        // Вывод таблицы с результатами
        echo '<div>';
        echo '<h2>Результаты анализа</h2>';
        echo '<table border="1">';
        echo '<tr><th>Продукт</th><th>ABC категория</th><th>XYZ категория</th></tr>';
    

        $name = array();
        $abc_group = array();
        $xyz_group = array();
        foreach ($products as $product) {
            echo '<tr>';
            $i = 0;
            foreach ($product as $info) {
                echo '<td>' . htmlspecialchars($info) . '</td>';
                if ($i == 0)
                {
                    $name[] = $info;
                }
                if ($i == 1)
                {
                    $abc_group[] = $info;
                }
                if ($i == 2)
                {
                    $xyz_group[] = $info;

                }
                $i = $i + 1;
            }
            echo '</tr>';
        }
    
        echo '</table>';
        echo '</div>';
        
        if($auth == true)
            {
                ?>
                    <form action="save_an.php" method="POST">
                    <?php

                    foreach ($name as $product) {
                        echo '<input type="hidden" name="products[]" value="' . $product . '">';
                    }

                    foreach ($abc_group as $abc) {
                        echo '<input type="hidden" name="abc_group[]" value="' . $abc . '">';
                    }

                    foreach ($xyz_group as $xyz) {
                        echo '<input type="hidden" name="xyz_group[]" value="' . $xyz . '">';
                    }
                    ?>

                    <button type="submit">Сохранить результаты</button>
                    </form>
                <?php
             }
    } else {
        // Вывод сообщения, если файл не существует
        echo '<p>Файл result.txt не найден.</p>';
    }
?>
</html>
