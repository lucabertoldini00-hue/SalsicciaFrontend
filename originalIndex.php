<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include("set.inc");
include("dbConnect.php");
include("funzioni.inc");

$but_x_row = BOT_X_ROW;
$but_x_col = BOT_X_COL;

$bot_width = BOT_WIDTH;
$bot_height = BOT_HEIGHT;

/*$col_width = COL_WIDTH;
$col_height = COL_HEIGHT;*/

?>
<html>
<head>
    <meta name="generator" content="HTML Tidy for Windows (vers 1 June 2005), see www.w3.org">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="generator" content="PSPad editor, www.pspad.com">
    <title>
        Inserimento
    </title>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    <script type="text/javascript" src="utility.js" ></script>
</head>
<body onLoad="" onkeypress="powerLost(event);">
<?php
if(isSet($HTTP_GET_VARS['cat']))
    $cat = $HTTP_GET_VARS['cat'];
else
    $cat = DEFAULT_CAT;

if(ONLY_ONE_CATEGORY)
    $style = " height: 1024px;";
else
    $style = "";

$query="SELECT * FROM prodotti, categorie, prodotti_categorie WHERE 
      prodotti_categorie.id_prodotto = prodotti.id_prodotto AND 
      prodotti_categorie.id_categoria = categorie.id_categoria AND categorie.id_categoria = $cat order by posizione";

//print "Query: $query";

$ris = mysql_query_safe($mysqli, $query);
if(mysqli_num_rows($ris) == 0)
{

    $query="SELECT * FROM categorie WHERE id_categoria = $cat";
    $ris = mysql_query_safe($mysqli, $query);
    $riga = mysqli_fetch_array($ris);
    print "<div id=\"bott_pan\" style=\"background-image: url(colori_sfondo/$riga[colore].png);$style\" >";

    print "<center><h1>NESSUN PRODOTTO IN QUESTA CATEGORIA</h1><center></div>";
}
else
{
    $riga = mysqli_fetch_array($ris);

    print "<div id=\"bott_pan\" style=\"background-image: url(colori_sfondo/$riga[colore].png);$style\" >";

    if(!ONLY_ONE_CATEGORY)
        print "\n<center><div id=\"titolo_cat\">$riga[descrizione_cat]</div></center><br><br>";

    print "<table border=\"0\" width=\"100%\">\n<tr>";

    $but_x_pag = $but_x_col * $but_x_row;
    $control = $riga['posizione'];
    if($riga["posizione"]==0)
    {
        print("	<script language=\"javascript\">
							alert(\"esiste un elemento di posizione 0, controllare\")
						</script>");
    }
    for($i=1; $i<=$but_x_pag; $i++)
    {
        if($control == $i)
        {
            $prezzo = number_format($riga['prezzo'], 2, ',', '.');
            $desc = strtoupper($riga['descrizione_prod']);
            //$desc = htmlentities($desc);
            print "<td class=\"td_bottone\"><button class=\"bottone\" id=\"button_$i\" style=\"width: $bot_width; height: $bot_height;\" onclick=\"parent.riepilogo.location.href='riepilogo.php?id=$riga[id_prodotto]&prezzo=$riga[prezzo]&action=a'\">$desc<br><b>&euro; $prezzo</b></button></td>";
            $riga = mysqli_fetch_array($ris,MYSQLI_ASSOC);
            if(!is_null($riga))
                $control = $riga['posizione'];
        }
        else
            print "<td class=\"td_bottone\">&nbsp;</td>";
        if($i%$but_x_col == 0)
            print "</tr>\n<tr>";
    }
    print "</tr></table>";
    print "<div id=\"bottoni_conf\">
		  <button class=\"bottone_conf\" onclick=\"parent.riepilogo.location.href='riepilogo.php?action=r'\"><b>ANNULLA ORDINE</b></button><button class=\"bottone_conf\" onclick=\"location.href='stampa.php'\"><b>STAMPA</b></button></div>";
}
?>
</div>
</body>
</html>
