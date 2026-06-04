<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include("set.inc");
include("dbConnect.php");
include("funzioni.inc");
?>
<html>
<head>
    <meta name="generator" content="HTML Tidy for Windows (vers 1 June 2005), see www.w3.org">
    <meta http-equiv="content-type" content="text/html; charset=windows-1250">
    <meta name="generator" content="PSPad editor, www.pspad.com">
    <title>
        Inserimento
    </title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <script language="javascript">

        function aumenta(id)
        {
            text = document.getElementById(id);
            valore = parseInt(text.value);
            if(valore < 99)
                valore ++;
            text.value = valore;
        }

        function diminuisci(id)
        {
            text = document.getElementById(id);
            valore = parseInt(text.value);
            if(valore > 1)
                valore --;
            text.value = valore;
        }

        function modifica(id, id_ord)
        {
            text = document.getElementById(id);
            qta = text.value;
            location.href='mod.php?id_prodotto='+id+'&id_ordine='+id_ord+'&qta='+qta;
        }

        function rimuovi(id, id_ord)
        {
            location.href='rem.php?id_prodotto='+id+'&id_ordine='+id_ord;
        }

    </script>
</head>
<body>

<div id="mod_pan">
    <?php
    $id_cassa = ID_CASSA;
    print "<div id=\"bott_pan\" style=\"background-image: url(colori_sfondo/11.png);\" >";

    print "\n<center><div id=\"titolo_cat\">MODIFICA ORDINE</div></center><br><br>";
    ################## PARTE DI VISUALIZZAZIONE ORDINE ####################
    $query="SELECT prodotti.id_prodotto AS id,prodotti.descrizione_prod, righe_ordini.quantita, righe_ordini.totale, ordini.totale as tot_ord, 
     ordini.id_ordine AS id_ord FROM prodotti, ordini, righe_ordini WHERE 
     ordini.id_ordine = righe_ordini.id_ordine AND prodotti.id_prodotto = righe_ordini.id_prodotto AND
     id_cassa = $id_cassa AND ordini.chiuso = 0";
    $ris = mysql_query_safe($mysqli,$query);
    if(mysqli_num_rows($ris) > 0)
    {
        $riga = mysqli_fetch_array($ris);
        mysqli_data_seek($ris,0);
        $totale = $riga['tot_ord'];
    }
    else
        $totale = "0";
    $totale = number_format($totale, 2, ',', '.');
    //print "<center><h3>Totale $totale &euro;</h3></center>";

    print "<table border=\"0\" width=\"100%\"><tr class=\"mod\"><td>Descrizione</td><td>Q.t&agrave;</td><td>&nbsp;</td><td>Costo unitario</td><td>Conf.</td><td>Elim.</td></tr>";
    while($riga = mysqli_fetch_array($ris))
    {
        $prezzo = number_format($riga['totale'], 2, ',', '.');
        print "<tr class=\"mod\">
        <td >$riga[descrizione_prod]</td> 
        <td >
            <input style=\"width: 55px; height:55px; font-size: 45px;\" type=\"text\" size=\"2\" maxlength=\"2\" id=\"$riga[id]\" value=\"$riga[quantita]\">
			</td><td>
            <button class=\"bottone_conf_mini\" name=\"aumenta\" onclick=\"aumenta('$riga[id]');\">+1</button>
            <button class=\"bottone_conf_mini\" name=\"diminuisci\" onclick=\"diminuisci('$riga[id]');\">-1</button>
        </td> 
        <td>$prezzo &euro;</td>
        <td><button class=\"bottone_conf_mini\" name=\"ok\" onclick=\"modifica('$riga[id]', '$riga[id_ord]');\">OK</button></td>
         <td><button class=\"bottone_conf_mini\" name=\"cancella\" onclick=\"rimuovi('$riga[id]', '$riga[id_ord]');\">X</button></td></tr>\n";
    }
    print "</table>";
    print "<center><br><button class=\"bottone_conf\" onclick=\"location.href='index.php'\"><b>CONTINUA ORDINE</b></button> 
                 <button class=\"bottone_conf\" onclick=\"location.href='stampa.php'\"><b>STAMPA</b></button></center>";

    ?>
</div>

</div>

</body>
</html>
