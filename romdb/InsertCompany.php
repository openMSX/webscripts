<?php
include('settings.php');
if (empty($_SESSION['auth'])) {header('Location: login.php');exit();} 

CreateHeader();

/* Game Info */
	$ShortName	= MakeSQLSafe($_REQUEST['shortname']);
	$WebSite	= MakeSQLSafe($_REQUEST['website']);
	$Amateur	= MakeSQLSafe($_REQUEST['amateur']);
	$Country	= MakeSQLSafe($_REQUEST['country']);
	$FullName	= MakeSQLSafe($_REQUEST['fullname']);
	
/* Check for and add new hashes */
	$sqlCom='SELECT max(company_id)+1 as MaxId FROM msxdb_company';	
	$result = $db->querySingle($sqlCom,true);
	$MaxID=$result['MaxId'];

	$sql= ("insert into msxdb_company (company_id,shortname,website,amateur,country,fullname) select ".MakeSQLSafe($MaxID).",'".MakeSQLSafe($ShortName)."','".MakeSQLSafe($WebSite)."','".MakeSQLSafe($Amateur)."','".MakeSQLSafe($Country)."','".MakeSQLSafe($FullName)."'");

	echo('<div id="container" style="width:925px;">');
	$query = $db->exec($sql);
	if ($query) {
		echo 'Company ID added: '.$MaxID.'<br/>';
		echo '<a href="index.php">Go back to Main page</a>';
	}
	echo('</div>');
	
	//header('Location: edit.php?id='.$MaxID);

	//echo($sql);
	
?>
</body>
</html>

