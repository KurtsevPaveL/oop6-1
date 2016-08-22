<?php

    $sourceSerialized = '../temp/data-serialized.txt'; // Вот и понадобился data.php :)
    $f = fopen($sourceSerialized, "r");
    $textT = fread($f, filesize($sourceSerialized));
    $Data = unserialize($textT);
    asort($Data);
    fclose($f);

// $im - идентификатор изображения
// $VALUES - массив со значениями
// $LEGEND - массив с подписями
    function Diagramm($im, $VALUES, $LEGEND) {
        GLOBAL $COLORS, $SHADOWS;

        $black = ImageColorAllocate($im, 0, 0, 0);

        // Получим размеры изображения
        $W = ImageSX($im);
        $H = ImageSY($im);

        // Вывод легенды #####################################
        // Посчитаем количество пунктов, от этого зависит высота легенды
        $legend_count = count($LEGEND);

        // Посчитаем максимальную длину пункта, от этого зависит ширина легенды
        $max_length = 0;
        foreach ($LEGEND as $v)
            if ($max_length < strlen($v))
                $max_length = strlen($v);

        // Номер шрифта, котором мы будем выводить легенду
        $FONT = 7;
        $font_w = ImageFontWidth($FONT);
        $font_h = ImageFontHeight($FONT);

        // Вывод прямоугольника - границы легенды ----------------------------

        $l_width = ($font_w * $max_length) + $font_h + 10 + 5 + 10;
        $l_height = $font_h * $legend_count + 10 + 10;


        // Получим координаты верхнего левого угла прямоугольника - границы легенды
        $l_x1 = $W - 10 - $l_width;
        $l_y1 = ($H - $l_height) / 2;

        // Выводя прямоугольника - границы легенды
        ImageRectangle($im, $l_x1, $l_y1, $l_x1 + $l_width, $l_y1 + $l_height, $black);

        // Вывод текст легенды и цветных квадратиков
        $text_x = $l_x1 + 10 + 5 + $font_h;
        $square_x = $l_x1 + 10;
        $y = $l_y1 + 10;

        $i = 0;
        foreach ($LEGEND as $v) {
            $dy = $y + ($i * $font_h);
            ImageString($im, $FONT, $text_x, $dy, $v, $black);
            ImageFilledRectangle($im, $square_x + 1, $dy + 1, $square_x + $font_h - 1, $dy + $font_h - 1, $COLORS[$i]);
            ImageRectangle($im, $square_x + 1, $dy + 1, $square_x + $font_h - 1, $dy + $font_h - 1, $black);
            $i++;
        }

        // Вывод круговой диаграммы ----------------------------------------

        $total = array_sum($VALUES);
        $anglesum = $angle = Array(0);
        $i = 1;

        // Расчет углов
        while ($i < count($VALUES)) {
            $part = $VALUES[$i - 1] / $total;
            $angle[$i] = floor($part * 360);
            $anglesum[$i] = array_sum($angle);
            $i++;
        }
        $anglesum[] = $anglesum[0];

        // Расчет диаметра
        $diametr = $l_x1 - 10 - 10;

        // Расчет координат центра эллипса
        $circle_x = ($diametr / 2) + 10;
        $circle_y = $H / 2 - 10;

        // Поправка диаметра, если эллипс не помещается по высоте
        if ($diametr > ($H * 2) - 10 - 10)
            $diametr = ($H * 2) - 20 - 20 - 40;

        // Вывод тени
        for ($j = 20; $j > 0; $j--)
            for ($i = 0; $i < count($anglesum) - 1; $i++)
                ImageFilledArc($im, $circle_x, $circle_y + $j, $diametr, $diametr / 2, $anglesum[$i], $anglesum[$i + 1], $SHADOWS[$i], IMG_ARC_PIE);

        // Вывод круговой диаграммы
        for ($i = 0; $i < count($anglesum) - 1; $i++)
            ImageFilledArc($im, $circle_x, $circle_y, $diametr, $diametr / 2, $anglesum[$i], $anglesum[$i + 1], $COLORS[$i], IMG_ARC_PIE);
    }

// Зададим значение и подписи
    $VALUES = Array(100, 200, 300, 400, 500, 400, 300);
    $LEGEND = Array("John", "Bob", "Alex", "Mike", "Andrew", "Greg");

// Создадим изображения
    header("Content-Type: image/png");
    $im = ImageCreate(500, 500);

// Зададим цвет фона. Немного желтоватый, для того, чтобы было
// видно границы изображения на белом фоне.
    $bgcolor = ImageColorAllocate($im, 255, 255, 255);

// Зададим цвета элементов
    $COLORS[0] = imagecolorallocate($im, 255, 203, 3);
    $COLORS[1] = imagecolorallocate($im, 220, 101, 29);
    $COLORS[2] = imagecolorallocate($im, 189, 24, 51);
    $COLORS[3] = imagecolorallocate($im, 214, 0, 127);
    $COLORS[4] = imagecolorallocate($im, 98, 1, 96);
    $COLORS[5] = imagecolorallocate($im, 0, 62, 136);
    $COLORS[6] = imagecolorallocate($im, 0, 102, 179);
    $COLORS[7] = imagecolorallocate($im, 0, 145, 195);
    $COLORS[8] = imagecolorallocate($im, 0, 115, 106);
    $COLORS[9] = imagecolorallocate($im, 178, 210, 52);
    $COLORS[10] = imagecolorallocate($im, 137, 91, 74);
    $COLORS[11] = imagecolorallocate($im, 82, 56, 47);

// Зададим цвета теней элементов
    $SHADOWS[0] = imagecolorallocate($im, 205, 153, 0);
    $SHADOWS[1] = imagecolorallocate($im, 170, 51, 0);
    $SHADOWS[2] = imagecolorallocate($im, 139, 0, 1);
    $SHADOWS[3] = imagecolorallocate($im, 164, 0, 77);
    $SHADOWS[4] = imagecolorallocate($im, 48, 0, 46);
    $SHADOWS[5] = imagecolorallocate($im, 0, 12, 86);
    $SHADOWS[6] = imagecolorallocate($im, 0, 52, 129);
    $SHADOWS[7] = imagecolorallocate($im, 0, 95, 145);
    $SHADOWS[8] = imagecolorallocate($im, 0, 65, 56);
    $SHADOWS[9] = imagecolorallocate($im, 128, 160, 2);
    $SHADOWS[10] = imagecolorallocate($im, 87, 41, 24);
    $SHADOWS[11] = imagecolorallocate($im, 32, 6, 0);

    //Вызов функции рисования диаграммы
    //Diagramm($im, $VALUES, $LEGEND);
    Diagramm($im, $Data, $Data);

// Генерация изображения
    ImagePNG($im);

