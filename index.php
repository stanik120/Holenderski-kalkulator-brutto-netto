<?php
function wynik(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST['godzin'] <= 38){     #Jeżeli w tygodniu przepracowałeś mniej lub 38 godzin
            $brutto = $_POST['godzin'] * $_POST['brutto'];
            $nadgodzin = 0;
        }
        elseif ($_POST['godzin'] > 38){  #Jeżeli w tygodniu przepracowałeś więcej niż 38 godzin
            $nadgodzin = $_POST['godzin'] - 38;
            $brutto = (38 * $_POST['brutto']) + ($nadgodzin * $_POST['brutto'] * 1.35);
        }
        
        $ubezpieczenie = 28;
        $podatek = 0.052; #20%
        $netto = $brutto - ($brutto * $podatek);

        echo "Przepracowałeś " . $_POST['godzin'] . " godzin w tym " . $nadgodzin ." to nadgodziny.<br/>";
        echo "Wysokość podatków: " . $podatek * 100 . "%<br/>";
        echo "Kwota Brutto: " . round($brutto, 2) . "€<br/>";
        echo "Kwota Netto: " . round($netto, 2) . "€<br/>";
        echo "Zarboiłeś z nadgodzin(brutto): " . round(($nadgodzin * $_POST['brutto']), 2) . "€ (135%)<br/>";
        echo "Zarboiłeś z nadgodzin(netto): "; echo round(($nadgodzin * $_POST['brutto']) - ($nadgodzin * $_POST['brutto'] * $podatek), 2) . "€<br/>";
        echo "Dodatkowe opłaty: -" . $_POST['oplaty'] . "€<br/>";
        echo "Ubezpieczenie: -" . $ubezpieczenie . "€<br/>";
        echo "Po odjęciu ubezpieczenia oraz dodatkowych opłat powinno zostać Ci "; echo round($netto - $ubezpieczenie - $_POST['oplaty'], 2) . "€ na ręke.<br/>";
    }
}

function cookie_brutto(){
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        setcookie('brutto', $_POST['brutto']);
        return $_POST['brutto'];
    }
    else
        if(isset($_COOKIE['brutto']))
            return $_COOKIE['brutto'];
        else
            return 10.21;
}

function cookie_oplaty(){
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        setcookie('oplaty', $_POST['oplaty']);
        return $_POST['oplaty'];
    }
    else
        if(isset($_COOKIE['oplaty']))
            return $_COOKIE['oplaty'];
        else
            return 100;
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>
        Kalkulator wynagrodzenia w Holandii
    </title>
</head>

<body>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        Ilość przepracowanych godzin w tygodniu:
        <input type="number" required="required" name="godzin"><br />
        Stawka brutto:
        <input type="number" required="required" min="1" step="0.01" name="brutto" value="<?php echo cookie_brutto() ?>"><br />
        Dodatkowe opłaty pobierane przez pracodawcę:
        <input type="number" required="required" step="0.01" min="0" name="oplaty" value="<?php echo cookie_oplaty() ?>"><br />
        <input type="submit">
    </form>

    <?php wynik() ?>
    
</body>

</html>