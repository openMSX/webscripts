<?php
include('settings.php');
if (empty($_SESSION['auth'])) {header('Location: login.php');exit();} 

if(empty($_REQUEST['GameID'])){header('Location: index.php');exit();} else {$GameID=$_REQUEST['GameID'];}

if(!is_numeric($GameID)==1) {header('Location: index.php');exit();}

/* Game Info */
	$GameName=$_REQUEST['GameName'];
	$Year=$_REQUEST['Year'];
	$CompanyID1=$_REQUEST['CompanyID1'];
	$CompanyID2=$_REQUEST['CompanyID2'];
	$Platform=$_REQUEST['Platform'];
	$GenMSXID=$_REQUEST['GenMSXID'];

/* Hashes */
	$HashID=$_REQUEST['HashID'];
	$Dump=$_REQUEST['Dump'];
	$RomType=$_REQUEST['RomType'];
	$SHA1=$_REQUEST['SHA1'];
	$Remark=$_REQUEST['Remark'];
	$Meta=$_REQUEST['Meta'];
	$Active=$_REQUEST['Active'];

/* New Hash Entry */
	$NewRomType=MakeSQLSafe($_REQUEST['NewRomType']);
	$NewSHA1=MakeSQLSafe($_REQUEST['NewSHA1']);
	$NewRemark=MakeSQLSafe($_REQUEST['NewRemark']);
	$NewMeta=MakeSQLSafe($_REQUEST['NewMeta']);
	$NewDump=MakeSQLSafe($_REQUEST['NewDump']);

/* Just create one big SQL statement */
$sql='';

/* Create Game Info */
	$sql.=('update msxdb_rominfo set ');
	$sql.=(" GenMSXID='".MakeSQLSafe($GenMSXID)."'");
	$sql.=(",GameName='".MakeSQLSafe($GameName)."'");	
	$sql.=(",Year='".MakeSQLSafe($Year)."'");
	$sql.=(",CompanyID1='".MakeSQLSafe($CompanyID1)."'");
	$sql.=(",CompanyID2='".MakeSQLSafe($CompanyID2)."'");
	$sql.=(",Platform='".MakeSQLSafe($Platform)."'");
	$sql.=(" Where GameID=".$GameID.";\n");

/* Create Hashes */
	for ($i = 0; $i < count($HashID); $i++)  {
			$sql.=("update msxdb_romdetails set Active='".MakeSQLSafe($Active[$i])."', Meta='".MakeSQLSafe($Meta[$i])."', Remark='".MakeSQLSafe($Remark[$i])."', Dump='".MakeSQLSafe($Dump[$i])."', RomType='".MakeSQLSafe($RomType[$i])."', `SHA1`='".MakeSQLSafe($SHA1[$i])."' where HashID='".MakeSQLSafe($HashID[$i])."';\n");
		}

/* Check for and add new hashes */
	if (strlen($NewSHA1)>1 and strlen($NewRomType)>1) {
		$sqlCom='SELECT max(HashID)+1 as MaxHashID  FROM msxdb_romdetails';	
		$result = $db->querySingle($sqlCom,true);
		$MaxID=$result['MaxHashID'];
		
		//GameID	RomType	SHA1	Remark	Meta	Dump	Active
		$sql.= ("insert into msxdb_romdetails select ".MakeSQLSafe($MaxID).",".MakeSQLSafe($GameID).",'".MakeSQLSafe($NewRomType)."','".MakeSQLSafe($NewSHA1)."','".MakeSQLSafe($NewRemark)."','".MakeSQLSafe($NewMeta)."','".MakeSQLSafe($NewDump)."','1'");
	}

$query = $db->exec($sql);

header('Location: edit.php?id='.$GameID);
exit();
?>
