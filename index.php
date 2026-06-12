<?php require_once 'functionsFrontend.inc'; ?>
<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';
$showModifica = $action == 'm';
$showOpzioni = $action == 'o';
$showConfig = $action == 'c';
$showAdmin = $action == 'c' && isset($_GET['ok']) && isset($_GET['code']) && $_GET['code'] == '@123*';
$showStampa = $action == 's';
$showStandby = $action == 'standby';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php if ($showAdmin): ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <header><h1>PANNELLO ADMIN</h1></header>
            <?php mostraPannelloAdmin(); ?>
        <?php elseif ($showStampa): ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <?php mostraSchermataStampa(); ?>
        <?php elseif ($showModifica): ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <header><h1>MODIFICA ORDINE</h1></header>
            <?php mostraModificaOrdine(); ?>
            <div class="footer"><?php mostraFooterModifica(); ?></div>
        <?php elseif ($showOpzioni): ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <header><h1>TIPOLOGIA ORDINE</h1></header>
            <?php mostraOpzioni(); ?>
            <div class="footer"><?php mostraFooterModifica(); ?></div>
        <?php elseif ($showStandby): ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <header><h1>ORDINI IN STANDBY</h1></header>
            <?php mostraOrdiniStandby(); ?>
        <?php elseif ($showConfig && isset($_GET['ok']) && !$showAdmin): ?>
            <section style="display:flex;flex-direction:column;align-items:center;justify-content:center;flex-grow:1;">
                <p style="font-size:24px;font-weight:800;color:#e54b3c;margin-bottom:30px;">ERRORE CODICE DI AUTORIZZAZIONE ERRATO</p>
            </section>
        <?php else: ?>
            <nav><?php mostraNavCategorie(); ?><?php mostraIndicatoreTipo(); ?></nav>
            <header><h1><?php mostraTitolo(); ?></h1></header>
            <section id="main"><?php mostraTabellaProdotti(); ?></section>
            <div class="footer"><?php mostraFooterBottoni(); ?></div>
        <?php endif; ?>
    </main>
    <aside>
        <header>
            <h2>RIEPILOGO ORDINE</h2>
            <div class="order-summary-box"><?php mostraBoxRiepilogo(); ?></div>
            <div class="aside-actions"><?php mostraAzioniLaterali(); ?></div>
        </header>
        <?php if ($showConfig && !$showAdmin): ?>
            <section style="flex-grow:1;"><?php mostraPannelloConfig(); ?></section>
        <?php else: ?>
            <section>
                <h3>RIEPILOGO PRODOTTI</h3>
                <ul class="cart-list"><?php mostraListaProdotti(); ?></ul>
            </section>
        <?php endif; ?>
        <?php mostraAzioniExtra(); ?>
    </aside>
</body>
</html>
