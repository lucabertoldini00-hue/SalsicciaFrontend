<?php

$HTTP_GET_VARS =& $_GET;
$HTTP_POST_VARS =& $_POST;

define("DB0", "ProtCivCrem19");
define("DB1", "CoroValsass19");

$file = fopen($_SERVER['DOCUMENT_ROOT']."/salsiccia/1.0/dbSelect.inc", "r") or die("Unable to open file!");
$dbList = fgets($file);
fclose($file);
$dbList = explode('#', $dbList);

$nomeDB = $dbList[0];

define("MENU_ENABLE", 0);
define("MULTIPLE_DB",0);

define("DEFAULT_CAT", 19);
define("DEBUG", 1);
define("ONLY_ONE_CATEGORY",0);

#Costante per l'abilitazione della grnerazione del report per la cucina
define("REPORT_CUCINA", "0");
#Numero di righe che conterrà ogni foglio di report
define("REPORT_CUCINA_ROW", "10");

#MAX 32 Caratteri

if(MULTIPLE_DB && strpos($nomeDB, "Coro") !== false)
    define("EVENT_NAME",  "Festa Coro Valsassina Ceremeno 2019");
else if (MULTIPLE_DB && strpos($nomeDB, "Civ") !== false)
    define("EVENT_NAME",  "  Protezione Civile Ceremeno 2019  ");
else                                                              #;
    define("EVENT_NAME",  "BAGNASCO - Proloco Casargo 06/08/25");

//define("EVENT_NAME",  "KAPPA Festival il Giappone a Ferrara");
//define("EVENT_NAME",  " La Busecca dell'Asilo - 20/10/2018 ");
//define("EVENT_NAME", "Premana Rivive l'Antico 2018 - 13-14 Ott. Cassa 1");
#MAX 44 Caratteri
define("CREDITS", "T-System SALSICCIA - Datrik Solutions");

#Costante per la stampa di un'etichetta finale con il totale dell'ordine
define("TOTAL_LABEL", "0");
#Se a 1 vengono stampate n etichette per ogni prodotto dove n è la quantità di prodotto ordinato
#Se a 0 viene stampata una sola eichetta che riporta la quantità di prodotto ordinato
define("ONE_LABEL_PER_PRODUCT", "1");

define("CONTINUOUS_LABEL",1);

#Costante per la stampa dell'id ordine su ogni biglietto
define("PRINT_ORDER_ID", "0");
define("LABELS_FILE", "label");
define("COMMAND_FILE", "cmd/cmd");

#Durata Festa in Giorni
define("DURATA_FESTA", "1");
#Ora cambio data
define("ORA_CAMBIO_DATA", "5");

#Max BUT_X_COL 7
define("BOT_X_COL", 4);
#Max BUT_X_ROW 7
if(ONLY_ONE_CATEGORY)
    define("BOT_X_ROW", 7);
else
    define("BOT_X_ROW", 6);
#TD
#define("COL_WIDTH", 120);
#define("COL_HEIGHT",150);
#Bottone
define("BOT_WIDTH", 205);
define("BOT_HEIGHT",120);

define("TIPO_ORDINE", "nor");
define("REMOTE_CLIENT_IP", "192.168.0.30");
$remoteIP = $_SERVER['REMOTE_ADDR'];

if($remoteIP == REMOTE_CLIENT_IP)
    define("ID_CASSA", 100);
else
    define("ID_CASSA", 1);
define("PWD", "@123*");
define("DATA_CONFRONTO", "2019-06-16 06:00:00");



?>
