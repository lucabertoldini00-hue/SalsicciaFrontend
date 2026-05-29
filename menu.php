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

        function cambia_pagine(categoria)
        {
            parent.centro.location.href='index.php?cat='+categoria;
            parent.riepilogo.document.getElementById('id_bc').focus();
        }

    </script>
    <script type="text/javascript" src="utility.js" ></script>

</head>
<body onkeypress="powerLost(event);">

<div id="menu_pan">
    <?php

    $query="SELECT * FROM categorie order by id_categoria";

    $ris = mysql_query_safe($mysqli, $query);
    print "<table border=\"0\"><tr>";
    while($riga = mysqli_fetch_array($ris))
    {
        print "<td><button class=\"bottone_menu\" style=\"	background-image: url(colori_bottoni_top/$riga[colore].png);\" onclick=\"cambia_pagine('$riga[id_categoria]')\">$riga[descrizione_cat]</button></td>\n";
    }
    ?>



</div>

</body>
</html>
