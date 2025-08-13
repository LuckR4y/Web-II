<html>
    <?php
        $matriz = array(
            array(1, 2, 3),
            array(4, 5, 6),
            array(7, 8, 9),
        );

        echo $matriz[0][0] . "<br>";
        echo $matriz[1][1] . "<br>";
        echo $matriz[2][2] . "<br>";

        foreach ($matriz as $linha) {
            foreach ($linha as $elemento) {
                echo $elemento . " ";
            
            }
            echo "<br>";
        }

        ?>
        </html>