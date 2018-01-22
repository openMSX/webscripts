<?PHP
include('settings.php');
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$romcount = 0;

$strOut	 ='<?xml version="1.0" encoding="utf-8"?>'.chr(10);
$strOut	.='<!DOCTYPE softwaredb SYSTEM "softwaredb1.dtd">'.chr(10);
$strOut	.='<softwaredb timestamp="'.time().'">'.chr(10);

$strOut	.='<!-- Credits -->'.chr(10);

$strOut	.='<![CDATA['.chr(10);
$strOut	.='The softwaredb.xml file contains information about rom mapper types'.chr(10);
$strOut	.= chr(10);
$strOut	.='Copyright 2003 Nicolas Beyaert (Initial Database)'.chr(10);
$strOut	.='Copyright 2004-2013 BlueMSX Team'.chr(10); 
$strOut	.='Copyright 2005-'.date("Y").' openMSX Team'.chr(10);
$strOut .='Generation MSXIDs by www.generation-msx.nl'.chr(10);
$strOut	.= chr(10);
$strOut	.=']]>'.chr(10);
#get All Entries 

	$stmt = $db->prepare('SELECT distinct GameID FROM getrominfo order by gamename;');
	$result = $stmt->execute();	

	while ($row = $result->fetchArray())
	{
		$strOut.= '<software>'.chr(10);
		$strOut.= GetGameEntry($row['GameID']);
		$strOut.= '</software>'.chr(10);
	}

	$db->close();	
	
$strOut .='</softwaredb>';
$strOut.=(chr(10).'<!-- Roms in this XML file:'.$romcount.' - Created on '.date("D M j Y - G:i:s T").'-->');

$fileName = 'softwaredb.xml';
$PathName = str_replace('htdocs','database',$_SERVER['DOCUMENT_ROOT']);
$fh = fopen($PathName.'/'.$fileName, 'w') or die("can't open file");
fwrite($fh,$strOut);
fclose($fh);

$filename =(string)'Archive/softwaredb-'.date('Ymd-His', time()).'.zip';

echo($filename);

$zip = new ZipArchive;
$res = $zip->open($filename, ZipArchive::CREATE);
if ($res === TRUE) {
    $zip->addFromString('softwaredb.xml', $strOut);
    $zip->close();
    echo 'Created File And Saved';
} else {
    echo 'failed';
}


$zip = new ZipArchive;
$res = $zip->open('Archive/softwaredb-latest.zip', ZipArchive::CREATE);
if ($res === TRUE) {
    $zip->addFromString('softwaredb.xml', $strOut);
    $zip->close();
    echo 'Created File And Saved';
} else {
    echo 'failed';
}

header('Location: archive.php');
exit;

/*

$zip = new ZipArchive();
$zipName = "softwaredb.zip";

if ($zip->open($zipName, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$fileName>\n");
}

echo('Adding:'.$PathName.$fileName);

$zip->addFile($PathName.$fileName);
echo "numfiles: " . $zip->numFiles . "\n";
echo "status:" . $zip->status . "\n";
$zip->close();
*/

function GetGameEntry($GameID) {
	global $db;

	//$db = new SQLite3($DBlocation);	
	$stmt = $db->prepare('SELECT * FROM getrominfo WHERE GameID==:ID limit 1;');
	$stmt->bindValue(':ID', $GameID, SQLITE3_INTEGER);
	$result = $stmt->execute();
	
	$strPage ='';
	
	while ($row = $result->fetchArray())
		{
			//$strPage.= '<software>';
			$strPage.= chr(9).'<title>'.XMLClean($row['gamename']).'</title>'.chr(10);
			if ($row['GenMSXId']>0) {$strPage.= chr(9).'<genmsxid>'.$row['GenMSXId'].'</genmsxid>'.chr(10);}
			$strPage.= chr(9).'<system>'.$row['system'].'</system>'.chr(10);
			$strPage.= chr(9).'<company>'.XMLClean($row['company']).'</company>'.chr(10);
			$strPage.= chr(9).'<year>'.$row['YEAR'].'</year>'.chr(10);
			$strPage.= chr(9).'<country>'.$row['country'].'</country>'.chr(10);			
		}	
	
	$strPage.=GetDump($GameID);
	
	return $strPage;
}

function GetDump($GameID) {
	global $db;
	global $romcount;
	#echo('fetching:'.$GameID.chr(10));
	//$db = new SQLite3($DBlocation);	
	$stmt = $db->prepare('SELECT Dumper,Mapper,StartAddress,BootType,Remark,sha1,romtype FROM getrominfo WHERE GameID==:ID');
	$stmt->bindValue(':ID', $GameID, SQLITE3_INTEGER);
	$result = $stmt->execute();
	
	$echo ='';
	
	while ($row = $result->fetchArray())
		{
			$romcount++;
			$echo.=(chr(9).chr(9).'<dump>');
			$echo.=($row["Dumper"]);
			$echo.=('<'.$row["Mapper"].'>');
			$echo.=($row["StartAddress"]);
			$echo.=($row["BootType"]);
			if ($row["Mapper"]=='megarom') {$echo.=('<type>'.$row["romtype"].'</type>');}
			$echo.=('<hash>'.$row["sha1"].'</hash>');
			if (strlen($row["Remark"])>1) {$echo.=('<remark>'.XMLClean($row["Remark"]).'</remark>');}
			$echo.=('</'.$row['Mapper'].'>');
			$echo.=('</dump>');
			$echo.= chr(10);		
		}
	
	
	return $echo;
}

?>


