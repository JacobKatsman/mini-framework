<?php

namespace AppName;

class TemplateEgngine
{
    /**
     *  здесь  форма для ввода url-a
     */
    public function renderGeTUrlFormTemplate()
    {
        echo "<html>";
        echo '<form action="/getform"  method="get">';
        echo '<label for="fname">Введите URL:</label><br>';
        echo   '<input type="text" id="file" name="file"><br>';
        echo '<input type="submit" value="Го(GO)">';
        echo '</form>';
        echo "</html>";
    }
    /**
     *  здесь сетка с картинками и размерами
     */
    public function renderTemplate($arrList)
    {
        echo "<html>";
        $count = 0;
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($arrList['listFiles'] as $k => $v) {
            $count++;
            $pieces = explode("/", $v['file']);
            $path = $pieces[count($pieces) - 2]  ."/".  $pieces[count($pieces)-1];
            echo "<td>";
            echo "<td>";
            preg_match('/.+\.(\w+)$/xis', $path, $ext);

            if ($ext[1] !='') {
                echo "<img widht='100px'   
                       height='100px' src='http://127.0.0.1:8080/showimage?file=" . $path . "'>"
                    . number_format($v['size'] / 1024, 2, '.', '') . " Кб</td>";
            }
            echo "</td>";
            if ($count % 4 == 0) {
                echo '</tr><tr>';
            }
        }
        echo "</table>";

        echo "Всего файлов: (".$arrList['imgCounter'].")  ";
        echo "Общий размер: " .number_format($arrList['totalSize'] / 1024, 2, '.', '') ."Кб";

        echo "</html>";
    }
}

