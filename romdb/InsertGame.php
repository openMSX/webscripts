<?php
include('settings.php');
if (empty($_SESSION['auth'])) {header('Location: login.php');exit();} 

/* Game Info */
	$GameName=MakeSQLSafe($_REQUEST['GameName']);
	$Year=MakeSQLSafe($_REQUEST['Year']);
	$CompanyID1=MakeSQLSafe($_REQUEST['CompanyID1']);
	$CompanyID2=MakeSQLSafe($_REQUEST['CompanyID2']);
	$Platform=MakeSQLSafe($_REQUEST['Platform']);
	$GenMSXID=MakeSQLSafe($_REQUEST['GenMSXID']);
	
/* Check for and add new hashes */
	$sqlCom='SELECT max(GameID)+1 as MaxGameID  FROM msxdb_rominfo';	
	$result = $db->querySingle($sqlCom,true);
	$MaxID=$result['MaxGameID'];

	$sql= ("insert into msxdb_rominfo select ".MakeSQLSafe($MaxID).",".MakeSQLSafe($GenMSXID).",'".MakeSQLSafe($GameName)."','".MakeSQLSafe($Year)."','".MakeSQLSafe($CompanyID1)."','".MakeSQLSafe($CompanyID2)."','".MakeSQLSafe($Platform)."'");

	$query = $db->exec($sql);
	if ($query) {
		//echo 'Number of rows modified: ', $db->changes();
	}
	
	header('Location: edit.php?id='.$MaxID);

?>