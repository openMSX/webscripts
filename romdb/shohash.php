<?php

$gameid=urlencode(trim($_REQUEST['gameid']));
$SQL='SELECT * FROM getgameinfo where GameID='.$gameid;
echo($SQL);

$DBlocation = str_replace('htdocs','database',$_SERVER['DOCUMENT_ROOT'].'RomDB.db');


$db = new SQLite3($DBlocation);	
	$stmt = $db->prepare($SQL);
	$result = $stmt->execute();	

	$row = $result->fetchArray();


 
?>


<!DOCTYPE html>
<html>
<head>
<style>
button.accordion {
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover {
    background-color: #ddd;
}

div.panel {
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
</style>
</head>
<body>

<div class="popup">
<form action="updatename.php?id='.$gameid.'" method="post">
	<input name="gameid" value="'.$gameid.'" style="visibility:hidden;width:100px;" />
	<br/>Rom Info
	<table class="display">
			<tr><td>GameName</td><td><input name="GameName" value="<?php echo($row['GameName']); ?>"/></td></tr>
			<tr><td>Year</td><td><input name="Year"  value="<?php echo($row['Year']); ?>"/></td></tr>
			<tr><td>Company 1</td><td>'.CompanySelect($Companyid1,"Companyid1").'</td></tr>
			<tr><td>Company 2</td><td>'.CompanySelect($Companyid2,"Companyid2").'</td></tr>
			<tr><td>Country</td><td><img src="graphics\\<?php echo($row['Country']); ?>.png" alt="<?php echo($row['Country']); ?>" class="flag" /></td></tr>
			<tr><td>Platform</td><td><select name="Platform">
			<option value="MSX1" <?php if ($row['Platform']=='MSX1') {echo('selected');} ?>>MSX1</option>
			<option value="MSX2" <?php if ($row['Platform']=='MSX2') {echo('selected');} ?>>MSX2</option>
			<option value="ARAB" <?php if ($row['Platform']=='ARAB') {echo('selected');} ?>>ARAB</option>
		
			</select></td></tr>
			<tr><td>GenMSXID</td><td><input name="GenMSXId"  value="<?php echo($row['GenMSXId']); ?>"/></td></tr>
			<tr><td/><td><input type="submit" Value="Save" style="background-color:#00ff88;"/></td></tr>
	</table>
	</form>
	
	<br/>Add Hash to ROM
	<table class="display">
	<tr>
		<td>Dump</td>
		<td>Mapper</td>
		<td>SHA1</td>
		<td>Remark</td>
		<td>Meta</td>
		<td>Active</td>
		<td>Delete</td>
	</tr>
	
	<form action="addhash.php?id='.$gameid.'" method="post">
	<input name="gameid" value="'.$gameid.'" style="visibility:hidden;width:100px;" />
	<tr style="background-color:#ffffdd;">
		<td>'.DumpSelect($row["Dump"],"Dump").'</td>
		<td>'.MapperSelect($row["RomType"],"RomType").'</td>
		<td><input name="SHA1"/></td>
		<td><input name="Remark" style="width:100px;"/></td>
		<td><input name="Meta" style="width:100px;"/></td>
		<td><input type="submit" Value="Add" style="width:40px;background-color:#00ff88;"/></td>
	</tr>	
	</form>
	</table>


	<button class="accordion">Hash Information</button>
	<div class="panel">
	  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
	</div>
</div>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}
</script>

</body>
</html>*/