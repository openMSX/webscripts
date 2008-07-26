<?session_start();
if ($_SESSION['auth']!=True){exit("log in");}
$time_start = explode(' ', microtime());
include 'connect.php';

$romid=$_REQUEST['romid'];
$crc32=$_REQUEST['crc32'];
$year=$_REQUEST['year'];
$company=$_REQUEST['company'];
$country=$_REQUEST['country'];
$romtype=$_REQUEST['romtype'];
$meta=$_REQUEST['meta'];
$gamename=$_REQUEST['gamename'];
$sha1=$_REQUEST['sha1'];
$remark=$_REQUEST['remark'];
$filesize=$_REQUEST['filesize'];
$dump=$_REQUEST['dump'];
$active=$_REQUEST['active'];
	
$db = mysql_connect($hostname,$username,$password) or die("Cannot Connect to MySQL server");
//$query="UPDATE FROM benoit where id=".$romid;
$gamename=addslashes($_REQUEST['gamename']);
$remark=addslashes($_REQUEST['remark']);
$country=addslashes($_REQUEST['country']);
$company=addslashes($_REQUEST['company']);
$dump=addslashes($_REQUEST['dump']);

$query="update benoit SET crc32='$crc32',year='$year',company='$company',country='$country',romtype='$romtype',meta='$meta',gamename='$gamename',sha1='$sha1',remark='$remark',filesize='$filesize',dump='$dump',active='$active' where id='".$romid."'"; 

mysql_select_db($database) or die("Cannot Connect to database");	

$result = mysql_query($query) or die ("something died in the database :'(");

//echo($query);

?>
<html>

<head>
<title>Meta Redirect Code</title>
<meta http-equiv="refresh" content="1;url=changerom.php?romid=<? echo($romid) ?>">
</head>

<body>
	<center>
		<a href="changerom.php?romid=<? echo($romid) ?>">Update succesfull</a>
	</center>
</body>
