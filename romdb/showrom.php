<html>
<?
$time_start = explode(' ', microtime());
include 'connect.php';

$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
$query="SELECT * FROM benoit where id=".$_REQUEST['romid'];

mysql_select_db($database) or die("Cannot Connect to database");	

$result = mysql_query($query) or die ("Rom not found");
$numofrows = mysql_num_rows($result);
$row = mysql_fetch_array($result);
?>
<html>
<head>
	<title><? echo ($row["gamename"]); ?></title>
	<style>
		td{font-family:verdana;font-size:12px;font-color:#000000;}
		.big {font-size:15px;}
		.right {text-align:right;width:150px;font-weight:bold;}
		.rominfo{width:600px;background:#eeeeee;}
	</style>
</head>
<body onload="this.focus();">
	<a href="showrom.php?romid=<?echo($romid-1);?>">previous</a> | <a href="showrom.php?romid=<?echo($romid+1);?>">next</a>	
<table class="rominfo" border="0">	
	<tr><td colspan="2"><font class="big"><? echo(trim(($row["gamename"]))); ?></font></td></tr>
	<tr><td colspan="2"><hr/></td></tr>
	<tr><td class="right">Extra information : 	</td><td width="400px;"><? echo($row["meta"]); ?></td></tr>
	<tr><td class="right">Company : 			</td><td><? echo($row["company"]); ?></td></tr>
	<tr><td class="right">Year : 				</td><td><? echo($row["year"]); ?></td></tr>
	<tr><td class="right">Country : 			</td><td><? echo($row["country"]); ?></td></tr>
	<tr><td colspan="2"><hr/></td></tr>
	<tr><td class="right">Rom Type : 			</td><td><? echo($row["romtype"]); ?></td></tr>
	<tr><td class="right">Rom / Megarom :		</td><td><? echo(romtag($row["romtype"]))?></td></tr>
	<tr><td class="right">SHA Value :  			</td><td><? echo($row["sha1"]); ?></td></tr>
	<tr><td class="right">CRC32 Value :  		</td><td><? echo($row["crc32"]); ?></td></tr>
	<tr><td colspan="2"><hr/></td></tr>
	<tr><td class="right">Rom Size :  			</td><td><? echo(displaySize($row["filesize"])); ?></td></tr>
	<tr><td class="right">Dump :  				</td><td><? echo($row["dump"]); ?></td></tr>
	<tr><td colspan="2"><hr/></td></tr>
	<tr><td class="right">Remark : 				</td><td><? echo($row["remark"]); ?></td></tr>
	<table>
		<center><a href="javascript:this.close();">Close</a></center>
</body>
</html>
<?

$db->close;

$time_end = explode(' ', microtime());
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

echo('<!-- Parse time '.$parse_time.' seconds on '.date("D M j G:i:s T Y").' -->');

function displaySize($size){
if ($size==0){return 'unknown';}
return ($size/1024).' Kb';

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
