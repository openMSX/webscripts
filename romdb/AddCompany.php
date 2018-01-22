<?php 
include('settings.php');

if (empty($_SESSION['auth'])) {header('Location: login.php');exit();} 

CreateHeader();

	echo('<form action="InsertCompany.php" method="post">');
	echo('<div id="container" style="width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before adding" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}

		echo('<h3>Add New Game Developer</h3>');
		echo('<table>');
		echo('<tr><td>Short Name:	</td><td><input type="text" name="shortname" style="width:350;"/></td></tr>');
		echo('<tr><td>Website:		</td><td><input type="text" name="website" style="width:350;"/></td></tr>');
		echo('<tr><td>Amateur		</td><td><select name="amateur" style="width:50;"><option value="1">Yes</option><option value="0">No</option></select></td></tr>');
		echo('<tr><td>Country:		</td><td><input type="text" name="country" style="width:350;"/></td></tr>');
		echo('<tr><td>Full Name:	</td><td><input type="text" name="fullname" style="width:350;"/></td></tr>');

	echo('</table>');
	echo('</div><br/>');
	
	echo('<div id="container" style="text-align:center;width:925px;">');
	if(empty($_SESSION['auth'])) {echo('<input type="submit" value="Log in before adding" style="width:100%;background-color:#ff7733;color:#ffffff;"/>');}
	if(!empty($_SESSION['auth'])) {echo('<input type="submit" value="Add Developer" style="width:800px;"/>');}
	echo('</div>');
	?>	
</body>
</html>
