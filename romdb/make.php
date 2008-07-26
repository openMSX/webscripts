<?PHP
INCLUDE('connect.php');

$time_start = explode(' ', microtime());

$strOut	 ='<?xml version="1.0" encoding="utf-8"?>'.chr(10);
$strOut	.='<!DOCTYPE softwaredb SYSTEM "softwaredb1.dtd">'.chr(10);

$strOut	.='<!--'.chr(10);
$strOut	.='The softwaredb.xml file contains information about rom mapper types'.chr(10);
$strOut	.chr(10);
$strOut	.='Copyright 2004-2008 Benoit Delvaux		(Database Administrator)'.chr(10);
$strOut	.='Copyright 2003 Nicolas Beyaert			(Initial Database)'.chr(10);
$strOut	.='Copyright 2005-2008 Patrick van Arkel 	(Database Administrator)'.chr(10);
$strOut	.chr(10);
$strOut	.='A special thanks to both the blueMSX and openMSX team for their support'.chr(10);
$strOut	.='-->'.chr(10);
$strOut	.='<softwaredb xml:lang="en" timestamp="'.time().'">'.chr(10);
$strOut	.='<!-- MSX Rom Info -->'.chr(10);

$romcount=0;

$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
#$query='SELECT gamename,max(company) as Company,min(year) as year,max(country)as country,max(company) as company,count(*) as games from getrominfo where system in ("msx1","msx2","arab","system") and sha1<>"" group by gamename order by 1';
$query='SELECT gamename,max(company) as company,min(year) as year,max(country)as country,company as company,count(*) as games from getrominfo where system in ("msx1","msx2","arab") and sha1<>"" group by gamename,company order by 1,2';

mysql_select_db($database) or die("Cannot Connect to database");	
$result = mysql_query($query) or die ("Cannot handle Query:".$query);
$numofrows = mysql_num_rows($result);
	echo('<h3>ROM Datafile Builder</h3>');
	echo('<table border="1" cellspacing="0" cellpadding="0" style="background:#000000;font-size:10px;font-family:verdana;">');
	echo('<tr><td style="color:#ffffff;">#</td><td style="color:#ffffff;">Company</td><td style="color:#ffffff;">Name</td><td style="color:#ffffff;">Count</td></tr>');
			    for ($i=0;$i<$numofrows;$i++){
					$row = mysql_fetch_array($result);
					$strOut	.=buildRomInfo($hostname,$username,$password,$database,$row["gamename"],$row["company"],$row["year"],$row["country"]);
					echo('<tr><td style="background:#dddddd;">'.$i.'</td><td style="background:#efefef;">'.$row["company"].'</td><td style="background:#eeeeee;">'.$row["gamename"].'</td><td style="background:#ffffcc;text-align:right;">'.$row['games'].'</td></tr>');
					$romcount=$romcount+$row['games'];
				}
	echo('<tr><td></td><td></td><td style="color:#ffffff;">Total</td><td style="color:#ffffff;text-align:right;">'.$romcount.'</td></tr>');
	echo('</table>');

#buildRomInfo($hostname,$username,$password,$database,'Sky Jaguar','MSX1','Konami',1989,'JP');
function 	buildRomInfo($hostname,$username,$password,$database,$GameName		,$Company		,$Year		,$Country) {
	$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
	$query='SELECT distinct * FROM getrominfo WHERE gamename="'.$GameName.'" and company="'.$Company.'"';
	mysql_select_db($database) or die("Cannot Connect to database");	
	$result = mysql_query($query) or die ("Cannot handle Query:".$query);
	$numofrows = mysql_num_rows($result);
	
	$echo=('<software>'.chr(10));
	$echo.=('	<title xml:lang="en">'.trim(xmlentities($GameName)).'</title>'.chr(10));
	$echo.=('	<system>MSX</system>'.chr(10));
	$echo.=('	<company>'.trim(xmlentities($Company)).'</company>'.chr(10));
	$echo.=('	<year>'.$Year.'</year>'.chr(10));
	$echo.=('	<country>'.$Country.'</country>'.chr(10));

		    for ($i=0;$i<$numofrows;$i++){
				$row = mysql_fetch_array($result);
					$echo.=('		<dump>');
					$echo.=($row["Dumper"]);
					$echo.=('<'.$row["Mapper"].'>');
					$echo.=($row["StartAddress"]);
					$echo.=($row["BootType"]);
					if ($row["Mapper"]=='megarom') {$echo.=('<type>'.$row["romtype"].'</type>');}
					$echo.=('<hash algo="sha1">'.$row["sha1"].'</hash>');
					if (strlen($row["Remark"])>1) {$echo.=('<remark>'.trim(xmlentities($row["Remark"])).'</remark>');}
					$echo.=('</'.$row["Mapper"].'>');
					$echo.=('</dump>');
					$echo.= chr(10);
			}
	$echo.=('</software>'.chr(10));
	
	return $echo;
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

$strOut	.='</softwaredb>';

$time_end 	= explode(' ', microtime());
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

$strOut.=(chr(10).'<!--'.chr(10));
$strOut.=('Roms in this database:'.$romcount.chr(10));
$strOut.=('Parse time '.$parse_time.' seconds on '.date("D M j G:i:s T Y").chr(10).' -->');


	$fh = fopen('softwaredb.xml', 'w') or die("can't open file");
	fwrite($fh,$strOut);
	fclose($fh);

echo('<h3>done</h3>');
?>
<a href="softwaredb.xml">softwaredb.xml</a>