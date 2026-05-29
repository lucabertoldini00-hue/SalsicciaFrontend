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
        function mostraDiv(id)
        {
            document.getElementById(id).style.visibility = "visible";
        }

        function nascondiDiv(id)
        {
            document.getElementById(id).style.visibility = "hidden";
        }

        function codeAdd(val)
        {
            code = document.getElementById('codice').value;
            code = code + val;
            document.getElementById('codice').value = code;
        }

        function codeReset()
        {
            document.getElementById('codice').value = '';
        }

        function send()
        {
            code = document.getElementById('codice').value;
            parent.centro.location.href='config.php?code=' + code;
        }
    </script>
    <script type="text/javascript" src="utility.js" ></script>
</head>
<body onkeypress="powerLost(event);" onload="document.getElementById('id_bc').focus();">

<div id="riep_pan">
    <?php

    print "<div id=\"titolo_riepilogo\">Riepilogo Ordine<br><input type=\"text\" name=\"barcode\" id=\"id_bc\" size=\"20\" onkeypress=\"sendBarcode(event)\";></div><br>";
    $id_cassa = ID_CASSA;
    ################## SEZIONE DI CANCELLAZIONE DELL'ORDINE ####################
    if(isSet($HTTP_GET_VARS['action']))
    {
        if($HTTP_GET_VARS['action'] == "r")
        {
            $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
            $ris = mysql_query_safe($mysqli, $query);
            if(mysqli_num_rows($ris) == 1)
            {
                //$id_ordine = mysql_result($ris, 0, "id_ordine");
                $id_ordine = mysqli_fetch_array($ris);
                $id_ordine = $id_ordine['id_ordine'];
                $query = "DELETE FROM ordini WHERE id_ordine = $id_ordine AND id_cassa = $id_cassa AND chiuso = '0'";
                $ris = mysql_query_safe($mysqli, $query);
                $query = "DELETE FROM righe_ordini WHERE id_ordine = $id_ordine";
                $ris = mysql_query_safe($mysqli, $query);

                print "\n<script>location.href='riepilogo.php';</script>";
            }
        }
    }
    ################## SEZIONE DI AGGIUNTA PRODOTTI ALL' ORDINE ####################
    if((isSet($HTTP_GET_VARS['id']) || isSet($HTTP_GET_VARS['bc'])) && isSet($HTTP_GET_VARS['action']))
    {

        #$bc = $mysqli->real_escape_string($HTTP_GET_VARS['bc']);
        $bc = $HTTP_GET_VARS['bc'];
        //print "\n<script>alert('Aggiunta ORDINE');</script>";
        #Bisogna stabilire se c'� un ordine associato alla cassa aperto o no
        $query = "SELECT * FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
        $ris = mysql_query_safe($mysqli, $query);
        #Se troviamo un ordine ancora aperto stabiliamo se il prodotto da aggiungere � gia nell'ordine o no
        if(mysqli_num_rows($ris) == 1)
        {
            $id_ordine = mysqli_fetch_array($ris);
            $id_ordine = $id_ordine['id_ordine'];

            #Query per sapere se il prodotto che st� aggiungendo � gi� nell'ordine o no
            if(isSet($HTTP_GET_VARS['id']))
                $query = "SELECT * FROM righe_ordini WHERE id_ordine = $id_ordine  AND id_prodotto = $HTTP_GET_VARS[id]";
            else
                $query = "SELECT * FROM righe_ordini, prodotti WHERE righe_ordini.id_prodotto = prodotti.id_prodotto AND id_ordine = $id_ordine AND barcode = \"$bc\"";

            $ris = mysql_query_safe($mysqli, $query);
            #Se il prodotto � gi� nell'ordine incremento la sua quantit� di 1 unit�
            if(mysqli_num_rows($ris) == 1)
            {
                //print "<script>alert('Prodotto gia in ordine');</script>";
                $riga = mysqli_fetch_array($ris);
                $id_prodotto = $riga['id_prodotto'];
                $query = "UPDATE righe_ordini set quantita = quantita +1 WHERE id_ordine = $id_ordine 
                AND id_prodotto = $id_prodotto";
                $ris = mysql_query_safe($mysqli, $query);
            }
            #Altrimenti aggiungo il prodotto all'ordine
            else
            {
                if(isSet($HTTP_GET_VARS['id']))
                    $query = "SELECT * FROM prodotti WHERE id_prodotto = $HTTP_GET_VARS[id]";
                else
                    $query = "SELECT * FROM prodotti WHERE barcode = \"$bc\"";
                $ris = mysql_query_safe($mysqli, $query);
                $prodotto = mysqli_fetch_array($ris);

                if(mysqli_num_rows($ris) > 0)
                {
                    /*$query = "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale,imp4,imp10,imp22)VALUES
                    ($id_ordine, '$HTTP_GET_VARS[id]', '1', $prodotto[prezzo], $prodotto[imp4], $prodotto[imp10], $prodotto[imp22]);";*/
                    $query = "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale)VALUES 
                ($id_ordine, '$prodotto[id_prodotto]', '1', $prodotto[prezzo]);";
                    //print "Query: $query";
                    $ris = mysql_query_safe($mysqli, $query);
                    $id_prodotto = $prodotto['id_prodotto'];
                }
                else
                {
                    print "<br><br><br><br><br><br><br><br><br><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Codice prodotto NON trovato.</h2>";
                    $id_prodotto = null;
                }
            }
        }
        else
        {
            #Se non trovo un ordine aperto ne creo uno nuovo al quale aggiungo il prodotto
            #print "<script>alert('Prodotto non in ordine');</script>";
            $query = "INSERT INTO ordini (data_ora,tipo,totale,n_pezzi,id_cassa,chiuso,num_biglietti)VALUES 
            (NOW(), 'nor', '0', '0', $id_cassa, '0', 0);";
            $ris = mysql_query_safe($mysqli, $query);
            $id_ordine = mysqli_insert_id($mysqli);
            if(isSet($HTTP_GET_VARS['id']))
                $query = "SELECT * FROM prodotti WHERE id_prodotto = $HTTP_GET_VARS[id]";
            else
                $query = "SELECT * FROM prodotti WHERE barcode = \"$bc\"";
            $ris = mysql_query_safe($mysqli, $query);
            $prodotto = mysqli_fetch_array($ris);
            /*$query = "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale,imp4,imp10,imp22)VALUES
            ($id_ordine, '$HTTP_GET_VARS[id]', '1', $prodotto[prezzo], $prodotto[imp4], $prodotto[imp10], $prodotto[imp22]);";*/

            if(mysqli_num_rows($ris) > 0)
            {
                $query = "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale)VALUES 
                ($id_ordine, '$prodotto[id_prodotto]', '1', $prodotto[prezzo]);";
                $ris = mysql_query_safe($mysqli, $query);
                $id_prodotto = $prodotto['id_prodotto'];
            }
            else
                $id_prodotto = null;
        }
        calcolaTotali($id_prodotto, $id_ordine, $mysqli);
    }
    ################## PARTE DI VISUALIZZAZIONE ORDINE ####################
    $query="SELECT prodotti.descrizione_prod, righe_ordini.quantita, righe_ordini.totale, ordini.totale as tot_ord, ordini.tipo as tipo, ordini.id_ordine
     FROM prodotti, ordini, righe_ordini WHERE 
     ordini.id_ordine = righe_ordini.id_ordine AND prodotti.id_prodotto = righe_ordini.id_prodotto AND id_cassa = $id_cassa 
     AND ordini.chiuso = '0'";

    $ris = mysql_query_safe($mysqli, $query);
    if(mysqli_num_rows($ris) > 0)
    {
        $riga = mysqli_fetch_array($ris);
        mysqli_data_seek($ris,0);
        $totale = $riga['tot_ord'];
        $tipo = $riga['tipo'];
        $id = $riga['id_ordine'];
        $l = "";
        if($tipo == "pre")
            $l = "P";
        if($tipo == "mus")
            $l = "M";
        if($tipo == "stf")
            $l = "S";
        if($tipo == "asp")
            $l = "A";
        if($l != "")
            print "\n<div id=\"tag\">$l</div>\n";

        $totale = number_format($totale, 2, ',', '.');
        print "<div id=\"totale_riepilogo\">#$id - Totale $totale &euro;</div>";

        print "<div id=\"bott_riepilogo\">
         <button class=\"bottone_conf\" name=\"mod\" onclick=\"parent.centro.location.href='modifica.php'\"><b>Modifica Ordine</b></button>
         <button class=\"bottone_conf\" name=\"opt\" onclick=\"parent.centro.location.href='options.php'\"><b>Opzioni<b></button>
         </div>";

        print "<div id=\"lista_riepilogo\"><br><table border=\"0\" width=\"100%\">";
        while($riga = mysqli_fetch_array($ris))
        {
            $prezzo = number_format($riga['totale'], 2, ',', '.');
            $desc = strtoupper($riga['descrizione_prod']);
            $desc = htmlentities($desc);
            print "\n<tr class=\"tr_lista_riepilogo\"><td align=\"right\">$riga[quantita]</td><td>$desc</td><td align=\"right\">$prezzo &euro;</td></tr>";
            #print "$riga[quantita] $riga[descrizione_prod] $prezzo &euro;<br>";
        }
        print "</table>";
    }
    else
    {
        $totale = "0";
        $totale = number_format($totale, 2, ',', '.');
        print "<div id=\"totale_riepilogo\">Totale $totale &euro;</div>";

        print "<div id=\"tasto_conf\">
         <button class=\"bottone_conf\" name=\"conf\" onclick=\"codeReset(); mostraDiv('conf_code');\"><b>Configurazione</b></button>
         <button class=\"bottone_conf\" name=\"ordSb\" onclick=\"parent.centro.location.href='orderStandBy.php'\"><b>Ordini in Standby</b></button></div>";
        #PARTE PER ACCEDERE ALLA CONFIGURAZIONE
        print "<div id=\"conf_code\">\n
        <center>
        Inserire il codice:<br>
        <input type=\"password\" style=\"font-size: 40px;\" name=\"code\" size=\"7\" id=\"codice\">
        <table border=\"0\" width=\"55%\"><tr align=\"center\">\n
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('1');\">1</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('2');\">2</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('3');\">3</button></td></tr>\n
        <tr align=\"center\">\n
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('4');\">4</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('5');\">5</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('6');\">6</button></td></tr>\n
        <tr align=\"center\">\n
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('7');\">7</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('8');\">8</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('9');\">9</button></td></tr>\n
        <tr align=\"center\">\n
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('*');\">*</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('0');\">0</button></td>
        <td><button class=\"bottone_conf_mini\" onclick=\"codeAdd('@');\">@</button></td></tr>\n</table><br>
        
        <button class=\"bottone_conf\" onclick=\"send(); nascondiDiv('conf_code');\">OK</button>
        <button class=\"bottone_conf\" onclick=\"codeReset();\">CANC</button><br>
        <button class=\"bottone_conf\" onclick=\"codeReset(); nascondiDiv('conf_code');\">Chiudi</button>
        
        </center>
        </div>";
    }

    ?>
    <div id="clock"><?php print date("d/m/Y H:i:s");?></div>
</div>
</div>

</body>
</html>
