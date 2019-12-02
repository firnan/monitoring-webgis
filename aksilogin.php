<?php
include 'conect.php';
$username = $_POST['username'];
$password = $_POST['password'];
$query = mysql_query("select * from admin where username='$username'");
$data=mysql_fetch_array($query);
if ($username==$data['username'] && $password==$data['password']) {
	
	session_start();
 	$_SESSION['username']=$username;
 	$_SESSION['password']=$password;
	header('location:admin.php');
}
else {
	header('location:index.php');
}
?>