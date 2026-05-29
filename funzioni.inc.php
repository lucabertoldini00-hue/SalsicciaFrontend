<?php
//define("DEBUG", 1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


function mysql_query_safe($mysqli, $query)
{
    if(DEBUG)
    {
        $risultato = mysqli_query($mysqli, $query)
        or die(print "<br><center class=errore>Errore di MySql con la query <br><b>$query</b><br>".mysql_error()."<br><br><INPUT type=\"BUTTON\" value='INDIETRO' onClick=\"javascript:history.back(1);\"></center>");
    }
    else
    {
        $risultato = mysqli_query($mysqli, $query);
    }

    return $risultato;
}

function printA($str)
{
    $str = str_replace("'", "\'", $str);
    print "\n<script type=\"text/javascript\">alert('$str');</script>\n";
}

function calcolaTotali($id_prodotto, $id_ordine, $mysqli)
{
    if(isSet($id_prodotto))
    {
        #DOPO AVER INSERITO O AGGIUNTO UN PRODOTTO DEVO AGGIORNARE QUANTITA E TOTALE IN righe_ordini
        $query = "SELECT quantita, prezzo FROM prodotti,righe_ordini WHERE prodotti.id_prodotto = righe_ordini.id_prodotto AND 
        id_ordine = $id_ordine AND prodotti.id_prodotto = $id_prodotto";
        //print "query: $query";
        $ris = mysql_query_safe($mysqli, $query);
        //$riga = mysql_fetch_array($ris);
        if(mysqli_num_rows($ris) > 0)
        {
            $riga = mysqli_fetch_array($ris);
            $quantita = $riga['quantita'];
            $prezzo = $riga['prezzo'];
            $totale = $quantita * $prezzo;
            //print "\n<script>alert('n_pezzi: $quantita - totale:$totale');</script>";
            $query = "UPDATE righe_ordini set quantita = $quantita, totale = $totale WHERE id_ordine = $id_ordine AND id_prodotto = $id_prodotto";
            //print "query: $query";
            $ris = mysql_query_safe($mysqli, $query);
        }
        #DOPO AVER INSERITO O AGGIUNTO UN PRODOTTO DEVO AGGIORNARE n_pezzi e totale IN ordini
        $query = "SELECT sum(quantita) as n_pezzi, sum(totale) as totale FROM righe_ordini WHERE id_ordine = $id_ordine GROUP BY id_ordine;";
        $ris = mysql_query_safe($mysqli, $query);
        $riga = mysqli_fetch_array($ris);
        $n_pezzi = $riga['n_pezzi'];
        $totale = $riga['totale'];

        if($totale == 0)
            $query = "UPDATE ordini set n_pezzi = 0, totale = 0 WHERE id_ordine = $id_ordine";
        //print "query: $query";
        else
            $query = "UPDATE ordini set n_pezzi = $n_pezzi, totale = $totale WHERE id_ordine = $id_ordine";
        //print "query: $query";
        $ris = mysql_query_safe($mysqli, $query);
    }

}

function testo_biglietti($stringa)
{
    #Converto i caratteri accentati in lattare + carattere '
    $stringa = str_replace("è","e'",$stringa);

    #Divido la stringa del testo da stampare sul bigliestto nelle parole che la
    #compongono e le tengo in un array dove metto ogni singola parola è un elemento
    $array = explode(" ", $stringa);
    $n = count($array);
    #Variabili che conterrano il testo del diglietto, una variabile per riga
    $str1 = "";
    $str2 = "";
    #Se il testo è composto da ...
    switch($n)
    {
        #...una parola lo assegno alla stringa della prima riga
        case 1:
            $str1 = $array[0];
            break;
        #...due parole e la prima è minore di 12 caratteri, assegno la prima parola alla rpima riga e la seconda alla seconda riga
        case 2:
            if(strlen($array[0]) < 12)
            {
                $str1 = $array[0];
                $str2 = $array[1];
            }
            break;
        case 3:
            if(strlen($array[0]) + strlen($array[1]) < 12)
            {
                $str1 = $array[0] ." ". $array[1];
                $str2 = $array[2];
            }
            else
            {
                $str1 = $array[0];
                $str2 = $array[1] ." ". $array[2];
            }
            break;
        case 4:
            if(strlen($array[0]) + strlen($array[1]) + strlen($array[2]) < 12)
            {
                $str1 = $array[0] ." ". $array[1] ." ". $array[2];
                $str2 = $array[3];
            }
            else
            {
                if(strlen($array[0]) + strlen($array[1]) < 12)
                {
                    $str1 = $array[0] ." ". $array[1];
                    $str2 = $array[2] ." ". $array[3];
                }
                else
                {
                    $str1 = $array[0];
                    $str2 = $array[1] ." ". $array[2] ." ". $array[3];
                }
            }
        case 5:
            if(strlen($array[0]) + strlen($array[1]) + strlen($array[2]) + strlen($array[3]) < 12)
            {
                $str1 = $array[0] ." ". $array[1] ." ". $array[2] ." ". $array[3];
                $str2 = $array[4];
            }
            else
            {
                if(strlen($array[0]) + strlen($array[1]) + strlen($array[2]) < 12)
                {
                    $str1 = $array[0] ." ". $array[1] ." ". $array[2];
                    @$str2 = $array[3] ." ". $array[4];
                }
                else
                {
                    if(strlen($array[0]) + strlen($array[1]) < 12)
                    {
                        $str1 = $array[0] ." ". $array[1];
                        @$str2 = $array[2] ." ". $array[3] ." ". $array[4];
                    }
                    else
                    {
                        $str1 = $array[0];
                        @$str2 = $array[1] ." ". $array[2] ." ". $array[3] ." ". $array[4];
                    }
                }
            }

            break;
    }
    #print "Str 1: $str1<br>\n";
    #print "Str 2: $str2<br>\n";

    #Converto il testo in maisucolo perchè il font 5 della stampante stampa solo lettere maiuscole
    $str1 = strtoupper($str1);
    $str2 = strtoupper($str2);

    $str[0] = $str1;
    $str[1] = $str2;

    //print "Biglietto:". $str1.$str2;

    return $str;
}
function stampaMenuOld($testo,$quantita,$tipo_stampa,$parametri, $id=0)
# DA CORREGGERE - VARIATI I PARAMETRI
{

    /*
            tipo stampa, se è 1 allora sono 3 menù separati
            se è 0 allora stampa i menù assieme
                        0										                1
          polenta, salsiccia, acqua				polenta, polenta, polenta
            polenta, salsiccia, acqua				salsiccia, salsiccia, salsiccia
            polenta, salsiccia, acqua				acqua, acqua, acqua
    */

    $label = "";
    $num_pezzi =0;
    if($tipo_stampa==0)
    {
        $menu[0]=$testo[0];
        $menu[1]=$testo[1];
        $menu[2]="CONTORNO";
        //$menu[3]="PANE";
        $menu[3]="BIRRA";
        $menu[4]="BIBITA 0.30";
        $numero_prodotti_menu = 3;
        //print("	<script language=\"javascript\">alert(\"1 num_pezzi = $num_pezzi\")</script>");
        for($i=0;$i<$quantita;$i++)
        {
            if($testo[1]=="VENERDI")
            {
                //$label= "" ;
                $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
                #Stampa di un'etichetta per ogni singola quantità di un prodotto
                #Nome prodotto su 2 righe
                $label .= "A440,195,2,5,1,1,N,\"BIRRA\"\nA440,135,2,5,1,1,N,\"\"\n";
                #Numero cassa data e ora biglietto
                $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
                #Credits
                $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
                #Quantità
                $label .= "P1\n";
                $num_pezzi ++;
                $menu[0]="PANINO";
                $menu[1]="IMBOTTITO";

            }
            else
            {
                #Stampa Bevanda del Menu
                if($testo[1]=="BABY")
                {
                    //$label= "" ;
                    $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
                    #Stampa di un'etichetta per ogni singola quantità di un prodotto
                    #Nome prodotto su 2 righe
                    $label .= "A440,195,2,5,1,1,N,\"$menu[4]\"\nA440,135,2,5,1,1,N,\"\"\n";
                    #Numero cassa data e ora biglietto
                    $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
                    #Credits
                    $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
                    #Quantità
                    $label .= "P1\n";
                    $num_pezzi ++;
                }
                else
                {
                    $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
                    #Stampa di un'etichetta per ogni singola quantità di un prodotto
                    #Nome prodotto su 2 righe
                    $label .= "A440,195,2,5,1,1,N,\"$menu[3]\"\nA440,135,2,5,1,1,N,\"\"\n";
                    #Numero cassa data e ora biglietto
                    $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
                    #Credits
                    $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
                    #Quantità
                    $label .= "P1\n";
                    $num_pezzi ++;
                }
                #Stampa CONTORNO del Menu
                if($testo[1]=="BABY" && $menu[2]=="CONTORNO")
                {
                    $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
                    #Stampa di un'etichetta per ogni singola quantità di un prodotto
                    #Nome prodotto su 2 righe
                    $label .= "A440,195,2,5,1,1,N,\"PATATINE\"\nA440,135,2,5,1,1,N,\"\"\n";
                    #Numero cassa data e ora biglietto
                    $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
                    #Credits
                    $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
                    #Quantità
                    $label .= "P1\n";
                    $num_pezzi ++;
                }
                else
                {
                    $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
                    #Stampa di un'etichetta per ogni singola quantità di un prodotto
                    #Nome prodotto su 2 righe
                    $label .= "A440,195,2,5,1,1,N,\"$menu[2]\"\nA440,135,2,5,1,1,N,\"\"\n";
                    #Numero cassa data e ora biglietto
                    $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
                    #Credits
                    $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
                    #Quantità
                    $label .= "P1\n";
                    $num_pezzi ++;
                }
            }
            #Stampa NOME DEL MENU del Menu
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 2 righe
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            $label .= "A440,195,2,5,1,1,N,\"$menu[0]\"\nA440,135,2,5,1,1,N,\"$menu[1]\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;
        }
        $vect["num_pezzi"] = $num_pezzi;
        $vect["label"] = $label;
        return $vect;
    }
}
//stampaMenu($testo,$quantita,0,$parametri);
function stampaMenu($testo,$quantita,$tipo_stampa,$parametri, $id=0)
{
    /*
            tipo stampa, se è 1 allora sono 3 menù separati
            se è 0 allora stampa i menù assieme
                        0										                1
          polenta, salsiccia, acqua				polenta, polenta, polenta
            polenta, salsiccia, acqua				salsiccia, salsiccia, salsiccia
            polenta, salsiccia, acqua				acqua, acqua, acqua
    */

//print_r($testo);

    if(in_array("ACQUA", $testo))
        $bevanda = "ACQUA";
    else if(in_array("BUONO BIRRA", $testo))
        $bevanda = "BIRRA";
    else if(in_array("VINO", $testo))
        $bevanda = "VINO";
    else
        $bevanda = "BIBITA";

    $label = "";
    $num_pezzi =0;
    if($tipo_stampa==0)
    {
        $menu[0]="TARAGNA E";
        $menu[1]="SALSICCIA";
        $menu[2]="MIX FORMAGGI";
        $menu[3]="PATATINE";
        $menu[4]=$bevanda;
        $menu[5]="DOLCE";
        $menu[6]="CAFFE";
        $numero_prodotti_menu = 3;
        //print("	<script language=\"javascript\">alert(\"1 num_pezzi = $num_pezzi\")</script>");
        for($i=0;$i<$quantita;$i++)
        {
            #Prodotto 1
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 1 riga
            $label .= "A440,195,2,5,1,1,N,\"$menu[6]\"\nA440,135,2,5,1,1,N,\"\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;

            #Prodotto 2
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 1 riga
            $label .= "A440,195,2,5,1,1,N,\"$menu[5]\"\nA440,135,2,5,1,1,N,\"\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;

            #Prodotto 3
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 1 riga
            $label .= "A440,195,2,5,1,1,N,\"$menu[4]\"\nA440,135,2,5,1,1,N,\"\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;

            #Prodotto 4
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 1 riga
            $label .= "A440,195,2,5,1,1,N,\"$menu[3]\"\nA440,135,2,5,1,1,N,\"\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;

            #Prodotto 5
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 1 riga
            $label .= "A440,195,2,5,1,1,N,\"$menu[2]\"\nA440,135,2,5,1,1,N,\"\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;

            #Prodotto 6
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 2 righe
            $label .= "A440,195,2,5,1,1,N,\"$menu[0]\"\nA440,135,2,5,1,1,N,\"$menu[1]\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";
            $num_pezzi ++;


        }
        /*#Stampa NOME DEL MENU del Menu
        #Stampa di un'etichetta per ogni singola quantità di un prodotto
            #Nome prodotto su 2 righe
            $label .= "N\nA438,230,2,2,1,1,R,\"$parametri[evento]\"\n";
            $label .= "A440,195,2,5,1,1,N,\"$menu[0]\"\nA440,135,2,5,1,1,N,\"$menu[1]\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A380,65,2,2,1,1,N,\"Cassa$parametri[cassa] - $parametri[now] $parametri[t]\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$parametri[credits]\"\n";
            #Quantità
            $label .= "P1\n";*/
    }
    $vect["num_pezzi"] = $num_pezzi;
    $vect["label"] = $label;
    return $vect;
}

function leggiFile($nomeFile)
{
    $pf = fopen($nomeFile, "r")
    or die("Impossibile trovare il file $nomefile !!! ");
    $testo = "";
    $riga = fgets($pf);
    $testo = $testo.$riga;
    fclose($pf);
    return $testo;
}

function printOre()
{
    print "<table border=\"0\" width=\"100%\">\n
          <tr>\n
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('00:00');\" type=\"button\">00:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('01:00');\" type=\"button\">01:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('02:00');\" type=\"button\">02:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('03:00');\" type=\"button\">03:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('04:00');\" type=\"button\">04:00</button></td></tr>
          <tr>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('05:00');\" type=\"button\">05:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('06:00');\" type=\"button\">06:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('07:00');\" type=\"button\">07:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('08:00');\" type=\"button\">08:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('09:00');\" type=\"button\">09:00</button></td></tr>
          <tr>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('10:00');\" type=\"button\">10:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('11:00');\" type=\"button\">11:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('12:00');\" type=\"button\">12:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('13:00');\" type=\"button\">13:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('14:00');\" type=\"button\">14:00</button></td></tr>
          <tr>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('15:00');\" type=\"button\">15:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('16:00');\" type=\"button\">16:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('17:00');\" type=\"button\">17:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('18:00');\" type=\"button\">18:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('19:00');\" type=\"button\">19:00</button></td></tr>
          <tr>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('20:00');\" type=\"button\">20:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('21:00');\" type=\"button\">21:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('22:00');\" type=\"button\">22:00</button></td>
          <td class=\"td_bottone\"><button class=\"bottone\" style=\"width: 80px; height: 60px;\" onclick=\"setOra('23:00');\" type=\"button\">23:00</button></td>
          <td class=\"td_bottone\">&nbsp;</td>
         </tr></table>";
}

function print_tabGraf_ordini($pdf, $d_i, $d_f, $header, $w, $i, $mysqli)
{
    if($pdf->PageNo() > 1)
        $pdf->AddPage();
    #Creazione oggeto grafico
    $chart = new PieChart(840, 500);
    /*if($i == 0)
        $query = "SELECT * FROM categorie WHERE id_categoria <> 18;";
    else if ($i == 1)
        $query = "SELECT * FROM categorie WHERE id_categoria <> 17;";  */
    $query = "SELECT * FROM categorie";
    /*$query = "SELECT * FROM categorie WHERE id_categoria = 14 ";
    if($i == 0)
        $query .= "OR id_categoria = 16;";
    if($i == 1)
        $query .= "OR id_categoria = 17;";
    if($i == 2)
        $query .= "OR id_categoria = 18;";
    //print "Query: $query<br>"; */

    $rs = mysql_query_safe($mysqli, $query);
    $grapg_index = $i;
    while($row = mysql_fetch_array($rs))
    {
        $dataSet = new XYDataSet();
        $id_cat = $row['id_categoria'];
        $categoria = $row['descrizione_cat'];
        $g = $i+1;

        $pdf->SetFont('Arial','BI',16);
        $pdf->Cell(190,20,"Riepilogo prodotti venduti, categoria: $categoria, giorno$g",0,0,"L");
        $pdf->Ln(15);
        #Creazione Intestazione tabella prodotti
        $pdf->SetFont('Arial','BI',16);
        for($j=0;$j<count($header);$j++)
            $pdf->Cell($w[$j],7,$header[$j],1,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','',16);

        //AND ordini.tipo = 'stf'
        #Query per la popolazione della tabella e per la generazione del grafico
        $query = "SELECT prodotti.descrizione_prod AS prodotto, sum( righe_ordini.totale ) AS tot , sum( quantita ) AS quantita, id_categoria
        FROM ordini, righe_ordini, prodotti, prodotti_categorie WHERE ordini.id_ordine = righe_ordini.id_ordine
        AND prodotti.id_prodotto = righe_ordini.id_prodotto AND prodotti_categorie.id_prodotto = prodotti.id_prodotto 
        AND ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND prodotti_categorie.id_categoria = $id_cat AND tipo = '".TIPO_ORDINE."' 
        GROUP BY prodotti.id_prodotto ORDER BY quantita";
        //print "$query";
        $ris = mysql_query_safe($mysqli, $query);

        #Popolamento tabella e generazione grafico
        while($riga = mysql_fetch_array($ris))
        {
            $totale = number_format($riga['tot'], 2, ',', '.');
            $pdf->Cell(70,6,$riga['prodotto'],1,0);
            $pdf->Cell(60,6,$riga['quantita'],1,0,"C");
            $pdf->Cell(60,6,$totale,1,0,"R");
            $pdf->Ln();
            $dataSet->addPoint(new Point($riga['prodotto']." - ".$riga['quantita'], $riga['quantita']));
        }
        #Query per la generazione dei totali della tabella
        /*$query_tot = "SELECT sum(ordini.totale) AS tot_gen, sum(ordini.n_pezzi) AS tot_pezzi FROM ordini
        WHERE ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND ";*/

        //AND ordini.tipo = 'stf'
        $query_tot = "SELECT sum(quantita) AS tot_pezzi, sum(righe_ordini.totale) AS tot_gen FROM ordini, righe_ordini, prodotti_categorie 
        WHERE ordini.id_ordine = righe_ordini.id_ordine AND ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND 
        righe_ordini.id_prodotto = prodotti_categorie.id_prodotto and id_categoria = $id_cat AND tipo = '".TIPO_ORDINE."'" ;
        //print "$query_tot";
        $ris_tot = mysql_query_safe($mysqli, $query_tot);
        $tot_pezzi = mysql_result($ris_tot, 0, "tot_pezzi");
        $tot_gen = mysql_result($ris_tot, 0, "tot_gen");
        $tot_gen = number_format($tot_gen, 2, ',', '.');
        $pdf->SetFont('Arial','B',16);
        #Aggiunta dei totali alla tabella
        $pdf->Cell(70,6,"TOTALI",1,0);
        $pdf->Cell(60,6,$tot_pezzi,1,0,"C");
        $pdf->Cell(60,6,$tot_gen,1,0,"R");

        $pdf->Ln();

        #Creazione  del grafico

        //print_r($dataSet);

        $chart->setDataSet($dataSet);
        $chart->setTitle("");
        $chart->render("grafici/torta_$id_cat-$grapg_index.png");

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $y += 1;

        $pdf->Image("grafici/torta_$id_cat-$grapg_index.png",5,$y,200);
        //$pdf->Ln(130);
        $grapg_index ++;
        $pdf->AddPage();
    }
}

function print_tabGraf_affluenza($pdf, $d_i, $d_f, $intest, $w_col, $i, $mysqli)
{
    //$pdf->AddPage();
    $chart_bar = new VerticalBarChart(840, 500);
    $dataSet_bar = new XYDataSet();

    //AND ordini.tipo = 'stf'
    $query_stat = "SELECT date_format(data_ora,\"%H\") as ora , sum(totale) as tot, sum(num_biglietti) as n_pezzi, count(id_ordine) as tot_ord
    from ordini WHERE ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND tipo = '".TIPO_ORDINE."' 
    group by ora order by data_ora";

    //print "$query_stat";
    $g = $i+1;
    $pdf->SetFont('Arial','BI',18);
    $pdf->Cell(190,20,"Statistiche di affluenza, giorno $g",0,0,"L");

    $ris_stat = mysql_query_safe($mysqli, $query_stat);

    $pdf->SetFont('Arial','BI',16);
    $pdf->Ln();
    for($j=0;$j<count($intest);$j++)
        $pdf->Cell($w_col[$j],7,$intest[$j],1,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','',16);

    while($riga = mysql_fetch_array($ris_stat))
    {
        $totale = number_format($riga['tot'], 2, ',', '.');
        $m_b_o = $riga['n_pezzi'] / $riga['tot_ord'];
        $m_b_o = number_format($m_b_o, 3, ',', '.');
        $pdf->Cell(25,6,$riga['ora'].":00",1,0, "C");
        $pdf->Cell(35,6,$riga['tot_ord'],1,0,"C");
        $pdf->Cell(40,6,$riga['n_pezzi'],1,0,"C");
        $pdf->Cell(40,6,$totale,1,0,"R");
        $pdf->Cell(50,6,$m_b_o,1,0,"C");
        $pdf->Ln();
        $dataSet_bar->addPoint(new Point($riga['ora'].":00", $riga['tot_ord']));
    }
    //AND ordini.tipo = 'stf'
    $query_tot_aff = "SELECT sum(ordini.totale) AS tot_gen, sum(num_biglietti) AS tot_pezzi, count(ordini.id_ordine) AS n_ordini FROM ordini 
    WHERE ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND tipo = '".TIPO_ORDINE."'";
    //print "$query_tot_aff";
    $ris_tot = mysql_query_safe($mysqli, $query_tot_aff);
    $tot_pezzi = mysql_result($ris_tot, 0, "tot_pezzi");
    $tot_gen = mysql_result($ris_tot, 0, "tot_gen");
    $tot_gen = number_format($tot_gen, 2, ',', '.');
    $tot_ordini = mysql_result($ris_tot, 0, "n_ordini");

    $m_b_o = $tot_pezzi / $tot_ordini;
    $m_b_o = number_format($m_b_o, 3, ',', '.');

    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(25,6,"TOTALI",1,0);
    $pdf->Cell(35,6,$tot_ordini,1,0,"C");
    $pdf->Cell(40,6,$tot_pezzi,1,0,"C");
    $pdf->Cell(40,6,$tot_gen,1,0,"R");
    $pdf->Cell(50,6,"$m_b_o",1,0,"C");

    $pdf->Ln();

    $chart_bar->setDataSet($dataSet_bar);
    $chart_bar->setTitle("");
    $chart_bar->render("grafici/barre_$i.png");

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $y += 5;

    $pdf->Image("grafici/barre_$i.png",5,$y,200);
    $pdf->Ln(130);
}

function print_tabGraf_totali($pdf, $d_i, $d_f)
{
    #Creazione Intestazione tabella prodotti
    /*$pdf->SetFont('Arial','BI',16);
    for($j=0;$j<count($header);$j++)
		    $pdf->Cell($w[$j],7,$header[$j],1,0,'C');
	  $pdf->Ln();
    $pdf->SetFont('Arial','',16);*/

    #Creazione oggeto grafico
    #$chart = new PieChart(840, 500);
    #$dataSet = new XYDataSet();

    #Query per il conteggio reale dei biglietti: da sistemare

    /*$query = "SELECT righe_ordini.id_prodotto, count(righe_ordini.id_prodotto), sum(righe_ordini.quantita), prodotti.descrizione_prod,
    prodotti.olpp FROM prodotti, righe_ordini WHERE prodotti.id_prodotto = righe_ordini.id_prodotto Group BY prodotti.descrizione_prod";*/

    //AND ordini.tipo = 'stf'
    #Query per la popolazione della tabella e per la generazione del grafico
    $query = "SELECT sum(totale) as tot, sum(num_biglietti) as tot_pezzi, count(id_ordine) as tot_ord
    from ordini WHERE ordini.data_ora BETWEEN '$d_i' AND '$d_f' AND tipo = '".TIPO_ORDINE."'
    order by data_ora";
    //print "$query";
    $ris = mysql_query_safe($mysqli, $query);

    $pdf->AddPage();

    $pdf->SetFont('Arial','BI',18);
    $pdf->Cell(190,20,"Totali generali evento",0,0,"L");

    $tot_ris = mysql_result($ris, 0, "tot_ord");
    $tot_pezzi = mysql_result($ris, 0, "tot_pezzi");

    if(TOTAL_LABEL == 1)
    {
        $totale_biglietti = $tot_pezzi + $tot_ris . " di cui ($tot_ris) tot.";
        $tot_pezzi = $tot_pezzi + $tot_ris;
    }
    else
        $totale_biglietti = $tot_pezzi;

    $tot = mysql_result($ris, 0, "tot");
    $tot = number_format($tot, 2, ',', '.');
    $pdf->Ln(14);
    $pdf->SetFont('Arial','BI',18);
    $pdf->Cell(60,6,"Totale ordini",1,0,"C");
    $pdf->Cell(70,6,"Totale biglietti emessi",1,0,"C");
    $pdf->Cell(60,6,"Totale incasso",1,0,"C");
    $pdf->Ln();
    $pdf->SetFont('Arial','',18);
    $pdf->Cell(60,6,$tot_ris,1,0,"C");
    $pdf->Cell(70,6,$totale_biglietti,1,0,"C");
    $pdf->Cell(60,6,$tot,1,0,"R");
    $pdf->Ln();
    $pdf->SetFont('Arial','BI',18);
    $pdf->Cell(60,6,"Rotoli utilizzati",1,0,"C");
    $pdf->Cell(70,6,"Etichette rimaste",1,0,"C");
    $pdf->Ln();
    $pdf->SetFont('Arial','',18);

    $diff = $tot_pezzi - 967;
    $rotoli = 1;
    /*while(1)
    {
        $diff = $diff - 967;
        if($diff > 0)
        {
            $rotoli ++;
            if($diff < 967)
            {
                $rotoli ++;
                break;
            }
        }
        else
            break;
    }*/

    $n_rotoli_int = (int)($tot_pezzi/967);
    $resto = $tot_pezzi - ($n_rotoli_int*967);

    if($resto > 0)
        $n_rotoli_int++;

    $restanti = 967 - $resto;

    $pdf->Cell(60,6,$n_rotoli_int,1,0,"C");
    $pdf->Cell(70,6,"$restanti ",1,0,"C");
}

function genPdfCucina($ris, $evento, $pag, $mysqli)
{
    require('pdf/fpdf.php');
    $now = date("d/m/Y H:i");
    class PDF extends FPDF
    { }
    $pdf=new PDF();
    $pdf->SetAutoPageBreak(FALSE);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',22);
    $pdf->Cell(190,10,"$evento",0,0,"C");
    $pdf->Ln(20);

    $header=array('N. ordine','Ravioli','Gnocchi', 'Risotto', 'Pane Z.', 'Affett.', 'Asp.');
    $w=array(33,25,25,25,25,25,23);

    $pdf->SetFont('Arial','BI',16);
    for($j=0;$j<count($header);$j++)
        $pdf->Cell($w[$j],7,$header[$j],1,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','',30);

    $id_ordine = -1;
    $c = 0;
    $qta_tot = array('0', '0', '0', '0', '0');
    $color = 1;
    while($riga = mysql_fetch_array($ris))
    {
        $qta = array('0', '0', '0', '0', '0', '0');
        if($id_ordine == -1)
        {
            $id = $riga['id_ordine'];
            $first_id = $id;
        }
        $id_ordine = $riga['id_ordine'];

        //printa("$riga[tipo]");

        if(strcmp($riga['tipo'], "asp") == 0)
            $asp = "X";
        else
            $asp = "";

        #printa("INIZZ:  id_ordine: $id_ordine - id:$id");
        $qta[5] = $id_ordine;
        for($i=0; $i<5; $i++)
        {
            if($id_ordine == $id)
            {
                #printa("Id_Ord: $qta[4] -ID_prod:$riga[id_prodotto] - Q.ta:$riga[quantita] - c:$c ");
                if($riga['id_prodotto'] == 1)
                    $qta[0] = $riga['quantita'];
                else if($riga['id_prodotto'] == 2)
                    $qta[1] = $riga['quantita'];
                else if($riga['id_prodotto'] == 3)
                    $qta[2] = $riga['quantita'];
                else if($riga['id_prodotto'] == 4)
                    $qta[3] = $riga['quantita'];
                else
                    $qta[4] = $riga['quantita'];
                $riga = mysql_fetch_array($ris);
                $c ++;
                if($riga['id_ordine'] != $id)
                {
                    $id = $riga['id_ordine'];
                    $y = $c;
                    @mysql_data_seek($ris, $y);
                    break;
                }
            }
            else
                break;
        }
        //printa("PDF: Id_Ord: $qta[4] - RAV:$qta[0] - Gnoc:$qta[1] - Ris:$qta[2] -Pan:$qta[3]");

        #printa("id_ordine: $id_ordine - id:$id");

        if($color)
        {
            $pdf->SetFillColor(200,200,200);
            $color = 0;
        }
        else
        {
            $pdf->SetFillColor(255,255,255);
            $color = 1;
        }

        $pdf->Cell(33,18,$qta[5],1,0,"C",1);
        $pdf->Cell(25,18,$qta[0],1,0,"C",1);
        $pdf->Cell(25,18,$qta[1],1,0,"C",1);
        $pdf->Cell(25,18,$qta[2],1,0,"C",1);
        $pdf->Cell(25,18,$qta[3],1,0,"C",1);
        $pdf->Cell(25,18,$qta[4],1,0,"C",1);
        $pdf->Cell(23,18,"$asp",1,0,"C",1);
        $pdf->Ln();

        $qta_tot[0] += $qta[0];
        $qta_tot[1] += $qta[1];
        $qta_tot[2] += $qta[2];
        $qta_tot[3] += $qta[3];
        $qta_tot[4] += $qta[4];
    }
    $pdf->SetFont('Arial','B',30);
    $pdf->Cell(33,20,"TOT.",1,0,"C");
    $pdf->Cell(25,20,$qta_tot[0],1,0,"C");
    $pdf->Cell(25,20,$qta_tot[1],1,0,"C");
    $pdf->Cell(25,20,$qta_tot[2],1,0,"C");
    $pdf->Cell(25,20,$qta_tot[3],1,0,"C");
    $pdf->Cell(25,20,$qta_tot[4],1,0,"C");
    $pdf->Cell(23,20,"",1,0,"C");
    $pdf->Ln();
    $pdf->Ln(10);
    //Seleziona Arial corsivo 8
    $pdf->SetFont('Arial','I',26);
    //Stampa il numero di pagina centrato
    $pdf->Cell(0,10,"Foglio $pag - $now",0,0,'C');

    $pdf->Output("pdf_cucina/report_cucina-$pag.pdf");

    $query = "INSERT INTO cucina (id, id_da, id_a, data_ora) VALUES ('$pag', '$first_id', '$qta[5]', '$now');";
    $ris = mysql_query_safe($mysqli, $query);

    $pag++;

    $query = "UPDATE rcp SET last_id = $qta[5], pagina = $pag WHERE id = 0";
    $ris = mysql_query_safe($mysqli, $query);

    /*$pf = fopen("last_id.inc", "w")
	      or die("Impossibile trovare il file last_id.inc !!! ");
		$pag++;
    $str = "$qta[5]-$pag";
    fwrite($pf, $str);
		fclose($pf);*/
}

function getPrinterMediaType()
{
    $ret['type'] = leggiFile("printer.inc");
    if($ret['type'] == "C")
        $ret['txt'] = "Stampante GX420t - Carta continua ";
    else
        $ret['txt'] = "Stampante TLP2844 - Cartoncini a strappo";

    return $ret;
}

function setPrinterMediaType($type)
{
    $pf = fopen("printer.inc", "w")
    or die("Impossibile trovare il file printer.inc !!! ");
    fwrite($pf, $type);
    fclose($pf);
}

function stampa_contatori($pwd, $mysqli)
{
    $query = "SELECT * FROM contatori WHERE attivo = 'T'";
    $ris = mysql_query_safe($mysqli, $query);

    while($riga = mysqli_fetch_array($ris))
    {
        print "<button class=\"bottone_conf\" onclick=\"location.href='conta.php?id=$riga[id_contatore]&code=$pwd'\">$riga[nome]</button>";
    }
}

function conta_prodotto($id_contatore, $mysqli)
{
    $query = "SELECT * FROM contatori, prodotti_contatori WHERE contatori.id_contatore = prodotti_contatori.id_contatore AND contatori.id_contatore = $id_contatore";
    $ris = mysql_query_safe($mysqli, $query);
    $totale = 0;
    while($riga = mysqli_fetch_array($ris))
    {
        $controllo_periodo = $riga['controllo_periodo'];
        $nome = $riga['nome'];
        $data_da = $riga['data_da'];
        $data_a = $riga['data_a'];
        $qta = $riga['quantita'];
        $query_conta = "SELECT SUM(quantita)*$qta AS tot FROM righe_ordini, ordini WHERE ordini.id_ordine = righe_ordini.id_ordine AND righe_ordini.id_prodotto = $riga[id_prodotto]";

        if($controllo_periodo == "T")
        {
            $query_conta .= " AND data_ora BETWEEN '$data_da' AND '$data_a'";
        }

        $ris_conta = mysql_query_safe($mysqli, $query_conta);
        $tot_par = mysql_result($ris_conta,0 , "tot");

        $totale += $tot_par;
    }
    $ret['totale'] = $totale;
    $ret['nome'] = $nome;
    return $ret;
}

function generaCardDegustazione($numero, $qta)
{
    $top = 600;

    $label = "N\nA832,$top,2,3,2,3,R,\"     DEGUSTAZIONE $numero SAKE         \"\n";
    $top1 = $top-80;
    //$label .= "A832,$top1,2,3,2,2,N,\"    30 Ott. - 3 Nov. 2025\"\n";
    $h = $top1-120;
    if($numero == 1)
    {

        $label .= "X475,$top1,10,355,$h\n";
    }

    if($numero == 2)
    {
        $label .= "X600,$top1,10,480,$h\n";
        $label .= "X400,$top1,10,280,$h\n";
    }

    if($numero == 5)
    {
        $label .= "X770,$top1,10,650,$h\n";
        $label .= "X620,$top1,10,500,$h\n";
        $label .= "X470,$top1,10,350,$h\n";
        $label .= "X320,$top1,10,200,$h\n";
        $label .= "X170,$top1,10,50,$h\n";
    }
    $now = date("d/m/y H:i");
    //$label .= "A800,$top1,2,3,1,1,N,\"                Cassa$cassa - $now\"\n";
    /* $label .= "A800,$top1,2,4,2,2,N,\"     Totale: $totale"."Õ\"\n";
       $top1 = $top1-60;
       $label .= "A800,$top1,2,2,1,1,N,\"                   Non valido ai fini fiscali\"\n";*/
    $top1 = $top1-150;
    $label .= "A800,$top1,2,2,2,2,N,\"Il presente biglietto da diritto\"\n";
    $top1 = $top1-40;
    $label .= "A800,$top1,2,2,2,2,N,\"alla degustazione di $numero sake ed\"\n";
    $top1 = $top1-40;
    $label .= "A800,$top1,2,2,2,2,N,\"e' valido per tutta la durata \"\n";
    $top1 = $top1-40;
    $label .= "A800,$top1,2,2,2,2,N,\"dell'evento 16 Ottobbre 2025\"\n";
    $top1 = $top1-60;
    $label .= "A800,$top1,2,4,1,1,N,\"  La quantita' di ciascun assaggio e' di 30ml\"\n";
    $top1 = $top1-60;
    $label .= "A800,$top1,2,3,1,1,N,\"             Stampato il  $now\"\n";
    $top1 = $top1-40;
    $label .= "A800,$top1,2,2,1,1,N,\"(La vendita di bevande alcoliche e' vietata ai minori di 18 anni)\"\n";
    /*$top1 = $top1-60;
	  $label .= "A800,$top1,2,8,1,1,N,\"ppp 日本酒 ppp\"\n";*/
    $top1 = $top1-40;
    $label .= "A832,$top1,2,2,2,2,R,\"         www.sushitalia.com             \"\n";

    $top1 = $top1-60;
    //$label .= "A800,$top1,2,5,2,2,N,\"Ogni assaggio prevede una quantità di 30ml \"\n";
    #Numero biglietti
    $label .= "P1\n";
    //Taglio Carta
    $label .= "C\n";

    if($qta > 1)
    {
        for($i=1; $i<$qta; $i++)
        {
            $label .= $label;
        }
    }

    $pf = fopen("labelCards", "w")
    or die("Impossibile aprire il file di stampa");
    fwrite($pf, $label, strlen($label));
}

function genera_file_stampa($ris, $numero_righe)
{
    $total_label = TOTAL_LABEL;
    $print_order_id = PRINT_ORDER_ID;
    $evento = EVENT_NAME;
    $credits = CREDITS;
    $cassa = ID_CASSA;
    $printer = "PIPPO";

    #Apertura del file
    $pf = fopen(LABELS_FILE, "w")
    or die("Impossibile aprire il file di stampa");
    if(CONTINUOUS_LABEL)
    {
        $printer = "-P GX420t";
        $riga = mysqli_fetch_array($ris);
        $id_ordine = $riga['id_ordine'];
        $totale = $riga['tot_ord'];
        $totale = number_format($totale, 2, '.', '.');
        $tipo = $riga['tipo'];
        $t = "";

        if($print_order_id == 1)
            $id = "N.$id_ordine";
        else
            $id = "";

        if($tipo == "nor")
            $t = "Normale";
        if($tipo == "asp")
            $t = "Asporto";

        #Dati evento Formato: X,Y,FONT,MULTIPLIER,ROTAZIONE, , REVERSE/NORMAL<img src="labels/set_euro" alt="set_euro, 8B" title="Set euro" border="0" height="0" width="0">
        #Origine X angolo DX alto
        //$label .= "N\nA438,230,2,2,1,1,R,\"$evento\"\n";
        $top = 420 + ($numero_righe * 60);
        $now = date("d/m/y H:i");
        #Dati evento
        //$label = "N\nA832,$top,2,3,2,3,R,\"     Festa del SUGIDAMA         \"\n";
        $label = "N\nA832,$top,2,3,2,3,R,\" Mostra CAPRA OROBICA Casargo    \"\n";
        $top1 = $top-80;
        $label .= "A832,$top1,2,3,2,2,N,\"    15 - 16 Ottobre 2025\"\n";
        $top1 = $top1-60;
        $label .= "A800,$top1,2,3,1,1,N,\"                Cassa$cassa - $now\"\n";
        $top1 = $top1-60;
        mysqli_data_seek($ris,0);
        $num_pezzi = 0;
        while($riga = mysqli_fetch_array($ris))
        {
            $testo = $riga['testo'];
            $quantita = $riga['quantita'];
            $importo = $riga['totale'];
            $importo = number_format($importo, 2, '.', '.');
            $importo .= "Õ";
            //Test per verificare se devo stampare i cartellini per le degustazioni del sake

            #Modifica del testo su più righe
            #$testo = testo_biglietti($testo);
            $testo = strtoupper($testo);
            #Nome prodotto
            $label .= "A800,$top1,2,1,1,2,N,\"N.$quantita $testo\"\n";
            #Prezzo
            if(strlen($importo) == 6)
                $importo = " ".$importo;
            $label .= "A170,$top1,2,1,2,2,N,\"$importo\"\n";
            #pos.partenza orizzontale, pos.partenza Verticale, rotazione, font(grandezza), vertical multiplier, orizzontal multiplier, reverse image (R=reverse, N=normal), "stringa da stampare"
            $num_pezzi++;
            $top1 = $top1-60;

            if($riga['id_prodotto'] == 14 || $riga['id_prodotto'] == 15 || $riga['id_prodotto'] == 16)
            {
                if($riga['id_prodotto'] == 14)
                    generaCardDegustazione(1, $quantita);
                if($riga['id_prodotto'] == 15)
                    generaCardDegustazione(2, $quantita);
                if($riga['id_prodotto'] == 16)
                    generaCardDegustazione(5, $quantita);
                system("lpr $printer labelCards");
            }
        }
        $label .= "A800,$top1,2,4,2,2,N,\"     Totale: $totale"."Õ\"\n";
        $top1 = $top1-60;
        //$label .= "A800,$top1,2,2,1,1,N,\"                   Non valido ai fini fiscali\"\n";
        $top1 = $top1-60;
        $label .= "A832,$top1,2,2,2,2,N,\"      Arrivederci e Grazie !!!     \"\n";
        $top1 = $top1-60;
        $label .= "A832,$top1,2,2,2,2,R,\"         www.sushitalia.com             \"\n";
        //$top1 = $top1-60;
        //$label .= "A800,$top1,2,3,2,2,N,\"          ordine n:\"\n";
        //$top1 = $top1-60;
        //$label .= "A800,$top1,2,5,2,2,N,\"    $id_ordine\"\n"
        #Numero biglietti
        $label .= "P1\n";
        //Taglio Carta
        $label .= "C\n";
        print "Totale ordine: $totale &euro;<br>";
        $num_pezzi = 1;
    }
    else
    {
        $riga_biglietto = mysqli_fetch_array($ris);

        $id_ordine = $riga_biglietto['id_ordine'];
        /*if(olpp==1)
            $n_pezzi = mysql_result($ris, 0, "n_pezzi");
        else
        {
            $query_1 = "SELECT count(righe_ordini.id_prodotto) AS n FROM righe_ordini WHERE righe_ordini.id_ordine = $id_ordine";
            $ris_1 = mysql_query_safe($mysqli, $query_1);
            $n_pezzi = mysql_result($ris_1,0,"n");
        }*/
        $totale = $riga_biglietto['tot_ord'];


        $totale = number_format($totale, 2, '.', '.');
        //print "totale: $totale";

        $tipo = $riga_biglietto['tipo'];
        $t = "";

        if($print_order_id == 1)
            $id = "N.$id_ordine";
        else
            $id = "";

        if($tipo == "pre")
            $t = "#P#";
        if($tipo == "mus")
            $t = "#M#";
        if($tipo == "stf")
            $t = "#S#";
        if($tipo == "asp")
            $t = "#A#";

        mysqli_data_seek($ris,0);
        #print "Totale ordine: $totale &euro;<br>Inviati in stampa $n_pezzi biglietti<br><br>";
        $label = "";
        #Stampo un biglietto aggiuntivo con il totale dell'ordine
        if($total_label == 1)
        {
            $now = date("d/m/Y H:i");
            /*  #Dati evento
            $label .= "N\nA438,230,2,2,1,1,R,\"$evento\"\n";
            #Nome prodotto su 2 righe
            $label .= "A380,200,2,4,3,3,N,\"TOTALE\"\nA380,135,2,4,3,3,N,\"$totale"."?\"\n";
            #Numero cassa data e ora biglietto
            $label .= "A390,65,2,2,1,1,N,\"Non valido ai fini fiscali\"\n";
            #Credits
            $label .= "A390,14,2,1,1,1,N,\"$credits\"\n";
            #Numero biglietti
            $label .= "P1\n"; */

            #TEST ZPL totale
            $label .= "^XA\n^POI\n^FO0,9^GB470,30,30^FS\n^FO35,14^ADN^FR^FD$evento^FS\n";
            #Nome prodotto su 2 righe
            $label .= "^FO15,100^ADN,45^FD\"TOTALE\"^FS\n ^FO15,135^AAN,14^FD$totale^FS\n";
            #Numero cassa data e ora biglietto
            $label .= "^FO15,200^AAN,14^FD\"Non valido ai fini fiscali\"^FS\n";
            #Credits
            $label .= "^FO15,225^AAN,14^FD$credits^FS\n";
            #Numero biglietti
            $label .= "^PQ\n^XZ\n";

        }
        fwrite($pf, $label, strlen($label));
        $label = "";
        #Ciclo che scrive il file da inviare alla stampante
        $num_pezzi=0;
        while($riga = mysqli_fetch_array($ris))
        {
            $now = date("d/m/y H:i");
            #Dati evento Formato: X,Y,FONT,MULTIPLIER,ROTAZIONE, , REVERSE/NORMAL<img src="labels/set_euro" alt="set_euro, 8B" title="Set euro" border="0" height="0" width="0">
            #Origine X angolo DX alto
            $testo = $riga['testo'];
            $quantita = $riga['quantita'];
            /*print("	<script language=\"javascript\">
                          alert(\"testo = $testo quantita = $quantita\")
                      </script>");*/
            #Modifica del testo su più righe
            $testo = testo_biglietti($testo);
            $olpp_2=$riga['olpp'];
            #se l'id che voglio stampare è quello del menù e la funzione stampa menù è abilitata allora chiamo la funzione stampa menu passandogli come parametri

            #preparo un vettore contenente tutti i parametri da passare alla funzione
            $parametri["now"]=$now;
            $parametri["id"] = $id;
            $parametri["cassa"]=$cassa;
            $parametri["credits"] = $credits;
            $parametri["evento"] = $evento;
            $parametri["t"] = $t;
            $parametri["quantita"] = $quantita;
            $parametri["testo"] = $testo;
            $parametri["olpp"] = $olpp_2;
            $parametri["testo"] = $testo;
            $parametri["num_pezzi"] = $num_pezzi;
            $parametri["print_order_id"] = $print_order_id;

            if( ($riga["id_prodotto"]== 60 || $riga["id_prodotto"]==61 || $riga["id_prodotto"]==62 || $riga["id_prodotto"]==63) && MENU_ENABLE)
            {
                #chiamo la funzione
                $vect = stampaMenu($testo,$quantita,0,$parametri);
                #come ritorno dalla funzione ho 2 parametri, num_pezzi è il numero di cartellini stampati e label e la stringa che verrà stampata
                $num_pezzi += $vect["num_pezzi"];
                $label .= $vect["label"];
            }
            else
            {
                #Determinazione della modalità di stampa delle etichette
                $ret = get_product_label($parametri);
                $num_pezzi = $ret['num_pezzi'];
                $label .= $ret['label'];
            }
        }
        print "Totale ordine: $totale &euro;<br>Inviati in stampa $num_pezzi biglietti<br>";
        if($printer == "-P GX420t")
            //Taglio Carta
            $label .= "C\n";
    }
    #Scrittura del file
    fwrite($pf, $label, strlen($label));
    #fwrite($pf, utf8_decode($label), strlen($label));
    fclose($pf);
    $vect["num_pezzi"] = $num_pezzi;
    $vect["id_ordine"] = $id_ordine;
    $vect["totale"] = $totale;
    //print "totale2: $totale";
    return $vect;
}

function get_product_label($parametri)
{
    $label = "";
    $now = $parametri["now"] ;
    $id = $parametri["id"];
    $cassa = $parametri["cassa"];
    $credits = $parametri["credits"];
    $evento = $parametri["evento"];
    $t = $parametri["t"];
    $quantita = $parametri["quantita"];
    $testo = $parametri["testo"];
    $olpp = $parametri["olpp"];
    $testo = $parametri["testo"];
    $num_pezzi = $parametri["num_pezzi"];
    $print_order_id = $parametri["print_order_id"];

    if(($olpp=="T") || ($quantita==1))
    {
        /* #Stampa di un'etichetta per ogni singola quantità di un prodotto
        #Nome prodotto su 2 righe
        $label .= "N\nA438,230,2,2,1,1,R,\"$evento\"\n";
        $label .= "A440,195,2,5,1,1,N,\"$testo[0]\"\nA440,135,2,5,1,1,N,\"$testo[1]\"\n";
        #Numero cassa data e ora biglietto
        if($print_order_id == 1)
        $label .= "A430,65,2,2,1,1,N,\"$id Cassa$cassa - $now $t\"\n";
        else
        $label .= "A380,65,2,2,1,1,N,\"Cassa$cassa - $now $t\"\n";
        #Credits
        $label .= "A390,14,2,1,1,1,N,\"$credits\"\n";
        #Quantità
        $label .= "P".$quantita."\n";
        $num_pezzi += $quantita;  */

        # TEST ZPL
        $label .= "^XA\n^POI\n^FO0,9^GB470,30,30^FS\n^FO35,14^ADN^FR^FD$evento^FS\n";
        $label .= "^FO35,65^ADN,45^FD$testo[0]^FS\n^FO35,125^ADN,45^FD$testo[1]^FS\n";
        #Numero cassa data e ora biglietto
        if($print_order_id == 1)
            $label .= "^FO90,195^ADN^FD$id Cassa$cassa - $now $t^FS\n";
        else
            $label .= "^FO90,195^ADN^FDCassa$cassa - $now $t^FS\n";
        #Credits
        $label .= "^FO15,225^AAN,14^FD$credits^FS\n";
        #Quantità
        $label .= "^PQ".$quantita."\n^XZ\n";
        $num_pezzi += $quantita;

    }
    else
    {
        /* $label .= "N\nA438,230,2,2,1,1,R,\"$evento\"\n";
        #Stampa di un'etichetta con la quantità di un prodotto
        if(strlen($testo[0])<=8)    #Controllo per non uscire dall'etichetta con il testo, se è troppo grande riduce il font
        {
        #Nome prodotto su 2 righe
        $label .= "A440,195,2,5,1,1,N,\"$quantita"."X $testo[0]\"\nA440,135,2,5,1,1,N,\"$testo[1]\"\n";   #pos.partenza orizzontale, pos.partenza Verticale, rotazione, font(grandezza), vertical multiplier, orizzontal multiplier, reverse image (R=reverse, N=normal), "stringa da stampare"
        #Numero cassa data e ora biglietto
        if($print_order_id == 1)
          $label .= "A430,65,2,2,1,1,N,\"$id Cassa$cassa - $now $t\"\n";
        else
          $label .= "A380,65,2,2,1,1,N,\"Cassa$cassa - $now $t\"\n";
        #Credits
        $label .= "A390,14,2,1,1,1,N,\"$credits\"\n";
        #Quantità
        $label .= "P1\n";
        $num_pezzi++;
        }
        else
        {
        #Nome prodotto su 2 righe
        $label .= "A440,195,2,1,3,3,N,\"$quantita"."x $testo[0]\"\nA440,135,2,1,3,3,N,\"$testo[1]\"\n";
        #Numero cassa data e ora biglietto
        if($print_order_id == 1)
          $label .= "A430,65,2,2,1,1,N,\"$id Cassa$cassa - $now $t\"\n";
        else
          $label .= "A380,65,2,2,1,1,N,\"Cassa$cassa - $now $t\"\n";
        #Credits
        $label .= "A390,14,2,1,1,1,N,\"$credits\"\n";
        #Quantità
        $label .= "P1\n";
        $num_pezzi++;   */

        # TEST ZPL
        $label .= "^XA\n^POI\n^FO0,9^GB470,30,30^FS\n^FO35,14^ADN^FR^FD$evento^FS\n";
        #Stampa di un'etichetta con la quantità di un prodotto
        if(strlen($testo[0])<=8)    #Controllo per non uscire dall'etichetta con il testo, se è troppo grande riduce il font
        {
            #Nome prodotto su 2 righe
            $label .= "^FO35,65^ADN,45^FD$quantita"."X $testo[0]^FS\n^FO35,125^ADN,45^FD$testo[1]^FS\n";
            #Numero cassa data e ora biglietto
            if($print_order_id == 1)
                $label .= "^FO90,195^ADN^FD$id Cassa$cassa - $now $t^FS\n";
            else
                $label .= "^FO90,195^ADN^FDCassa$cassa - $now $t^FS\n";
            #Credits
            $label .= "^FO15,225^AAN,14^FD$credits^FS\n";
            #Quantità
            $label .= "^PQ1\n^XZ\n";
            $num_pezzi++;
        }
        else
        {
            #Nome prodotto su 2 righe
            $label .= "^FO35,65^ABN,24^FD$quantita"."X $testo[0]^FS\n^FO35,125^ABN,24^FD$testo[1]^FS\n";
            #Numero cassa data e ora biglietto
            if($print_order_id == 1)
                $label .= "^FO90,195^ADN^FD$id Cassa$cassa - $now $t^FS\n";
            else
                $label .= "^FO90,195^ADN^FDCassa$cassa - $now $t^FS\n";
            #Credits
            $label .= "^FO15,225^AAN,14^FD$credits^FS\n";
            #Quantità
            $label .= "^PQ1\n^XZ\n";
            $num_pezzi++;
        }
    }

    $ret['num_pezzi'] = $num_pezzi;
    $ret['label'] = $label;
    return $ret;

}

function ftpPut()
{
    $file = 'label';

    // set up basic connection
    $ftp = ftp_connect("192.168.0.205");

    // login with username and password
    $login_result = ftp_login($ftp, "", "");

    // upload a file
    if (ftp_put($ftp, $file, $file, FTP_BINARY))
    {
        //echo "successfully uploaded $file\n";
    } else
    {
        //echo "There was a problem while uploading $file\n";
    }

    // close the connection
    ftp_close($ftp);
}




?>
