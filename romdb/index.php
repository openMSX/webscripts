<?PHP
include('settings.php');

$edit='';

if(!empty($_REQUEST["edit"])) {$edit=$_REQUEST["edit"];} 
if(!empty($_REQUEST["gamename"])){$GameName=$_REQUEST["gamename"];} else {$GameName='';}
if(!empty($_REQUEST["company"])){$Company=$_REQUEST["company"];} else {$Company='';}
if( empty($_REQUEST["company"]) && empty($_REQUEST["gamename"])) {$GameName='A';}

CreateHeader();
SearchMenu();
?>
<div class="background-content">
<?php

	$line=0;
	
	$SQL='';
	$SQL='SELECT * FROM getgameinfo where 1=1 ';
	if (strlen($GameName)==1) {
		$SQL.="and gamename like '".MakeSQLSafe($GameName)."%'";
	}
	if (strlen($GameName)>1) {
		$SQL.="and gamename like '%".MakeSQLSafe($GameName)."%'";
	}
	if (strlen($Company)>1) {
		$SQL.="and Company like '%".MakeSQLSafe($Company)."%'";
	}	
	$SQL.=';';
	
	
	//$db = new SQLite3($DBlocation);	
	$stmt = $db->prepare($SQL);
	$result = $stmt->execute();	
	echo('<div id="container" style="width:925px;">');
	echo('<table>');
	while ($row = $result->fetchArray())
	{
		$line++;
		echo('<tr class="line'.($line % 2).'">');
		echo('<td><img src="grp/icon_edit.png" onclick="edit('.$row['GameID'].');"/></td>');
		//echo('<td><img src="grp/icon_delete.png" onclick="delete('.$row['GameID'].');"/></td>');
		//echo('<td><img src="grp/icon_add.png"/></td>');
		echo('<td>'.$row['GameName'].'</td>');
		echo('<td>'.$row['Company'].'</td>');
		echo('<td>'.$row['Year'].'</td>');
		echo('<td><img src="grp/'.$row['Country'].'.png"/></td>');
		echo('<td><img src="grp/icon_'.strtolower($row['Platform']).'.png"/></td>');
		if ($row['TrainerFound']==1) {
			echo('<td><img src="grp/icon_cheat.png" onclick="cheat('.$row['GameID'].');"/></td>');
		} else {echo('<td></td>');}
		
		if ($row['GenMSXId']>0) {
		echo('<td><img src="grp/icon_gmsx.png" onclick="gotogmsx('.$row['GenMSXId'].');"/></td>');
		} else {echo('<td></td>');}
		
		echo('</tr>'.chr(13).chr(13).chr(10));
	}
	echo('</table>');
	echo('<div>');
	echo('</div>');

	if ($edit!='') {echo('<script>edit('.$edit.');</script');} else {echo('<script>HideBox();</script');}
	
?>

<!-- https://thenounproject.com/search/?q=delete -->

</body>
</html>