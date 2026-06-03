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
}

// === NAV: pulsanti categorie ===
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

// pulsanti modifica e opzioni
function mostraAzioniLaterali()
{
    echo '<button class="aside-btn" onclick="parent.centro.location.href=\'modifica.php\'">🖊️ Modifica Ordine</button>
          <button class="aside-btn" onclick="parent.centro.location.href=\'options.php\'">⚙️ Opzioni</button>';
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
