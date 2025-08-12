<html>
    <?php
        $pais = "br"; 
        if ($pais == "br") {
            print "Brasil<br>";
        }
        if ($pais == "br") {
            print "Brasil ";
            print "São Paulo";
        }

        switch ($pais) {
            case 'br'; 
            print "<br>Brasil";
                break;
            
            case 'ca'; 
            print "<br>Canada";
                break;

                case 'de'; 
            print "<br>Alemanha";
                break;

                default:
                "nenhum pais correspondente";
        }
    ?>
        </html>