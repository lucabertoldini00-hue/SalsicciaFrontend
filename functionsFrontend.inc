<?php

require_once 'set.inc';
require_once 'dbConnect.php';
require_once 'funzioni.inc';

$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : DEFAULT_CAT;

$id_cassa = ID_CASSA;

$but_x_row = BOT_X_ROW;
$but_x_col = BOT_X_COL;
$bot_width = BOT_WIDTH;
$bot_height = BOT_HEIGHT;

// Azioni del riepilogo

gestisciAzioni();

function gestisciAzioni()
{
    global $mysqli, $id_cassa, $HTTP_GET_VARS;

    if (isset($HTTP_GET_VARS['action']))
    {
        $action = $HTTP_GET_VARS['action'];
    }
    else
    {
        $action = '';
    }

    if ($action == 'r') // annulla ordine
    {
        $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
        $ris = mysql_query_safe($mysqli, $query);
        if (mysqli_num_rows($ris) == 1)
        {
            $id_ordine = mysqli_fetch_array($ris);
            $id_ordine = $id_ordine['id_ordine'];
            $query = "DELETE FROM ordini WHERE id_ordine = $id_ordine AND id_cassa = $id_cassa AND chiuso = '0'";
            mysql_query_safe($mysqli, $query);
            $query = "DELETE FROM righe_ordini WHERE id_ordine = $id_ordine";
            mysql_query_safe($mysqli, $query);
        }
    }

    if ($action == 'a' && isset($HTTP_GET_VARS['id'])) // aggiungi prodotto
    {
        $id_prodotto = (int)$HTTP_GET_VARS['id'];

        $query = "SELECT * FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
        $ris = mysql_query_safe($mysqli, $query);

        if (mysqli_num_rows($ris) == 1)
        {
            //Ordine esiste recupero id_ordine
            $id_ordine = mysqli_fetch_array($ris);
            $id_ordine = $id_ordine['id_ordine'];

            $query = "SELECT * FROM righe_ordini WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto";
            $ris = mysql_query_safe($mysqli, $query);

            if (mysqli_num_rows($ris) == 1)
            {
                //prodotto già presente incrementa
                $query = "UPDATE righe_ordini SET quantita = quantita + 1 WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto";
                mysql_query_safe($mysqli, $query);
            }
            else
            {
                // Nuovo prodotto inserimento riga
                $query = "SELECT * FROM prodotti WHERE id_prodotto = $id_prodotto";
                $ris = mysql_query_safe($mysqli, $query);
                $prodotto = mysqli_fetch_array($ris);
                $query = "INSERT INTO righe_ordini (id_ordine, id_prodotto, quantita, totale) VALUES ($id_ordine, '$prodotto[id_prodotto]', '1', $prodotto[prezzo])";
                mysql_query_safe($mysqli, $query);
            }
        }
        else
        {
            // Nessun ordine aperto, creo uno nuovo
            $query = "INSERT INTO ordini (data_ora, tipo, totale, n_pezzi, id_cassa, chiuso, num_biglietti) VALUES (NOW(), 'nor', '0', '0', $id_cassa, '0', 0)";
            mysql_query_safe($mysqli, $query);
            $id_ordine = mysqli_insert_id($mysqli);

            $query = "SELECT * FROM prodotti WHERE id_prodotto = $id_prodotto";
            $ris = mysql_query_safe($mysqli, $query);
            $prodotto = mysqli_fetch_array($ris);
            $query = "INSERT INTO righe_ordini (id_ordine, id_prodotto, quantita, totale) VALUES ($id_ordine, '$prodotto[id_prodotto]', '1', $prodotto[prezzo])";
            mysql_query_safe($mysqli, $query);
        }

        // ricalcolo ordine
        calcolaTotali($id_prodotto, $id_ordine, $mysqli);
    }

    if ($action == 'mq' && isset($HTTP_GET_VARS['id']) && isset($HTTP_GET_VARS['qta']))
    {
        $id_prodotto = (int)$HTTP_GET_VARS['id'];
        $qta = (int)$HTTP_GET_VARS['qta'];

        if ($qta > 0)
        {
            $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
            $ris = mysql_query_safe($mysqli, $query);
            if (mysqli_num_rows($ris) == 1)
            {
                $riga = mysqli_fetch_array($ris);
                $id_ordine = $riga['id_ordine'];
                $query = "UPDATE righe_ordini SET quantita = $qta WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto";
                mysql_query_safe($mysqli, $query);
                calcolaTotali($id_prodotto, $id_ordine, $mysqli);
            }
        }

        header("Location: ?action=m");
        exit;
    }

    if ($action == 'mr' && isset($HTTP_GET_VARS['id']))
    {
        $id_prodotto = (int)$HTTP_GET_VARS['id'];

        $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
        $ris = mysql_query_safe($mysqli, $query);
        if (mysqli_num_rows($ris) == 1)
        {
            $riga = mysqli_fetch_array($ris);
            $id_ordine = $riga['id_ordine'];
            $query = "DELETE FROM righe_ordini WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto";
            mysql_query_safe($mysqli, $query);
            calcolaTotali($id_prodotto, $id_ordine, $mysqli);
        }

        header("Location: ?action=m");
        exit;
    }

    if ($action == 'st' && isset($HTTP_GET_VARS['t']))
    {
        $tipo = $HTTP_GET_VARS['t'];
        $validi = array('nor', 'pre', 'mus', 'stf', 'asp');

        if (in_array($tipo, $validi))
        {
            $query = "SELECT id_ordine FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0'";
            $ris = mysql_query_safe($mysqli, $query);

            if (mysqli_num_rows($ris) == 1)
            {
                $riga = mysqli_fetch_array($ris);
                $id_ordine = $riga['id_ordine'];
                $query = "UPDATE ordini SET tipo = '$tipo' WHERE id_ordine = $id_ordine";
                mysql_query_safe($mysqli, $query);
            }
            else
            {
                $query = "INSERT INTO ordini (data_ora, tipo, totale, n_pezzi, id_cassa, chiuso, num_biglietti) VALUES (NOW(), '$tipo', '0', '0', $id_cassa, '0', 0)";
                mysql_query_safe($mysqli, $query);
            }
        }

        header("Location: ?action=o");
        exit;
    }
}

// pulsanti categorie
function mostraNavCategorie()
{
    global $mysqli, $cat;
    $query = "SELECT * FROM categorie ORDER BY id_categoria";
    $ris = mysql_query_safe($mysqli, $query);
    while ($riga = mysqli_fetch_array($ris))
    {
        $attivo = '';

        if ($riga['id_categoria'] == $cat)
        {
            $attivo = 'attivo';
        }
        echo "<button class=\"nav-cat {$attivo}\" onclick=\"location.href='?cat={$riga['id_categoria']}'\">{$riga['descrizione_cat']}</button>";
    }
}

//Categoria corrente
function mostraTitolo()
{
    global $mysqli, $cat;
    $query = "SELECT descrizione_cat FROM categorie WHERE id_categoria = {$cat}";
    $ris = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) > 0)
    {
        $riga = mysqli_fetch_array($ris);
        echo $riga['descrizione_cat'];
    }
    else
    {
        echo 'SAKE';
    }
}

//indicatore tipo ordine nav
function mostraIndicatoreTipo()
{
    global $mysqli;
    $id_cassa = ID_CASSA;

    $query = "SELECT tipo FROM ordini WHERE id_cassa = $id_cassa AND chiuso = '0' LIMIT 1";
    $ris = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) > 0)
    {
        $riga = mysqli_fetch_array($ris);
        $tipo = $riga['tipo'];

        $lettere = array('pre' => 'P', 'mus' => 'M', 'stf' => 'S', 'asp' => 'A');

        if (isset($lettere[$tipo]))
            echo '<span style="color:#e54b3c;font-weight:800;font-size:18px;margin-left:auto;">' . $lettere[$tipo] . '</span>';
    }
}

// tabella prodotti
function mostraTabellaProdotti()
{
    global $mysqli, $cat, $but_x_row, $but_x_col;

    $but_x_pag = $but_x_col * $but_x_row;

    $query = "SELECT * FROM prodotti, categorie, prodotti_categorie WHERE
              prodotti_categorie.id_prodotto = prodotti.id_prodotto AND
              prodotti_categorie.id_categoria = categorie.id_categoria AND
              categorie.id_categoria = {$cat} ORDER BY posizione";
    $ris = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) == 0)
    {
        $q = "SELECT * FROM categorie WHERE id_categoria = {$cat}";
        $r = mysql_query_safe($mysqli, $q);
        $riga = mysqli_fetch_array($r);
        $colore = $riga ? $riga['colore'] : '';
        echo "<div style=\"background-image: url(colori_sfondo/{$colore}.png); height: 1024px;\">";
        echo "<center><h1>NESSUN PRODOTTO IN QUESTA CATEGORIA</h1></center></div>";
        return;
    }

    $riga = mysqli_fetch_array($ris);
    $colore = $riga['colore'];

    /*
    if (!ONLY_ONE_CATEGORY)
    {
        echo "<div id=\"titolo_cat\" style=\"text-align:center;margin-bottom:10px;\">{$riga['descrizione_cat']}</div>";
    }
    */

    echo '<table><tbody><tr>';

    // griglia bottoni
    $control = $riga['posizione'];
    for ($i = 1; $i <= $but_x_pag; $i++)
    {
        if ($control == $i)
        {
            $prezzo = number_format($riga['prezzo'], 2, ',', '.');
            $desc = strtoupper($riga['descrizione_prod']);
            $id_prodotto = $riga['id_prodotto'];
            echo "<td class=\"td_bottone\">
                    <button class=\"bottone\" onclick=\"location.href='?cat={$cat}&action=a&id={$id_prodotto}'\">
                      {$desc}<br><b>&euro; {$prezzo}</b>
                    </button>
                  </td>";
            $riga = mysqli_fetch_array($ris, MYSQLI_ASSOC);
            if ($riga)
                $control = $riga['posizione'];
        }
        else
        {
            echo '<td class="td_bottone">&nbsp;</td>';
        }
        // Vai a capo dopo but_x_col colonne
        if ($i % $but_x_col == 0 && $i < $but_x_pag)
            echo "</tr><tr>";
    }
    echo '</tr></tbody></table>';
}

//Modifica ordine
function mostraModificaOrdine()
{
    global $mysqli;
    $id_cassa = ID_CASSA;

    $query = "SELECT prodotti.id_prodotto AS id, prodotti.descrizione_prod, righe_ordini.quantita, righe_ordini.totale, ordini.totale as tot_ord, ordini.id_ordine AS id_ord FROM prodotti, ordini, righe_ordini WHERE ordini.id_ordine = righe_ordini.id_ordine AND prodotti.id_prodotto = righe_ordini.id_prodotto AND id_cassa = $id_cassa AND ordini.chiuso = 0";
    $ris = mysql_query_safe($mysqli, $query);

    echo '<section class="modifica-section">';

    if (mysqli_num_rows($ris) > 0)
    {
        echo '<table class="modifica-table">';
        echo '<thead><tr>';
        echo '<th class="intestazione-tabella-descrizione">DESCRIZIONE</th>';
        echo '<th class="intestazione-tabella-quantita">Q.TÀ</th>';
        echo '<th class="intestazione-tabella-prezzo">COSTO UNITARIO</th>';
        echo '<th class="intestazione-tabella-azioni">AZIONI</th>';
        echo '</tr></thead>';
        echo '<tbody>';

        while ($riga = mysqli_fetch_array($ris))
        {
            $desc = strtoupper($riga['descrizione_prod']);
            $prezzo_unitario = $riga['quantita'] > 0 ? number_format($riga['totale'] / $riga['quantita'], 2, ',', '.') : '0,00';
            $qta = $riga['quantita'];

            echo '<tr>';
            echo "<td class=\"cella-tabella-descrizione\">{$desc}</td>";
            echo '<td class="cella-tabella-quantita">';
            echo '<div class="contenitore-qta">';
            if ($qta > 1)
                echo "<a href=\"?action=mq&id={$riga['id']}&qta=" . ($qta - 1) . "\" class=\"bottone-qta bottone-qta-meno\" style=\"text-decoration:none\">−</a>";
            else
                echo "<span class=\"bottone-qta bottone-qta-meno\" style=\"opacity:0.3;cursor:default\">−</span>";
            echo "<span class=\"input-qta\" style=\"display:inline-flex;align-items:center;justify-content:center\">{$qta}</span>";
            echo "<a href=\"?action=mq&id={$riga['id']}&qta=" . ($qta + 1) . "\" class=\"bottone-qta bottone-qta-piu\" style=\"text-decoration:none\">+</a>";
            echo '</div>';
            echo '</td>';
            echo "<td class=\"cella-tabella-prezzo\">€ {$prezzo_unitario}</td>";
            echo '<td class="cella-tabella-azioni">';
            echo "<a href=\"?action=m\" class=\"action-btn action-check\" style=\"text-decoration:none\">✓</a>";
            echo "<a href=\"?action=mr&id={$riga['id']}\" class=\"action-btn action-delete\" style=\"text-decoration:none\">✕</a>";
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
    else
    {
        echo '<p style="text-align:center;margin-top:40px;font-size:18px;color:#8c9ba5;">Nessun prodotto nell\'ordine</p>';
    }

    echo '</section>';
}

// Footer modifica
function mostraFooterModifica()
{
    global $cat;
    echo '<a href="?cat=' . $cat . '&action=r" id="annulla-btn" style="text-decoration:none"><span id="annullaOrdine-sign">🗑️️ ANNULLA ORDINE</span></a>
          <a href="index.php" id="continua-btn" style="text-decoration:none"><span id="continua-sign">🛒 CONTINUA ORDINE</span></a>
          <a href="stampa.php" id="stampa-btn" style="text-decoration:none"><span id="stampa-sign">🖨️ STAMPA</span></a>';
}

// Schermata opzioni
function mostraOpzioni()
{
    echo '<section class="opzioni-section">';
    echo '<div class="opzioni-row-1">';
    echo '<a href="?action=st&t=pre" class="opzione-btn" style="text-decoration:none">PREVENDITA</a>';
    echo '<a href="?action=st&t=mus" class="opzione-btn" style="text-decoration:none">MUSICISTI</a>';
    echo '<a href="?action=st&t=stf" class="opzione-btn" style="text-decoration:none">STAFF</a>';
    echo '<a href="?action=st&t=asp" class="opzione-btn" style="text-decoration:none">ASPORTO</a>';
    echo '</div>';
    echo '<div class="opzioni-row-2">';
    echo '<a href="?action=st&t=nor" class="opzione-btn opzione-btn-large" style="text-decoration:none">NORMALE</a>';
    echo '<span class="opzione-btn opzione-btn-large" style="opacity:0.5;cursor:default">STAND BY</span>';
    echo '</div>';
    echo '</section>';
}

// Pulsanti annulla ordine e stampa
function mostraFooterBottoni()
{
    global $cat;
    echo '<button id="annulla-btn" onclick="location.href=\'?cat=' . $cat . '&action=r\'"><span id="annullaOrdine-sign">🗑️️ ANNULLA ORDINE</span></button>
          <button id="stampa-btn" onclick="location.href=\'stampa.php\'"><span id="stampa-sign">🖨️ STAMPA</span></button>';
}

//R iepilogo ordine
function mostraBoxRiepilogo()
{
    global $mysqli;
    $id_cassa = ID_CASSA;

    $query = "SELECT id_ordine, totale FROM ordini WHERE id_cassa = {$id_cassa} AND chiuso = '0'";
    $ris = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) > 0)
    {
        $riga = mysqli_fetch_array($ris);
        $totale = number_format($riga['totale'], 2, ',', '.');
        echo "<div class=\"order-number\">ORDINE #{$riga['id_ordine']}</div>
              <div class=\"order-total\">
                <h3>TOTALE DA PAGARE</h3>
                <h1 class=\"item-cost\">&euro; {$totale}</h1>
              </div>";
    }
    else
    {
        echo '<div class="order-number">NESSUN ORDINE APERTO</div>
              <div class="order-total">
                <h3>TOTALE DA PAGARE</h3>
                <h1 class="item-cost">&euro; 0,00</h1>
              </div>';
    }
}

//Pulsanti modifica e opzioni
function mostraAzioniLaterali()
{
    echo '<a href="?action=m" class="aside-btn" style="text-decoration:none">🖊️ Modifica Ordine</a>
          <a href="?action=o" class="aside-btn" style="text-decoration:none">⚙️ Opzioni</a>';
}

// elenco prodotti ordine
function mostraListaProdotti()
{
    global $mysqli;
    $id_cassa = ID_CASSA;

    $query = "SELECT prodotti.descrizione_prod, righe_ordini.quantita, righe_ordini.totale
              FROM prodotti, ordini, righe_ordini
              WHERE ordini.id_ordine = righe_ordini.id_ordine
                AND prodotti.id_prodotto = righe_ordini.id_prodotto
                AND id_cassa = {$id_cassa}
                AND ordini.chiuso = '0'";
    $ris = mysql_query_safe($mysqli, $query);

    if (mysqli_num_rows($ris) > 0)
    {
        while ($riga = mysqli_fetch_array($ris))
        {
            $prezzo = number_format($riga['totale'], 2, ',', '.');
            $desc = strtoupper($riga['descrizione_prod']);
            echo "<li>
                    <div class=\"cart-item\">
                        <span class=\"item-qty\">{$riga['quantita']}</span>
                        <span class=\"item-desc\">{$desc}</span>
                        <span class=\"item-cost\">&euro; {$prezzo}</span>
                    </div>
                  </li>";
        }
    }
    else
    {
        echo '<li><div class="cart-item"><span class="item-desc">Nessun prodotto nell\'ordine</span></div></li>';
    }
}

//pulsanti fondo aside
function mostraAzioniExtra()
{
    echo '<div style="margin-top:auto;display:flex;gap:12px;padding-top:20px;">';
    echo '<a href="?action=c" class="aside-btn" style="text-decoration:none">🔧 Configurazione</a>';
    echo '<a href="?action=standby" class="aside-btn" style="text-decoration:none">⏸️ Ordini in standby</a>';
    echo '</div>';
}

// Pannello configurazione con tastierino
function mostraPannelloConfig()
{
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    $encodedCode = urlencode($code);

    echo '<h3 style="font-size:13px;color:#5a6b7c;margin-bottom:12px;font-weight:800;">INSERISCI IL CODICE:</h3>';
    echo '<input type="password" class="codice-text-field" value="' . htmlspecialchars($code) . '" readonly>';

    echo '<div class="tastierino-grid">';
    for ($i = 1; $i <= 9; $i++)
        echo '<a href="?action=c&code=' . $encodedCode . $i . '" class="tastierino-btn" style="text-decoration:none">' . $i . '</a>';
    echo '<a href="?action=c&code=' . $encodedCode . '*" class="tastierino-btn" style="text-decoration:none">*</a>';
    echo '<a href="?action=c&code=' . $encodedCode . '0" class="tastierino-btn" style="text-decoration:none">0</a>';
    echo '<a href="?action=c&code=' . $encodedCode . '@" class="tastierino-btn" style="text-decoration:none">@</a>';
    echo '</div>';

    echo '<div class="tastierino-actions">';
    echo '<a href="?action=c&ok=1&code=' . $encodedCode . '" class="tastierino-action-btn action-ok" style="text-decoration:none;display:flex;align-items:center;justify-content:center">OK</a>';
    echo '<a href="?action=c" class="tastierino-action-btn action-canc" style="text-decoration:none;display:flex;align-items:center;justify-content:center">CANC</a>';
    echo '</div>';

    echo '<a href="index.php" class="tastierino-close-btn" style="text-decoration:none;display:flex;align-items:center;justify-content:center">CHIUDI</a>';
}
