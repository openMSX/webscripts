<pre>
<?php
include 'connect.php';

$mysql="SELECT distinct * FROM benoit where active=1 and extra IN ('msx1','msx2','arab') AND SHA1<>'';";

	$con = mysql_connect($host,$user,$pass);
	if (!$con) {die('Could not Connect to Server: ' . mysql_error());}
	$blah=mysql_select_db($db, $con);
	if (!$blah) {die('Not Connected to Database');}
	$result = mysql_query($mysql,$con);
	if (!$result) {echo('Error: ' . mysql_error());}

$numofrows = mysql_num_rows($result);

echo('/* openMSX romDB Dump with '.$numofrows.' records */'.chr(10));
$sql="begin;".chr(10);
$sql.="DROP TABLE IF EXISTS romdb;".chr(10);

$sql.="CREATE TABLE romdb (";
$sql.="'id' INTEGER NOT NULL,";
$sql.="'year' tinytext NOT NULL,";
$sql.="'company' varchar(64) NOT NULL,";
$sql.="'country' text NOT NULL,";
$sql.="'romtype' text NOT NULL,";
$sql.="'meta' text NOT NULL,";
$sql.="'gamename' varchar(64),";
$sql.="'sha1' text NOT NULL,";
$sql.="'remark' varchar(512),";
$sql.="'dump' varchar(32) NOT NULL default 'False',";
$sql.="'system' varchar(4) NOT NULL default '',";
$sql.="'genMSXID' bigint(20) default NULL,";
$sql.="PRIMARY KEY  ('id')";
$sql.=")".chr(10);

$sql.="CREATE UNIQUE INDEX IF NOT EXISTS romdb.sha1values on romdb (SHA1);".chr(10);

		for ($i=0;$i<$numofrows;$i++){
			$row = mysql_fetch_array($result);
			$sql.=("insert into romdb values ");
			$sql.=("('".doublequote($row["id"])."'");
			$sql.=(",'".doublequote($row["year"])."'");
			$sql.=(",'".doublequote($row["company"])."'");
			$sql.=(",'".doublequote($row["country"])."'");
			$sql.=(",'".doublequote($row["romtype"])."'");
			$sql.=(",'".doublequote($row["meta"])."'");
			$sql.=(",'".doublequote($row["gamename"])."'");
			$sql.=(",'".doublequote($row["sha1"])."'");
			$sql.=(",'".doublequote($row["remark"])."'");
			$sql.=(",'".doublequote($row["dump"])."'");
			$sql.=(",'".doublequote($row["extra"])."'");
			$sql.=(",'".doublequote($row["genMSXID"])."');".chr(10));
		}

$sql.="commit;".chr(10);

echo($sql);

function doublequote($strString) {
	return str_replace('\'','\'\'',$strString);
}

?>
</pre>
