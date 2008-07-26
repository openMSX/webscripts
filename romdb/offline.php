<?
$time_start = explode(' ', microtime());
include 'connect.php';

echo('<?xml version="1.0" encoding="UTF-8" standalone="no" ?>').chr(10);
echo('<dat xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="datas.xsd">').chr(10);
echo('	<configuration>').chr(10);
echo('		<datName>MSX - Official Emulation Software Database</datName>').chr(10);
echo('		<datVersion>1</datVersion>').chr(10);
echo('		<system>msx</system>').chr(10);
echo('		<screenshotsWidth>272</screenshotsWidth>').chr(10);
echo('		<screenshotsHeight>240</screenshotsHeight>').chr(10);
echo('		<infos>').chr(10);
echo('			<title visible="false" inNamingOption="true" default="false"/>').chr(10);
echo('			<location visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('			<publisher visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('			<saveType visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('			<sourceRom visible="false" inNamingOption="false" default="false"/>').chr(10);
echo('			<romSize visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('			<releaseNumber visible="false" inNamingOption="true" default="false"/>').chr(10);
echo('			<imageNumber visible="true" inNamingOption="false" default="false"/>').chr(10);
echo('			<languageNumber visible="true" inNamingOption="true" default="false"/>').chr(10);
echo('			<comment visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('			<romCRC visible="true" inNamingOption="true" default="false"/>').chr(10);
echo('			<languages visible="true" inNamingOption="true" default="true"/>').chr(10);
echo('		</infos>').chr(10);
echo('		<canOpen>').chr(10);
echo('			<extension>.rom</extension>').chr(10);
echo('			<extension>.dsk</extension>').chr(10);
echo('			<extension>.cas</extension>').chr(10);
echo('		</canOpen>').chr(10);
echo('		<newDat>').chr(10);
echo('			<datVersionURL>http://dummy-address.com</datVersionURL>').chr(10);
echo('			<datURL fileName="msxemu_ol.zip">http://dummy-address.com/msxemu_ol.zip</datURL>').chr(10);
echo('			<imURL>http://dummy-address.com/imgs/</imURL>').chr(10);
echo('		</newDat>').chr(10);
echo('		<search>').chr(10);
echo('			<to value="location" default="true" auto="true"/>').chr(10);
echo('			<to value="romSize" default="true" auto="false">').chr(10);
echo('				<find operation="=" value="8192">8Kbytes</find>').chr(10);
echo('				<find operation="=" value="16384">16Kbytes</find>').chr(10);
echo('				<find operation="=" value="32768">32Kbytes</find>').chr(10);
echo('				<find operation="=" value="65536">64Kbytes</find>').chr(10);
echo('				<find operation="=" value="131072">128Kbytes</find>').chr(10);
echo('				<find operation="=" value="262144">256Kbytes</find>').chr(10);
echo('			</to>').chr(10);
echo('			<to value="saveType" default="false" auto="false"/>').chr(10);
echo('			<to value="languages" default="true" auto="true"/>').chr(10);
echo('		</search>').chr(10);
echo('		<romTitle>%n</romTitle>').chr(10);
echo('	</configuration>').chr(10);
echo('	<games>').chr(10);

$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
$query="SELECT * FROM benoit";

mysql_select_db($database) or die("Cannot Connect to database");	

$result = mysql_query($query) or die ("Cannot handle Query");
$numofrows = mysql_num_rows($result);

for ($i=0;$i<$numofrows;$i++){
	$romnumber = $i+1;
	$row = mysql_fetch_array($result);
	echo('		<game>').chr(10);
	echo('			<imageNumber>'.$romnumber.'</imageNumber>').chr(10);
	echo('			<releaseNumber>'.$romnumber.'</releaseNumber>').chr(10);
	echo('			<title>'.trim(xmlentities(translate($row["gamename"]))).trim(xmlentities($row["meta"])).'</title>').chr(10);
	echo('			<saveType>'.trim(xmlentities($row["romtype"])).'</saveType>').chr(10);
	echo('			<romSize>'.$row["filesize"].'</romSize>').chr(10);;
	echo('			<location>'.getcountry($row["country"]).'</location>').chr(10);
	echo('			<publisher>'.trim(xmlentities($row["company"])).'</publisher>').chr(10);
	echo('			<files>').chr(10);
	echo('				<romCRC extension=".rom">'.$row["crc32"].'</romCRC>').chr(10);
	echo('			</files>').chr(10);
	echo('			<im1CRC>00000000</im1CRC>').chr(10);
	echo('			<im2CRC>00000000</im2CRC>').chr(10);
	echo('			<comment>'.trim(xmlentities($row["remark"])).'</comment>').chr(10);
	echo('		</game>').chr(10);
}
echo('	</games>').chr(10);
echo('</dat>').chr(10);

$db->close;

$time_end = explode(' ', microtime());
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

//echo('<!-- Parse time '.$parse_time.' seconds on '.date("D M j G:i:s T Y").' -->');

function getcountry($country){
$countries = array("Japan" => '7',"The Netherlands" => '8',"Korea" => '20',
				   "Spain" => '4',"England" => '9',"" => '-1', "Brazil" => '21',
				   "France" => '5',"Arabic" => '18');
return $countries[$country];
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

function translate($string)
{
	$illegals = array(":","?","\\");
	return str_replace($illegals,"",$string);
}

?>