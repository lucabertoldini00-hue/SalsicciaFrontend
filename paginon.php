<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//IT" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>SALSICCIA V. 1.0</title>
</head>

<?php
include('set.inc');
if(ONLY_ONE_CATEGORY)
{
    print "<frameset cols=\"850, *\" border=\"0\">
			
				<frame src=\"index.php\" scrolling=\"no\" frameborder=\"0\" name=\"centro\"   onkeypress=\"powerLost(event);\">
			";
}
else
{
    print "<frameset cols=\"850, *\" border=\"0\">
			<frameset rows=\"80,*\" border=\"0\">
				<frame src=\"menu.php\" scrolling=\"no\" frameborder=\"0\" name=\"menu\">
				<frame src=\"index.php\" scrolling=\"no\" frameborder=\"0\" name=\"centro\">
			</frameset>";
}

?>
<!--<frameset cols="850, *" border="0">
<frameset rows="80,*" border="0">
	<frame src="menu.php" scrolling="no" frameborder="0" name="menu">
	<frame src="index.php" scrolling="no" frameborder="0" name="centro">
 </frameset>-->

<frame src="riepilogo.php" scrolling="no" frameborder="0" name="riepilogo">
<noframes>
    <p>Qui deve essere indicato il link a <A href="senzaFrame.html">
            una versione del sito</a> che non utilizzi un layout a frame</p>
</noframes>
</frameset>
</html>


