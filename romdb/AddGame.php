<?php 
include('settings.php');

if (empty($_SESSION['auth'])) {header('Location: login.php');exit();} 

CreateHeader();

	echo('<form action="InsertGame.php" method="post">');
	echo('<div id="container" style="width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before adding" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}

		echo('<h3>Add New Game Title</h3>');
		echo('<table>');
		echo('<tr><td>GameName:	</td><td><input type="text" name="GameName" style="width:350;"/></td></tr>');
		echo('<tr><td>Year:		</td><td><input type="text" name="Year" style="width:75px;"/></td></tr>');
		echo('<tr><td>Company 1:</td><td>'.CompanySelect("","CompanyID1").'</td></tr>');
		echo('<tr><td>Company 2:</td><td>'.CompanySelect("","CompanyID2").'</td></tr>');
		echo('<tr><td>Platform :</td><td>'.PlatformSelect("","Platform").'</td></tr>');
		echo('<tr><td>GenMSXID :</td><td><input type="text" name="GenMSXID" value="0" style="width:75px;"/></td></tr>');

	echo('</table>');
	echo('</div><br/>');
	
	echo('<div id="container" style="text-align:center;width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before adding" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}
	if(!empty($_SESSION['auth'])) {echo('<input type="submit" value="Add Game" style="width:800px;"/>');}
	echo('</div>');
	?>
</body>
</html>