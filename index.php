<?php
include("set.inc");
include("dbConnect.php");
include("funzioni.inc");

$but_x_row = BOT_X_ROW;
$but_x_col = BOT_X_COL;
$bot_width  = BOT_WIDTH;
$bot_height = BOT_HEIGHT;
$id_cassa   = ID_CASSA;

if (isset($HTTP_GET_VARS['cat']))
    $cat = $HTTP_GET_VARS['cat'];
else
    $cat = DEFAULT_CAT;

if (isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == "r") {
    $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
    $ris = mysql_query_safe($mysqli, $query);
    if (mysqli_num_rows($ris) == 1) {
        $id_ordine = mysqli_fetch_array($ris);
        $id_ordine = $id_ordine['id_ordine'];
        mysql_query_safe($mysqli, "DELETE FROM ordini WHERE id_ordine = $id_ordine AND id_cassa = $id_cassa AND chiuso = '0'");
        mysql_query_safe($mysqli, "DELETE FROM righe_ordini WHERE id_ordine = $id_ordine");
    }
    header("Location: index.php");
    exit;
}


if ((isset($HTTP_GET_VARS['id']) || isset($HTTP_GET_VARS['bc'])) && isset($HTTP_GET_VARS['action'])) {
    $bc = isset($HTTP_GET_VARS['bc']) ? $HTTP_GET_VARS['bc'] : '';

    $query = "SELECT * FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
    $ris   = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) == 1) {
        $id_ordine = mysqli_fetch_array($ris);
        $id_ordine = $id_ordine['id_ordine'];

        if (isset($HTTP_GET_VARS['id']))
            $q2 = "SELECT * FROM righe_ordini WHERE id_ordine = $id_ordine AND id_prodotto = $HTTP_GET_VARS[id]";
        else
            $q2 = "SELECT * FROM righe_ordini, prodotti WHERE righe_ordini.id_prodotto = prodotti.id_prodotto AND id_ordine = $id_ordine AND barcode = \"$bc\"";

        $ris2 = mysql_query_safe($mysqli, $q2);

        if (mysqli_num_rows($ris2) == 1) {
            $riga2      = mysqli_fetch_array($ris2);
            $id_prodotto = $riga2['id_prodotto'];
            mysql_query_safe($mysqli, "UPDATE righe_ordini SET quantita = quantita + 1 WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto");
        } else {
            if (isset($HTTP_GET_VARS['id']))
                $qp = "SELECT * FROM prodotti WHERE id_prodotto = $HTTP_GET_VARS[id]";
            else
                $qp = "SELECT * FROM prodotti WHERE barcode = \"$bc\"";
            $risp    = mysql_query_safe($mysqli, $qp);
            $prodotto = mysqli_fetch_array($risp);
            if (mysqli_num_rows($risp) > 0) {
                $id_prodotto = $prodotto['id_prodotto'];
                mysql_query_safe($mysqli, "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale) VALUES ($id_ordine, '$id_prodotto', '1', $prodotto[prezzo])");
            } else {
                $id_prodotto = null;
            }
        }
    } else {
        mysql_query_safe($mysqli, "INSERT INTO ordini (data_ora,tipo,totale,n_pezzi,id_cassa,chiuso,num_biglietti) VALUES (NOW(),'nor','0','0',$id_cassa,'0',0)");
        $id_ordine = mysqli_insert_id($mysqli);

        if (isset($HTTP_GET_VARS['id']))
            $qp = "SELECT * FROM prodotti WHERE id_prodotto = $HTTP_GET_VARS[id]";
        else
            $qp = "SELECT * FROM prodotti WHERE barcode = \"$bc\"";
        $risp    = mysql_query_safe($mysqli, $qp);
        $prodotto = mysqli_fetch_array($risp);

        if (mysqli_num_rows($risp) > 0) {
            $id_prodotto = $prodotto['id_prodotto'];
            mysql_query_safe($mysqli, "INSERT INTO righe_ordini (id_ordine,id_prodotto,quantita,totale) VALUES ($id_ordine, '$id_prodotto', '1', $prodotto[prezzo])");
        } else {
            $id_prodotto = null;
        }
    }
    calcolaTotali($id_prodotto, $id_ordine, $mysqli);
    header("Location: index.php?cat=$cat");
    exit;
}

$query_riep = "SELECT prodotti.descrizione_prod, righe_ordini.quantita, righe_ordini.totale,
               ordini.totale AS tot_ord, ordini.tipo AS tipo, ordini.id_ordine
               FROM prodotti, ordini, righe_ordini
               WHERE ordini.id_ordine = righe_ordini.id_ordine
                 AND prodotti.id_prodotto = righe_ordini.id_prodotto
                 AND id_cassa = $id_cassa AND ordini.chiuso = '0'";
$ris_riep = mysql_query_safe($mysqli, $query_riep);
$has_order = (mysqli_num_rows($ris_riep) > 0);

if ($has_order) {
    $prima_riga = mysqli_fetch_array($ris_riep);
    mysqli_data_seek($ris_riep, 0);
    $totale_ord  = number_format($prima_riga['tot_ord'], 2, ',', '.');
    $tipo_ord    = $prima_riga['tipo'];
    $id_ord      = $prima_riga['id_ordine'];
    $tag_lettera = '';
    if ($tipo_ord == "pre") $tag_lettera = "P";
    if ($tipo_ord == "mus") $tag_lettera = "M";
    if ($tipo_ord == "stf") $tag_lettera = "S";
    if ($tipo_ord == "asp") $tag_lettera = "A";
} else {
    $totale_ord  = number_format(0, 2, ',', '.');
    $id_ord      = '';
}


$query_prod = "SELECT * FROM prodotti, categorie, prodotti_categorie
               WHERE prodotti_categorie.id_prodotto = prodotti.id_prodotto
                 AND prodotti_categorie.id_categoria = categorie.id_categoria
                 AND categorie.id_categoria = $cat
               ORDER BY posizione";
$ris_prod = mysql_query_safe($mysqli, $query_prod);
$prodotti_vuoti = (mysqli_num_rows($ris_prod) == 0);

if (!$prodotti_vuoti) {
    $prima_prod = mysqli_fetch_array($ris_prod);
    mysqli_data_seek($ris_prod, 0);
    $colore_sfondo = $prima_prod['colore'];
    $nome_cat      = $prima_prod['descrizione_cat'];
} else {
    $query_cat = "SELECT * FROM categorie WHERE id_categoria = $cat";
    $ris_cat   = mysql_query_safe($mysqli, $query_cat);
    $riga_cat  = mysqli_fetch_array($ris_cat);
    $colore_sfondo = $riga_cat['colore'];
    $nome_cat      = $riga_cat['descrizione_cat'];
}

$query_cat_all = "SELECT * FROM categorie ORDER BY id_categoria";
$ris_cat_all   = mysql_query_safe($mysqli, $query_cat_all);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALSICCIA V. 1.0</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="utility.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <script>

        function cambia_categoria(id_cat) {
            window.location.href = 'index.php?cat=' + id_cat;
        }

        function sendBarcode(e) {
            if (e.keyCode == 13) {
                var bc = document.getElementById('id_bc').value;
                if (bc != '') {
                    window.location.href = 'index.php?cat=<?php echo $cat; ?>&bc=' + bc + '&action=a';
                }
            }
        }

        function codeAdd(val) {
            document.getElementById('codice').value += val;
        }
        function codeReset() {
            document.getElementById('codice').value = '';
        }
        function send() {
            var code = document.getElementById('codice').value;
            window.location.href = 'config.php?code=' + code;
        }
        function mostraDiv(id)   { document.getElementById(id).style.visibility = 'visible'; }
        function nascondiDiv(id) { document.getElementById(id).style.visibility = 'hidden';  }
    </script>
</head>
<body onkeypress="powerLost(event);">


<main>

    <?php if (!ONLY_ONE_CATEGORY): ?>
        <nav id="menu_pan">
            <?php while ($riga_m = mysqli_fetch_array($ris_cat_all)): ?>
                <button class="bottone_menu"
                        style="background-image: url(colori_bottoni_top/<?php echo $riga_m['colore']; ?>.png);"
                        onclick="cambia_categoria('<?php echo $riga_m['id_categoria']; ?>')">
                    <?php echo $riga_m['descrizione_cat']; ?>
                </button>
            <?php endwhile; ?>
        </nav>
    <?php endif; ?>

    <header>
        <h1><?php echo htmlspecialchars($nome_cat); ?></h1>
    </header>

    <section id="main"
             style="background-image: url(colori_sfondo/<?php echo $colore_sfondo; ?>.png);">

        <?php if ($prodotti_vuoti): ?>
            <center><h1>NESSUN PRODOTTO IN QUESTA CATEGORIA</h1></center>
        <?php else: ?>
            <table id="products-container">
                <tbody>
                <?php
                $but_x_pag = $but_x_col * $but_x_row;
                $riga_prod = mysqli_fetch_array($ris_prod, MYSQLI_ASSOC);
                $control   = $riga_prod['posizione'];

                if ($control == 0) {
                    echo '<script>alert("esiste un elemento di posizione 0, controllare");</script>';
                }

                for ($i = 1; $i <= $but_x_pag; $i++) {
                    if ($i == 1) echo "<tr>";

                    if ($control == $i) {
                        $prezzo = number_format($riga_prod['prezzo'], 2, ',', '.');
                        $desc   = strtoupper($riga_prod['descrizione_prod']);
                        echo "<td class=\"td_bottone\">
                            <button class=\"bottone\" id=\"button_$i\"
                                    style=\"width:{$bot_width}px; height:{$bot_height}px;\"
                                    onclick=\"window.location.href='index.php?cat=$cat&amp;id={$riga_prod['id_prodotto']}&amp;prezzo={$riga_prod['prezzo']}&amp;action=a'\">
                                $desc<br><b>&euro; $prezzo</b>
                            </button>
                          </td>";
                        $riga_prod = mysqli_fetch_array($ris_prod, MYSQLI_ASSOC);
                        if (!is_null($riga_prod))
                            $control = $riga_prod['posizione'];
                    } else {
                        echo "<td class=\"td_bottone\">&nbsp;</td>";
                    }

                    if ($i % $but_x_col == 0 && $i < $but_x_pag) echo "</tr><tr>";
                }
                echo "</tr>";
                ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <div class="footer">
        <button class="bottone_conf"
                onclick="window.location.href='index.php?cat=<?php echo $cat; ?>&action=r'">
            <b>ANNULLA ORDINE</b>
        </button>
        <button class="bottone_conf"
                onclick="window.location.href='stampa.php'">
            <b>STAMPA</b>
        </button>
    </div>

</main>

<aside>
    <header>
        <h2>RIEPILOGO ORDINE</h2>

        <input type="text" name="barcode" id="id_bc" size="20"
               onkeypress="sendBarcode(event)"
               style="font-size:1em; width:100%;"
               autofocus>

        <div class="order-summary-box">
            <?php if ($has_order): ?>
                <?php if ($tag_lettera != ''): ?>
                    <div id="tag"><?php echo $tag_lettera; ?></div>
                <?php endif; ?>
                <div class="order-number">ORDINE #<?php echo $id_ord; ?></div>
            <?php else: ?>
                <div class="order-number">Nessun ordine aperto</div>
            <?php endif; ?>
            <div class="order-total">
                <h3>TOTALE DA PAGARE</h3>
                <h1 class="item-cost">&euro; <?php echo $totale_ord; ?></h1>
            </div>
        </div>

        <?php if ($has_order): ?>
            <div class="aside-actions">
                <button class="aside-btn"
                        onclick="window.location.href='modifica.php'">
                    ✏️ Modifica Ordine
                </button>
                <button class="aside-btn"
                        onclick="window.location.href='options.php'">
                    ⚙️ Opzioni
                </button>
            </div>
        <?php else: ?>
            <div id="tasto_conf">
                <button class="bottone_conf"
                        onclick="codeReset(); mostraDiv('conf_code');">
                    <b>Configurazione</b>
                </button>
                <button class="bottone_conf"
                        onclick="window.location.href='orderStandBy.php'">
                    <b>Ordini in Standby</b>
                </button>
            </div>

            <div id="conf_code" style="visibility:hidden;">
                <center>
                    Inserire il codice:<br>
                    <input type="password" style="font-size:40px;" name="code" size="7" id="codice">
                    <table border="0" width="55%">
                        <tr><td><button class="bottone_conf_mini" onclick="codeAdd('1')">1</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('2')">2</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('3')">3</button></td></tr>
                        <tr><td><button class="bottone_conf_mini" onclick="codeAdd('4')">4</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('5')">5</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('6')">6</button></td></tr>
                        <tr><td><button class="bottone_conf_mini" onclick="codeAdd('7')">7</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('8')">8</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('9')">9</button></td></tr>
                        <tr><td><button class="bottone_conf_mini" onclick="codeAdd('*')">*</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('0')">0</button></td>
                            <td><button class="bottone_conf_mini" onclick="codeAdd('@')">@</button></td></tr>
                    </table><br>
                    <button class="bottone_conf" onclick="send(); nascondiDiv('conf_code');">OK</button>
                    <button class="bottone_conf" onclick="codeReset();">CANC</button><br>
                    <button class="bottone_conf" onclick="codeReset(); nascondiDiv('conf_code');">Chiudi</button>
                </center>
            </div>
        <?php endif; ?>
    </header>

    <section>
        <h3>RIEPILOGO PRODOTTI</h3>
        <?php if ($has_order): ?>
            <ul class="cart-list">
                <?php while ($riga_r = mysqli_fetch_array($ris_riep)): ?>
                    <li>
                        <div class="cart-item">
                            <span class="item-qty"><?php echo $riga_r['quantita']; ?></span>
                            <span class="item-desc"><?php echo htmlentities(strtoupper($riga_r['descrizione_prod'])); ?></span>
                            <span class="item-cost">&euro; <?php echo number_format($riga_r['totale'], 2, ',', '.'); ?></span>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p style="text-align:center; margin-top:1em;">Nessun prodotto</p>
        <?php endif; ?>
    </section>

    <div id="clock"><?php echo date("d/m/Y H:i:s"); ?></div>

</aside>

</body>
</html>