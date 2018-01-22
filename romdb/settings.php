<?php
session_start();

set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DBlocation = str_replace('htdocs','database',$_SERVER['DOCUMENT_ROOT'].'/RomDB.db');
$db = new SQLite3($DBlocation);

function SearchMenu() {
	echo('<div id="container" style="text-align:center;width:925px;">');
	echo('<table style="table{font-family:verdana;font-size:10px;background-color:#ffffee;border-style:solid;border-color:#cccccc;border-width:1px;">');
	
	for ($i=0;$i<26;$i++){
		echo('<td>&nbsp;</td><td><a href="index.php?gamename='.chr(65+$i).'">'.chr(65+$i).'</a><td>&nbsp;</td>');
		if ($i==17){echo('</tr><tr>');}
	}
	
	for ($i=0;$i<10;$i++){
		echo('<td>&nbsp;</td><td><a href="index.php?gamename='.$i.'">'.$i.'</a><td>&nbsp;</td>');
	}
	echo('</tr>');
	
	echo('<tr><td colspan="90"><hr/></td></tr><tr><td colspan="90">');
	echo('<form name="input" action="index.php" method="post">');
	echo('Name: <input style="width:200px;" type="text" name="gamename"> ');
	echo('Company: <input style="width:200px;" type="text" name="company"> ');
	echo('<input style="width:100px;" type="submit" value="Search">');
	echo('</form></td></tr></table></div><br/>');
}

function MakeSQLSafe($string) {
	$string=trim($string);
	return str_replace("'", "''", $string);
}

function PlatformSelect($Companyid,$name) {
	sleep(0.1);
	global $db;
	$stmt = $db->prepare('SELECT distinct Platform,upper(Platform) FROM "msxdb_rominfo" where "Platform" !="None" order by 2 desc');
	$compSel='<select name="'.$name.'">';
	$result = $stmt->execute();	
	while ($row = $result->fetchArray()) {
		$compSel.='<option value='.$row["Platform"];
		if ($row["Platform"]==$Companyid) {$compSel.=' selected ';}
		$compSel.='>'.$row["Platform"].'</option>';
	}
	$compSel.="</select>";
	return $compSel;
}

function CompanySelect($Companyid,$name) {
	sleep(0.1);
	global $db;
	$stmt = $db->prepare('SELECT distinct company_id,shortname,upper(shortname) FROM msxdb_company order by 3');
	$compSel='<select name="'.$name.'"><option value=0>--</option>';
	$result = $stmt->execute();	
	while ($row = $result->fetchArray()) {
		$compSel.='<option value='.$row["company_id"];
		if ($row["company_id"]==$Companyid) {$compSel.=' selected ';}
		$compSel.='>'.$row["shortname"].'</option>';
	}
	$compSel.="</select>";
	return $compSel;
}

function MapperSelect($mappertype,$name) {
	sleep(0.1);
	global $db;
	$stmt = $db->prepare("SELECT distinct RomType,upper(RomType) FROM msxdb_romdetails where length(RomType)>1 and RomType not like '%not used%' order by 2");
	$compSel='<select name="'.$name.'" class="mapper">';
	$result = $stmt->execute();	
	while ($row = $result->fetchArray()) {
		$compSel.='<option value="'.$row["RomType"].'"';
		if ($row["RomType"]==$mappertype) {$compSel.=' selected ';}
		$compSel.='>'.$row["RomType"].'</option>';
	}
	$compSel.="</select>";
	return $compSel;
}


function DumpSelect($dumptype,$name) {
	sleep(0.1);
	global $db;
	$stmt = $db->prepare("SELECT distinct Dump,upper(Dump) FROM msxdb_romdetails where dump !=0 and dump!='nope' order by 2");
	$compSel='<select style="width:80px;" name="'.$name.'" class="mapper"><option value=0>--</option>';
	$result = $stmt->execute();	
	while ($row = $result->fetchArray()) {
		$compSel.='<option value="'.$row["Dump"].'"';
		if ($row["Dump"]==$dumptype) {$compSel.=' selected ';}
		$compSel.='>'.$row["Dump"].'</option>';
	}
	$compSel.="</select>";
	
	return $compSel;
}
	
function XMLClean($strin) {
	return htmlspecialchars(trim($strin), ENT_COMPAT,'ISO-8859-1', true);
}
	
function CreateHeader(){?>
	<html>
	<head>
		<title>openMSX RomDB</title>
		<style>
			 body{font-family:arial;color:#000000;font-size:12px;text-shadow: 1px 1px #dbdbdb;}
			 td{font-family:arial;color:#000000;font-size:12px;text-shadow: 1px 1px #dbdbdb;}
			.line0 { background-color: ffffdd;font-size:12px;}
			.line0:hover { background-color: 0000ff;color:#ffffff;font-size:12px;}
			.line1 { background-color: f8f8f8;font-size:12px;}
			.line1:hover { background-color: 0000ff;color:#ffffff;font-size:12px;}
			
			h1 {color:#ff8800;text-shadow: 1px 1px #dbdbdb;font-family:arial;font-size:35px;padding:0px;margin:0px;}
			h2 {color:#ff8800;text-shadow: 1px 1px #dbdbdb;font-family:arial;font-size:30px;padding:0px;margin:0px;}
			h3 {color:#ff8800;text-shadow: 1px 1px #dbdbdb;font-family:arial;font-size:20px;padding:0px;margin:0px;}


			.overlay-box {
				background-color: #eeeeee;
				border: 1px solid #000;
				text-align:left;
				height: 80%;
				width: 950px;
				margin: auto;
				position: fixed;
				padding:10px;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				overflow:auto;
				visibility:hidden;
			}
			
			#container {
				background-color:#f8f8f8;
				border: 1px solid #525564;
				border-radius: 3px;
				padding: 10px;
				box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); 
				text-shadow: 1px 1px #dbdbdb;
			}
			
			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				background-color: #333;
			}

			li {
				float: left;
			}

			li a {
				display: block;
				color: white;
				text-align: center;
				padding: 14px 16px;
				text-decoration: none;
			}

			li a:hover {
				background-color: #111;
			}

		</style>
		<script>
			function edit(id) {alert("edit " +id);}
			function del(id) {alert("delete "+id);}
			function cheat(id) {alert("cheat  "+id);}		
				
			function ShowBox(){
				var d = document.getElementById("overlay-box")
				d.style.visibility = "visible";
			}
			
			function HideBox(){
				var d = document.getElementById("overlay-box");
				d.style.visibility = "hidden";
			}
			
			window.addEventListener("keydown",function(event) {key =event.which;if (key==27){HideBox('')}},false);
			
			function edit(id){
				location.href = 'edit.php?id='+id;
			}	
						
			function LoadHTML(page,divname) {
			   var con = document.getElementById(divname)
			   ,   xhr = new XMLHttpRequest();

			   xhr.onreadystatechange = function (e) { 
				if (xhr.readyState == 4 && xhr.status == 200) {
				 con.innerHTML = xhr.responseText;
				}
			   }

			 xhr.open("GET", page, true);
			 xhr.setRequestHeader('Content-type', 'text/html');
			 xhr.send();
			}
		</script>
</head>
<body>
<div style="width:950px;">
	<ul>
	  <li><a class="active" href="index.php">Home</a></li>
	  <?php
	  if(empty($_SESSION['auth'])) {echo('<li><a href="login.php">Admin</a></li>');}
	  if(!empty($_SESSION['auth'])) {echo('<li><a href="AddGame.php">Add Game</a></li>
	  <li><a href="AddCompany.php">Add Company</a></li>
	  <li><a href="make.php">Create XML File</a></li>');}
	  ?>
	  <li><a href="archive.php">Archive</a></li>
	</ul>
</div>
<br/>
<div class="overlay-box" id="overlay-box"><div id="content"></div></div>
<?php } ?>