<?php require_once 'functionsFrontend.inc'; ?>

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
        <nav><?php mostraNavCategorie(); ?></nav>
        <header><h1><?php mostraTitolo(); ?></h1></header>
        <section id="main"><?php mostraTabellaProdotti(); ?></section>
        <div class="footer"><?php mostraFooterBottoni(); ?></div>
    </main>
    <aside>
        <header>
            <h2>RIEPILOGO ORDINE</h2>
            <div class="order-summary-box"><?php mostraBoxRiepilogo(); ?></div>
            <div class="aside-actions"><?php mostraAzioniLaterali(); ?></div>
        </header>
        <section>
            <h3>RIEPILOGO PRODOTTI</h3>
            <ul class="cart-list"><?php mostraListaProdotti(); ?></ul>
        </section>
    </aside>
</body>
</html>
