<?php
require_once("dbcontroller.php");
$db_handle = new DBController();

if(!empty($_POST["name"])) {
	$name = mysql_real_escape_string(strip_tags($_POST["name"]));
	$address = mysql_real_escape_string(strip_tags($_POST["address"]));
	$lat = mysql_real_escape_string(strip_tags($_POST["lat"]));
	$lng = mysql_real_escape_string(strip_tags($_POST["lng"]));
	$status = mysql_real_escape_string(strip_tags($_POST["status"]));
  $sql = "INSERT INTO markers (name,address,lat,lng,status) VALUES ('" . $name . "','" . $address . "','" . $lat . "','" . $lng . "','" . $status . "')";
  $faq_id = $db_handle->executeInsert($sql);
	if(!empty($faq_id)) {
		$sql = "SELECT * from markers WHERE id = '$faq_id' ";
		$posts = $db_handle->runSelectQuery($sql);
	}
?>
<tr class="table-row" id="table-row-<?php echo $posts[0]["id"]; ?>">
<td contenteditable="true" onBlur="saveToDatabase(this,'name','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["name"]; ?></td>
<td contenteditable="true" onBlur="saveToDatabase(this,'address','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["address"]; ?></td>
<td contenteditable="true" onBlur="saveToDatabase(this,'lat','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["lat"]; ?></td>
<td contenteditable="true" onBlur="saveToDatabase(this,'lng','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["lng"]; ?></td>
<td contenteditable="true" onBlur="saveToDatabase(this,'status','<?php echo $posts[0]["id"]; ?>')" onClick="editRow(this);"><?php echo $posts[0]["status"]; ?></td>
<td><a class="ajax-action-links" onclick="deleteRecord(<?php echo $posts[0]["id"]; ?>);">Delete</a></td>
</tr>  
<?php } ?>