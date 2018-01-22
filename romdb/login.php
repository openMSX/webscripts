<?php 
include('settings.php');

if(!empty($_REQUEST['pass'])) {$pass=$_REQUEST['pass'];} else {$pass='';}
if (!empty($_SESSION['auth'])) {header('Location: index.php');exit();} 
if(sha1($pass)=="00000000000000000000000000000"){$_SESSION['auth']='True';header('Location: index.php');exit();}

CreateHeader();

?>
<form action="login.php" method="post"><input name="pass" style="width:150px;"/>