<?php

$hostname="localhost";
$user="root";
$pass="";
$db="kp";
//$server = "127.0.0.1";
$konek=mysql_connect($hostname,$user,$pass);
$database=mysql_select_db($db);
if($konek)
{
	echo "";

}
else
{
	echo "gagal";
}

?>