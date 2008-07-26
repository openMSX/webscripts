<?session_start();
if(md5($_REQUEST['pass'])=="[insert MD5 value]"){$_SESSION['auth']=True;}
//echo($_SESSION['auth']); 
?>

<html>
<?
$time_start = explode(' ', microtime());
include 'connect.php';


$display=urlencode(trim($_REQUEST['display']));

if (strlen($display)<1){$display='A';}
$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
$query="SELECT * FROM benoit WHERE romtype<>'coleco' and active=1 and gamename like '".$display."%' order by gamename";

mysql_select_db($database) or die("Cannot Connect to database");	

$result = mysql_query($query) or die ("not found");
$numofrows = mysql_num_rows($result);

?>
<html>
<head>
	<title>Rom List </title>
	<style>
		td{font-family:verdana;font-size:10px;font-color:#000000;}
		.big {font-size:15px;font-weight:bold;font-family:verdana;}
		.right {text-align:right;width:150px;font-weight:bold;}
		.rominfo{width:900px;background:#888888;}
		.small{font-size:10px;font-weight:bold;font-family:verdana;color:#eeeeee;}
	</style>
	<script>
		function popopen(id){
			WindowObjectReference = window.open("showrom.php?romid="+id, "rominfo","menubar=no,location=no,resizable=yes,scrollbars=no,status=no,height=400,width=400");
		}
<? if ($_SESSION['auth']==True){?>
			function delopen(id){
			WindowObjectReference = window.open("changerom.php?delete=true&romid="+id, "rominfo","menubar=no,location=no,resizable=yes,scrollbars=no,status=no,height=500,width=500");
		}
	
<?}?>
		

	</script>
		<?
echo('<table></tr>');
for ($i=0;$i<26;$i++){
echo('<td>&nbsp;</td><td><a href="database.php?display='.chr(65+$i).'">'.chr(65+$i).'</a><td>&nbsp;</td>');
if ($i==17){echo('</tr><tr>');}
}


for ($i=0;$i<10;$i++){
echo('<td>&nbsp;</td><td><a href="database.php?display='.$i.'">'.$i.'</a><td>&nbsp;</td>');
}
echo('</tr></table>');
?>

<form name="input" action="database.php" method="post">
<input type="text" name="display"><input type="submit" value="Search (partial start of name is ok)">
</form>
	
<font class="big"><? echo($numofrows);?> Entries for: <? echo(urlencode(trim($display)));?></font>
<table class="rominfo" border="0" cellpadding="1" cellspacing="1">
	<tr style="background:#000000;color:#ffffff;">
		<td></td>
		<td>Rom Name and info</font></td>
		<td>Year</td>
		<td>country</td>
		<td>Manufacturer</td>
		<td>Size (Kb)</td>
		<td>Dump</td>
		<td>SHA1</td>
		<td>search</td>
	</tr>


<? for ($i=0;$i<$numofrows;$i++){
	$row = mysql_fetch_array($result);?>
	

	<? if ($_SESSION['auth']==True){?> 
		<tr style="background:#ececff;" onmouseover="javascript:this.style.background='#FF3333';" onmouseout="javascript:this.style.background='#ececff';" onclick="delopen(<? echo($row["id"]); ?>);">
	<?}else{?>
		<tr style="background:#ffec91;" onmouseover="javascript:this.style.background='#FFC154';" onmouseout="javascript:this.style.background='#ffec91';" onclick="popopen(<? echo($row["id"]); ?>);">
	<?}?>

	<td><img src="info.png" alt="Click anywhere to get more information" /></td>
	<td><? echo(trim(($row["gamename"]))." ".$row["meta"]); ?></font></td>
	<td><? echo($row["year"]); ?></td>
	<?if (!empty($row["country"])){?><td style="text-align:center;"><img src="<? echo($row["country"]); ?>.png" border="1px"/></td><?}else{?><td/><?}?>
	<td><? echo($row["company"]); ?></td>
	<td><? echo(displaySize($row["filesize"])); ?></td>
	<td><? echo(getgoodmsx($row["dump"])); ?></td>
	<td><? echo(getsha1bool($row["sha1"])); ?></td>
	<td><a href="http://www.google.com/search?hl=en&lr=&q=site%3Ageneration-msx.nl+%22<? echo(urlencode(trim($row["gamename"]))); ?>%22&btnI=I%27m+Feeling+Lucky" target="new">search</a></td>	
	
	</tr>
<?
	}
	
echo("<table>");
$db->close;

$time_end = explode(' ', microtime());
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

echo('<div class="small">Parse time '.$parse_time.' seconds on '.date("D M j G:i:s T Y").' Webscripting © 2005-2008 Patrick van Arkel</div>');

function getsha1bool($instr){
	if (strlen($instr)>1){return'<img src="sha1.png" alt="SHA1 value" />';}else{return;}
}

function getgoodmsx($instr){
	if ($instr=='goodmsx'){return'<img src="goodmsx.png" alt="This is a goodMSX dump"/>';}else{return;}
}

function displaySize($size){
if ($size==0){return '';}
return round($size/1024).' Kb';

}

function romtag($instr){
	if ($instr=='0x4000'){return 'rom';}
	if ($instr=='mb8877a'){return 'systemrom';}
	if ($instr=='microsol'){return 'systemrom';}
	if ($instr=='svi738fdc'){return 'systemrom';}
	if ($instr=='tc8566af'){return 'systemrom';}
	if ($instr=='wd2793'){return 'systemrom';}
	if ($instr=='msxaudio'){return 'systemrom';}
	if ($instr=='msxaudio'){return 'NMS 1205';}
	return 'megarom';
}

function xmlentities($string, $quote_style=ENT_QUOTES)
{
   static $trans;
   if (!isset($trans)) {
       $trans = get_html_translation_table(HTML_ENTITIES, $quote_style);
       foreach ($trans as $key => $value)
           $trans[$key] = '&#'.ord($key).';';
       // dont translate the '&' in case it is part of &xxx;
       $trans[chr(38)] = '&';
   }
   // after the initial translation, _do_ map standalone '&' into '&#38;'
	return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,5};)/","&#38;" , strtr($string, $trans));
}
?>
