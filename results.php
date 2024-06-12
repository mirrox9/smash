<?php
    //Подключение шапки
    require_once("header.php");
    $auth =  $_SESSION['auth'];

    function calculateStandardDeviation($array)
    {
        // Вычисление среднего значения
        $mean = array_sum($array) / (count($array));
    
        // Вычисление разницы между каждым значением и средним значением
        $differences = array();
        foreach ($array as $value) {
            $differences[] = $value - $mean;
        }
    
        // Возвести каждую разницу в квадрат
        $squaredDifferences = array();
        foreach ($differences as $difference) {
            $squaredDifferences[] = pow($difference, 2);;
        }
    
        // Вычисление среднего значения квадратов разниц
        $meanSquaredDifferences = array_sum($squaredDifferences) / (count($array)-1);
    
        // Извлечение квадратного корня из среднего значения квадратов разниц
        $standardDeviation = sqrt($meanSquaredDifferences);
    
        return $standardDeviation;
    }
?>

<html>
    <head>
    <title>Результаты анализа</title>
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
            // Получаем значения из полей формы
            $numberInput = $_POST['numberInput'];
            $abcAnalysis = $_POST['abcAnalysis'];
            $xyzAnalysis = $_POST['xyzAnalysis'];
            $recommendations = $_POST['recommendations'];
            $pdfGeneration = $_POST['pdfGeneration'];
            $products = $_POST['products'];
            $revenues_on_period_post = $_POST['revenues'];
            $arrayOfDisRevenues = $_POST['arrayOfDisRevenues'];
            $ostatki = $_POST['ostatki']; 
          }
        ?>
        <div style="margin-top: 10px; margin-left: 10px;">
            <? if($xyzAnalysis!=1) { ?> <h1>ABC анализ</h1> <? } ?>
            <?php
                $revenues_on_period = array();
                if(count($arrayOfDisRevenues) != 0){
                    $arrayDoubleOfDisRevenues = array();
                    for ($i = 0; $i < count($products); $i++) {
                        $arrayDoubleOfDisRevenues[$i] = array();
                    }
                    $nextPr = 0;
                    $sum = 0;
                    $rowCount = 0;
                    foreach ($arrayOfDisRevenues as $key => $element) {
                    $arrayDoubleOfDisRevenues[$rowCount][$nextPr] = $element;
                    $sum = $sum + $element;
                    $nextPr =  $nextPr + 1;
                        if($nextPr == $numberInput)
                        {
                                $revenues_on_period[] = $sum;
                                $sum = 0;
                                $nextPr = 0;
                                $rowCount = $rowCount + 1;
                        }        
                    }
                }
                else
                {
                    $revenues_on_period = $revenues_on_period_post;
                }
                $revenues_base = $revenues_on_period;

                $total_virychka = 0;

                foreach ($revenues_on_period  as $el) {
                    $total_virychka = $total_virychka + $el; 
                }

                $dolya_v_virychke = array();
                foreach ($revenues_on_period as $el) {
                    $dolya_v_virychke[] = ($el/$total_virychka)*100; 
                }
                $forSort = $dolya_v_virychke;
                 // Создание массива ключей для сортировки
                 $keys = array_keys($forSort);

                 // Сортировка массива $forSort в порядке убывания
                 array_multisort($forSort, SORT_DESC, $keys);
 
                 // Сортировка массива $arrayOfDisRevenues с использованием отсортированных ключей
                 $arrayDoubleOfDisRevenuesSorted = array();
                 $productsSorted = array();
                 foreach ($keys as $key) {
                     $arrayDoubleOfDisRevenuesSorted[] = $arrayDoubleOfDisRevenues[$key];
                     $productsSorted[] = $products[$key];
                 }
                 if($xyzAnalysis==1) 
                 {
                     $products =  $productsSorted;
                 }
                ?>
                <? if($xyzAnalysis!=1) 
                { 
                ?>
                <h3>Шаг 1 - Определение доли в выручке</h3>
                <table>
                <thead>
                    <tr>
                    <th>Продукты</th>
                    <th>Выручка</th>
                    <th>Доля в выручке</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $key => $product) { ?>
                    <tr>
                        <td><?php echo $product; ?></td>
                        <td><?php echo $revenues_on_period[$key]; ?></td>
                        <td><?php echo round($dolya_v_virychke[$key],2); ?></td>
                    </tr>
                    <?php } ?>
                        <td>Общая выручка</td>
                        <td><?php echo $total_virychka; ?></td>
                        <td></td>
                </tbody>
                </table>
                <h3>Шаг 2 - Сортировка в порядке убывания доли в выручке, подсчет совокупного процента</h3>
                <?php
                $tempArray = array();
                foreach ($dolya_v_virychke as $key => $value) {
                $tempArray[$key] = $value;
                }
                // Сортируем массивы в соответствии с значениями в $dolya_v_virychke
                array_multisort($tempArray, SORT_DESC, $dolya_v_virychke, $products, $revenues_on_period);
                $total_proc = array();
                $sum = 0;
                foreach ($dolya_v_virychke as $el) {
                    $sum = $sum + $el;
                    $total_proc[] = $sum; 
                }
                ?>
                <table>
                <thead>
                    <tr>
                    <th>Продукты</th>
                    <th>Выручка</th>
                    <th>Доля в выручке</th>
                    <th>Совокупный процент</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $key => $product) { ?>
                    <tr>
                        <td><?php echo $product; ?></td>
                        <td><?php echo $revenues_on_period[$key]; ?></td>
                        <td><?php echo round($dolya_v_virychke[$key],2); ?></td>
                        <td><?php echo round($total_proc[$key],2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                <h3>Шаг 3 - Определение группы товара в зависимости от выручки</h3>
                <?php
                $abc_group = array();
                foreach ($total_proc as $el) {
                    if(round($el,2)<=80)
                    {
                        $abc_group[] = 1;//A
                    }
                    if((round($el,2)>80) && (round($el,2)<=95))
                    {
                        $abc_group[] = 2;//B
                    }
                    if(round($el,2)>95)
                    {
                        $abc_group[] = 3;//C
                    }
                }
                ?>
                <table>
                <thead>
                    <tr>
                    <th>Продукты</th>
                    <th>Выручка</th>
                    <th>Доля в выручке</th>
                    <th>Совокупный процент</th>
                    <th>Группа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $key => $product) { ?>
                    <tr>
                        <td><?php echo $product; ?></td>
                        <td><?php echo $revenues_on_period[$key]; ?></td>
                        <td><?php echo round($dolya_v_virychke[$key],2); ?></td>
                        <td><?php echo round($total_proc[$key],2); ?></td>
                        <td><?php 
                        switch ($abc_group[$key])
                        {
                            case 1:
                                echo "A";
                                break;
                            case 2:
                                echo "B";
                                break;
                            case 3:
                                echo "C";
                                break;
                        }
                        
                        ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                <?php
                if($recommendations == 1)
                {   
                    $A_cat = array_keys($abc_group, 1);
                    $B_cat = array_keys($abc_group, 2);
                    $C_cat = array_keys($abc_group, 3);
                    ?>
                    <h3>Рекомендации по результатам ABC анализа</h3>
                    <? if(count($A_cat)!=0) { ?>
                    <div style="max-width: 1100px;">
                    <p>Товары 
                    <?
                    foreach ($A_cat as $j) {
                    echo "$products[$j], "; 
                    }
                    ?>
                    относятся к категории A. Рекомендуется уделять этим товарам особое внимание, поскольку они приносят наибольшую долю выручки. Эти товары можно упомянуть в рассылке, на сайте и пуш-уведомлениях. Так же можно провести совместную интеграцию с другими товарами, категория которых не ниже B, акции с ними. 
                    </p>
                    </div><? } ?>

                    <? if(count($B_cat)!=0) { ?>
                    <div style="max-width: 1100px;">
                    <p>Товары 
                    <?
                    foreach ($B_cat as $j) {
                    echo "$products[$j], "; 
                    }
                    ?>
                    относятся к категории B и имеют среднюю важность. Развивайте и улучшайте их функциональность, чтобы сохранить их привлекательность для клиентов и удерживать их на рынке. Проводите анализ конкурентной среды, чтобы определить потенциальные возможности для расширения рынка и привлечения новых клиентов с помощью продуктов категории B. 
                    </p>
                    </div><? } ?>

                    <? if(count($C_cat)!=0) { ?>
                    <div style="max-width: 1100px;">
                    <p>Товары 
                    <?
                    foreach ($C_cat as $j) {
                    echo "$products[$j], "; 
                    }
                    ?>
                    относятся к категории C и являются наименее важными. Проведите анализ прибыльности и стоимости поддержки продуктов категории C. Если они приносят незначительный доход или требуют слишком много ресурсов, вы можете рассмотреть их устранение или сокращение. Поставьте ограничения на расходы и ресурсы, связанные с товарами категории C, если некоторые из них имеют потенциал для роста или могут быть важными для нишевых клиентов, вы можете продолжать их поддержку на минимальном уровне. 
                    </p>
                    </div><? } ?>
                    <? } ?>
                    <?php
                }
                if($abcAnalysis == 0){
                //XYZ анализ
                //Проверка на распределение выручки
                    $i = 0;
                    foreach ($ostatki  as $el) {
                        if (($el -  $revenues_base[$i]) != 0)
                        {
                            echo "$el - $revenues_base[$i]";
                            ?>
                            <h1>Выручка была распределена неправильно, проведение XYZ анализа невозможно!</h1>
                            <?php
                            $i = -1;
                            break;
                        }
                        else
                        {
                            $i = $i + 1;
                        }
                    }

                    if($i != -1)
                    { ?>
                        <h1>XYZ анализ</h1>
                        <h3>Шаг 1 - Формирование таблицы с выручкой для каждого месяца</h3>
                        <table>
                        <thead>
                            <tr>
                            <th>Продукты</th>
                            <?php
                               for($i = 0; $i < $numberInput; $i++)
                               {
                                $month = $i + 1;
                                echo "<th>Месяц $month</th>";
                               }     
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $key => $product) { ?>
                            <tr>
                                <td><?php echo $product; ?></td>
                                <?php                                
                                    // echo count($arrayDoubleOfDisRevenuesSorted[$i]);
                                    for ($j = 0; $j < count($arrayDoubleOfDisRevenuesSorted[$key]); $j++) {
                                        $zn = $arrayDoubleOfDisRevenuesSorted[$key][$j];
                                        echo "<td>$zn</td>"; 
                                    }
                                                                
                                ?>
                            </tr>
                            <?php } ?>                           
                        </tbody>
                        </table>

                        <h3>Шаг 2 - Расчет коэффициента вариации</h3>
                        <table>
                        <thead>
                            <tr>
                            <th>Продукты</th>
                            <?php
                               for($i = 0; $i < $numberInput; $i++)
                               {
                                $month = $i + 1;
                                echo "<th>Месяц $month</th>";
                               }     
                            ?>
                            <th>Коэффициент вариации</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $koef_arr = array();
                            foreach ($products as $key => $product) { ?>
                            <tr>
                                <td><?php echo $product; ?></td>
                                <?php 
                                $tempArr = array();
                                for ($j = 0; $j < count($arrayDoubleOfDisRevenuesSorted[$key]); $j++) {
                                    $zn = $arrayDoubleOfDisRevenuesSorted[$key][$j];
                                    echo "<td>$zn</td>"; 
                                    $tempArr[] = $zn;
                                }
                                $sred = array_sum($tempArr)/$numberInput;
                                $koef = calculateStandardDeviation($tempArr)/$sred*100;
                                $koef_arr[] = $koef;
                                ?>
                                 <td><?php echo round($koef,2); ?></td>
                            </tr>
                            <?php } ?>                           
                        </tbody>
                        </table>
                        <h3>Шаг 3 - Определение группы товара в зависимости от коэффициента вариации</h3>
                        <?php
                        $xyz_group = array();
                        foreach ($koef_arr as $el) {
                            if(round($el,2)<=10)
                            {
                                $xyz_group[] = 1;//X
                            }
                            if((round($el,2)>10) && (round($el,2)<=25))
                            {
                                $xyz_group[] = 2;//Y
                            }
                            if(round($el,2)>25)
                            {
                                $xyz_group[] = 3;//Z
                            }
                        }
                        ?>
                        <table>
                        <thead>
                            <tr>
                            <th>Продукты</th>
                            <?php
                               for($i = 0; $i < $numberInput; $i++)
                               {
                                $month = $i + 1;
                                echo "<th>Месяц $month</th>";
                               }     
                            ?>
                            <th>Коэффициент вариации</th>
                            <th>Группа</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($products as $key => $product) { ?>
                            <tr>
                                <td><?php echo $product; ?></td>
                                <?php 
                                $tempArr = array();
                                for ($j = 0; $j < count($arrayDoubleOfDisRevenuesSorted[$key]); $j++) {
                                    $zn = $arrayDoubleOfDisRevenuesSorted[$key][$j];
                                    echo "<td>$zn</td>"; 
                                    $tempArr[] = $zn;
                                }
                                $sred = array_sum($tempArr)/$numberInput;
                                $koef = calculateStandardDeviation($tempArr)/$sred*100;
                                ?>
                                <td><?php echo round($koef,2); ?></td>
                                <td><?php 
                                switch ($xyz_group[$key])
                                {
                                    case 1:
                                        echo "X";
                                        break;
                                    case 2:
                                        echo "Y";
                                        break;
                                    case 3:
                                        echo "Z";
                                        break;
                                }
                                ?></td>
                            </tr>
                            <?php } ?>                           
                        </tbody>
                        </table>
                        <?php
                        if($recommendations == 1)
                        {   
                            $X_cat = array_keys($xyz_group, 1);
                            $Y_cat = array_keys($xyz_group, 2);
                            $Z_cat = array_keys($xyz_group, 3);
                            ?>
                            <h3>Рекомендации по результатам XYZ анализа</h3>
                            <? if(count($X_cat)!=0) { ?>
                            <div style="max-width: 1100px;">
                            <p>Товары 
                            <?
                            foreach ($X_cat as $j) {
                            echo "$products[$j], "; 
                            }
                            ?>
                            относятся к категории X. Количество таких продуктов всегда должно быть в достаточном количестве чтобы удовлетворить спрос. Проводите маркетинговые кампании и рекламные активности, чтобы увеличить продажи и привлечь новых клиентов.  
                            </p>
                            </div><? } ?>

                            <? if(count($Y_cat)!=0) { ?>
                            <div style="max-width: 1100px;">
                            <p>Товары 
                            <?
                            foreach ($Y_cat as $j) {
                            echo "$products[$j], "; 
                            }
                            ?>
                            относятся к категории Y. Изучите и анализируйте спрос на продукты категории Y, чтобы понять основные требования клиентов. Разработайте акции и скидки, чтобы стимулировать спрос на эти продукты. 
                            </p>
                            </div><? } ?>

                            <? if(count($Z_cat)!=0) { ?>
                            <div style="max-width: 1100px;">
                            <p>Товары 
                            <?
                            foreach ($Z_cat as $j) {
                            echo "$products[$j], "; 
                            }
                            ?>
                            относятся к категории Z. В основном служат для знакомства клиента с продукцией компании поэтому лучше поставлять по предзаказу. Рассмотрите возможность пересмотра ценовой политики для категории Z, чтобы стимулировать продажи. Исследуйте возможности диверсификации или модификации товаров категории Z, чтобы повысить их привлекательность для клиентов 
                            </p>
                            </div><? } ?>
                            <?php
                        } ?>
                         <?php
                       
                    }
                }
            ?>
             <?php
            if($auth == true)
            {
                ?>
                    <form action="save_an.php" method="POST">
                    <?php

                    foreach ($products as $product) {
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
             ?>
        </div>
    </body>
</html>
<script>
function addRecord() {
  // Отправка AJAX-запроса к серверу для выполнения SQL-запроса
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "path/to/your-php-script.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Запрос успешно выполнен
      console.log(xhr.responseText);
    }
  };
  xhr.send("query=INSERT INTO `smash`.`an_list` (`anid`, `userid`, `type`, `date`) VALUES ('2', '1', 'ABC', '2023-05-21');");
}
</script>


