<html>
<body>
<?php
	include('settings.php');
	
	if(empty($_REQUEST["id"])){header('Location: index.php');exit();} ELSE {$id= $_REQUEST["id"];}
	
	if(!is_numeric($id)==1) {header('Location: index.php');exit();}
	
	CreateHeader();

	$sqlCom='SELECT * FROM getgameinfo where GameID=\''.$_GET["id"].'\'';
		
	$result = $db->querySingle($sqlCom,true);
	//$result = $stmt->execute();		
	//print_r($result);
	$GameName=$result['GameName'];
	
	echo('<form action="update.php" method="post">');
	echo('<div id="container" style="width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before updating" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}

		echo('<h3> Rom Info For - '.$GameName.'</h3>');
		echo('<table>');
		echo('<tr><td>GameName:	</td><td><input type="hidden" name="GameID" value="'.$id.'" /><input type="text" name="GameName" value="'.$GameName.'" style="width:350;"/></td></tr>');
		echo('<tr><td>Year:		</td><td><input type="text" name="Year" value="'.$result['Year'].'" style="width:75px;"/></td></tr>');
		echo('<tr><td>Company 1:</td><td>'.CompanySelect($result['CompanyID1'],"CompanyID1").'</td></tr>');
		echo('<tr><td>Company 2:</td><td>'.CompanySelect($result['CompanyID2'],"CompanyID2").'</td></tr>');
		echo('<tr><td>Platform :</td><td>'.PlatformSelect($result['Platform'],"Platform").'</td></tr>');
		echo('<tr><td>GenMSXID :</td><td><input type="text" name="GenMSXID" value="'.$result['GenMSXId'].'" style="width:75px;"/></td></tr>');
		echo('<tr><td>Country :</td><td><img src="grp/'.$result['Country'].'.png" /></td></tr>');

	echo('</table>');
	echo('</div>');

	$stmt = $db->prepare('SELECT HashID,RomType,SHA1,Remark,Meta,Dump,Active FROM msxdb_romdetails where GameID='.$id);
	$result = $stmt->execute();		
	echo('<br/>');
	
	echo('<div id="container" style="width:925px;">');
		echo('<h3>Current Hashes</h3>');
		echo('<table>');
		echo('<tr>');
			echo('<td>RomType</td>');
			echo('<td>SHA1 Value</td>');
			echo('<td>Remark</td>');
			echo('<td>Meta</td>');
			echo('<td>Dump</td>');
			echo('<td>Active</td>');
		echo('<tr>');
		
		$i=0;
		while ($row = $result->fetchArray()) {
			echo('<tr>');
				echo('<td><input type="hidden" name="HashID['.$i.']" 	value="'.$row['HashID'].'" />'.MapperSelect($row["RomType"],"RomType[$i]").'</td>');
				echo('<td><input type="text" name="SHA1['.$i.']" 	value="'.$row['SHA1'].'" style="width:350px;" /></td>');
				echo('<td><input type="text" name="Remark['.$i.']" 	value="'.$row['Remark'].'" style="width:200px;"/></td>');
				echo('<td><input type="text" name="Meta['.$i.']" 	value="'.$row['Meta'].'" style="width:75;"/></td>');
				echo('<td>'.DumpSelect($row["Dump"],"Dump[$i]").'</td>');
				echo('<td><input type="text" name="Active['.$i.']" 	value="'.$row['Active'].'" style="width:50;"/></td>');
			echo('</tr>');
			$i++;
		}
		
			echo ('<tr style="background:#22ff00;">');
				echo('<td>'.MapperSelect('',"NewRomType").'</td>');
				echo('<td><input type="text" name="NewSHA1" 	value="'.$row['SHA1'].'" style="width:350px;" placeholder="SHA1 value" /></td>');
				echo('<td><input type="text" name="NewRemark" 	value="'.$row['Remark'].'" style="width:200px;" placeholder="Remark"/></td>');
				echo('<td><input type="text" name="NewMeta" 	value="'.$row['Meta'].'" style="width:75;" placeholder="Meta like [RC-XXX]"/></td>');
				echo('<td>'.DumpSelect($row["Dump"],"NewDump").'</td>');
				echo('<td></td>');
			echo ('</tr>');
			
		echo('</table>');
	echo('</div>');
	echo('<br/>');
	
	echo('<div id="container" style="text-align:center;width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before updating" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}
	if(!empty($_SESSION['auth'])) {echo('<input type="submit" value="Update" style="width:800px;"/>');}
	echo('</div>');
	echo('</div>');
	echo('</form>');
?>
</body>
</html>
<?php
/*
create table tempstuff (
 GameID bigint	
,SHA1 varchar(255)
,Mapper varchar(255)
,Comment	varchar(255)
,Meta varchar(255)
);

delete FROM tempstuff where SHA1 in (SELECT SHA1 FROM "msxdb_romdetails")
delete FROM tempstuff where SHA1 in (SELECT SHA1 FROM "msxdb_romdetails")
insert into msxdb_romdetails(HashID,GameID,RomType,SHA1,Remark,Meta,Dump,Active) select distinct 3278+ROWID,GameID,Mapper,SHA1,Comment,Meta,'unknown',1 from tempstuff

insert into tempstuff select 14,'937464EB371C68ADD2236BCEF91D24A8CE7C4ED1','','RC-752'
*/
?>