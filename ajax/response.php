<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
//==============================================================================
//BONES AND PDO=================================================================
    $nameTester = filter_input(INPUT_POST, "nameTester");
    if (isset($nameTester)) {
        //SAY HELLO TO VISITOR:
        echo "Здравствуйте, $nameTester!<br> Вы бросили кубики 36000 раз, мы записали для вас результаты:<br>";

        //THROWING BONES:
        for ($i = 0; $i < 36000; $i++) {
            $num1 = mt_rand(1, 6);
            $num2 = mt_rand(1, 6);
            $sum = $num1 + $num2;
            switch ($sum):
                case(2):
                    $arrNum2 [] = $sum;
                    break;
                case(3):
                    $arrNum3[] = $sum;
                    break;
                case(4):
                    $arrNum4[] = $sum;
                    break;
                case(5):
                    $arrNum5[] = $sum;
                    break;
                case(6):
                    $arrNum6[] = $sum;
                    break;
                case(7):
                    $arrNum7[] = $sum;
                    break;
                case(8):
                    $arrNum8[] = $sum;
                    break;
                case(9):
                    $arrNum9[] = $sum;
                    break;
                case(10):
                    $arrNum10[] = $sum;
                    break;
                case(11):
                    $arrNum11[] = $sum;
                    break;
                case(12):
                    $arrNum12[] = $sum;
                    break;
            endswitch;
        }

        //CREATING PDO:
        require_once '../dbpass.php';
        try {
            $dbh = new PDO("mysql:host=localhost; dbname=$db", $user, $pass);
            $dbh->execute("set names utf8");

            $sqlExperTabl = ""
                . "INSERT INTO experiment"
                . " ( date, time, name, bones_num, throws)"
                . "VALUES"
                . " ( :date, :time, :name, :bones_num, :throws)";

            $q = $dbh->prepare($sqlExperTabl);
            $q->execute(array(":date" => date('d-m-Y'), ":time" => date('h:i:s'), ":name" => $nameTester, ":bones_num" => 2, ":throws" => 36000));
            $id = $dbh->lastInsertId();

            $sqlResTabl = ""
                . "INSERT INTO results"
                . "(num, count, id_exp)"
                . "VALUES"
                . "(:num, :count, :id_exp)";

            for ($i = 2; $i < 13; $i ++) {
                $throws = count(${'arrNum' . $i});
                $q->execute(array(":num" => $i, ":count" => $throws, ":id_exp" => "$id"));
            }

            $dbh = NULL;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        //OUTPUT:
        $outPut = "";
        for ($i = 2; $i < 13; $i++) {
            $dateValues[] = $res[$i] = count(${'arrNum' . $i});
            $friction = round((36000 / $res[$i]));
            //$dateLegend[] = $i;//TODO MAKE
            $dataArr[$friction] = $res[$i];
            $outPut .= "Сумма '$i' выпала " . $res[$i] . " раз и составляет '1/" . $friction . "' долю от всех бросков<br>";
        }
        echo $outPut;
//======================================================================================================
////======//IMAGE VARIANT A img/image.php //TO DO
//        $file1 = fopen("../temp/data-serialized-values.txt", "w");
//        $dataSerializedValues = serialize(asort($dateValues));
//        fwrite($file1, $dataSerializedValues);
//        fclose($file1);
//
//        $file2 = fopen("../temp/data-serialized-legends.txt", "w");
//        $dataSerializedLegend = serialize($dateLegend);
//        fwrite($file2, $dataSerializedLegend);
//        fclose($file2);
//        //echo "<img id='image' src='img/image.php'/>";//TO DO: WILL SHOW THE SUM OF BONES, NOT THE NUMBER OF TIMES THEY WERE DROPED
//======//VARIANT B img/image1.php //ARRAY WITH NUMBERS OF TIMES BONES FELT
        $file3 = fopen("../temp/data-serialized.txt", "w");
        $dataSerialized = serialize($res);
        fwrite($file3, $dataSerialized);
        fclose($file3);
        print "<div id='imag'><img id='image' src='img/image1.php?a=$id'/></div>";
    }




