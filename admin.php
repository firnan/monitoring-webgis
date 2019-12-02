<?php
require_once("dbcontroller.php");
$db_handle = new DBController();

$sql = "SELECT * from markers";
$row_product = $db_handle->runSelectQuery($sql);
?>
<?php
$page = $_SERVER['PHP_SELF'];
$sec = "60";
header("Refresh: $sec; url=$page");
 //echo "Watch the page reload itself in 10 second!";
date_default_timezone_set('Etc/GMT-7');
?>
<!DOCTYPE html >
  <head>
    <title>.:Network Monitoring:.</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      .time {
        position: absolute;
        top: 550px;
        left: 87%;
        z-index: 5;
        opacity: 0.8;
        filter: alpha(opacity=80);
        /*text-align: center;*/
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
        font-size: 15px;
      }
      .trademark {
        position: absolute;
        top: 595px;
        left: 5%;
        z-index: 5;
        opacity: 0.8;
        filter: alpha(opacity=80);
        /*text-align: center;*/
        font-family: 'Roboto','sans-serif';
        font-weight: bold;
        color: navy;
        line-height: 30px;
        padding-left: 10px;
        font-size: 15px;
      }
      .tab {
        margin: 5px auto;
        width: 100px;
      }
      .logout {
        position: absolute;
        top: 20px;
        left: 94%;
        z-index: 20;

        color:  #000080;
        opacity: 0.7;
        filter: alpha(opacity=70);
        /*text-align: center;*/
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      .floating-panel {
        position: absolute;
        overflow: auto;
        height: 250px;
        top: 50px;
        left: 1%;
        z-index: 5;
        background-color: #f5f5f5;
        padding: 5px;
        border: 1px solid #f5f5f5;
        color:  #000080;
        opacity: 0.8;
        filter: alpha(opacity=80);
        /*text-align: center;*/
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      table {
	margin-top:0px;
}

.tbl-qa {
	width: 98%;
	font-size:0.9em;
	background-color: #f5f5f5;
}

.tbl-qa th.table-header {
	padding: 5px;
	text-align: left;
	padding:10px;
	text-align:center;
}

.table-header {
	color:blue;
	font-size:18px;
	font-family:cursive;
	text-align:center;
}

.tbl-qa .table-row td {
	padding:5px;
	background-color: #FDFDFD;
	font-size:15px;
	font-family:cursive;
	text-align:center;
	font-weight: bold;
}

.ajax-action-links {
	color: #09F; 
	margin: 10px 0px;
	cursor:pointer;
}

.add-data-style {
	border:blue 1px solid;
	color: blue; 
	margin: 0px 190px;
	cursor:pointer;
	display: inline-block;
	padding: 8px 12px;
	background:azure;
	border-radius:8px;
}
    </style>
  </head>
  <body>
    <div class="logout"><button type="button"><a href="logout.php"><b>Logout</b></a></button></div>
<script src="jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function createNew() {
	$("#add-more").hide();
	var data = '<tr class="table-row" id="new_row_ajax">' +
	'<td contenteditable="true" id="txt_name" onBlur="addToHiddenField(this,\'name\')" onClick="editRow(this);"></td>' +
	'<td contenteditable="true" id="txt_address" onBlur="addToHiddenField(this,\'address\')" onClick="editRow(this);"></td>' +
	'<td contenteditable="true" id="txt_lat" onBlur="addToHiddenField(this,\'lat\')" onClick="editRow(this);"></td>' +
	'<td contenteditable="true" id="txt_lng" onBlur="addToHiddenField(this,\'lng\')" onClick="editRow(this);"></td>' +
	'<td contenteditable="true" id="txt_status" onBlur="addToHiddenField(this,\'status\')" onClick="editRow(this);"></td>' +
	'<td><input type="hidden" id="name" /><input type="hidden" id="address" /><input type="hidden" id="lat" /><input type="hidden" id="lng" /><input type="hidden" id="status" /><span id="confirmAdd"><a onClick="addToDatabase()" class="ajax-action-links">Save</a> / <a onclick="cancelAdd();" class="ajax-action-links">Cancel</a></span></td>' +	
	'</tr>';
  $("#table-body").append(data);
}

function cancelAdd() {
	$("#add-more").show();
	$("#new_row_ajax").remove();
}

function editRow(editableObj) {
  $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
  $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
  $.ajax({
    url: "edit.php",
    type: "POST",
    data:'column='+column+'&editval='+$(editableObj).text()+'&id='+id,
    success: function(data){
      $(editableObj).css("background","#FDFDFD");
    }
  });
}

function addToDatabase() {
  var name = $("#name").val();
  var address = $("#address").val();
  var lat = $("#lat").val();
  var lng = $("#lng").val();
  var status = $("#status").val();
  
	  $("#confirmAdd").html('<img src="loaderIcon.gif" />');
	  $.ajax({
		url: "add.php",
		type: "POST",
		data:'name='+name+'&address='+address+'&lat='+lat+'&lng='+lng+'&status='+status,
		success: function(data){
		  $("#new_row_ajax").remove();
		  $("#add-more").show();		  
		  $("#table-body").append(data);
		}
	  });
}

function addToHiddenField(addColumn,hiddenField) {
	var columnValue = $(addColumn).text();
	$("#"+hiddenField).val(columnValue);
}

function deleteRecord(id) {
	if(confirm("Are you sure you want to delete this row?")) {
		$.ajax({
			url: "delete.php",
			type: "POST",
			data:'id='+id,
			success: function(data){
			  $("#table-row-"+id).remove();
			}
		});
	}
}
</script>

    <div class="time">
    <script type="text/javascript">
    var detik = <?php echo date('s'); ?>;
    var menit = <?php echo date('i'); ?>;
    var jam   = <?php echo date('H'); ?>;
     
    function clock()
    {
        if (detik!=0 && detik%60==0) {
            menit++;
            detik=0;
        }
        if (detik==0) {
          $page = $_SERVER['PHP_SELF'];
$sec = "60";
header("Refresh: $sec; url=$page");
        }
        second = detik;
         
        if (menit!=0 && menit%60==0) {
            jam++;
            menit=0;
        }
        minute = menit;
         
        if (jam!=0 && jam%24==0) {
            jam=0;
        }
        hour = jam;
         
        if (detik<10){
            second='0'+detik;
        }
        if (menit<10){
            minute='0'+menit;
        }
         
        if (jam<10){
            hour='0'+jam;
        }
        waktu = hour+':'+minute+':'+second;
         
        document.getElementById("clock").innerHTML = waktu;
        detik++;
    }
 
    setInterval(clock,1000);
</script>
 
<div style="text-align:center;">
    <h1 id="clock"></h1>
</div></div>
    <div class="trademark">
      Firnan Sholihuda/UPNYK-IF/Kerja Praktek/BPPTKG/16Januari-16Februari 2017
    </div>
    <div class="floating-panel">
    <table class="tbl-qa">
  <thead>
	<tr>
	  <th class="table-header">Hostname</th>
	  <th class="table-header">IP Address</th>
	  <th class="table-header">Latitude</th>
	  <th class="table-header">Longitude</th>
	  <th class="table-header">Status</th>
	  <th class="table-header">Action</th>
	</tr>
  </thead>
  <tbody id="table-body">
	<?php
  require("conect.php");
  $db = "SELECT * FROM markers";
  $db = mysql_query($db);
	if(!empty($row_product)) { 
	foreach($row_product as $k=>$v) {
	  ?>
	  <tr class="table-row" id="table-row-<?php echo $row_product[$k]["id"]; ?>">
		<td contenteditable="true" onBlur="saveToDatabase(this,'name','<?php echo $row_product[$k]["id"]; ?>')" onClick="editRow(this);">
			<?php echo $row_product[$k]["name"]; ?>
		</td>
		<td contenteditable="true" onBlur="saveToDatabase(this,'address','<?php echo $row_product[$k]["id"]; ?>')" onClick="editRow(this);">
			<?php echo $row_product[$k]["address"]; ?>
		</td>
		<td contenteditable="true" onBlur="saveToDatabase(this,'lat','<?php echo $row_product[$k]["id"]; ?>')" onClick="editRow(this);">
			<?php echo $row_product[$k]["lat"]; ?>
		</td>
		<td contenteditable="true" onBlur="saveToDatabase(this,'lng','<?php echo $row_product[$k]["id"]; ?>')" onClick="editRow(this);">
			<?php echo $row_product[$k]["lng"]; ?>
		</td>
		<td contenteditable="false" onBlur="saveToDatabase(this,'status','<?php echo $row_product[$k]["id"]; ?>')" onClick="editRow(this);">
			<?php  
        $ip = $row_product[$k]["address"];
                    // Run the ping to the IP
                    exec ("ping -n 1 $ip", $outcome, $ping_output);

                    if(0 == $ping_output) {
                        echo "<font color='green'><strong>Online!</strong></font>";
                        //print_r($outcome);
                    }
                    else {
                        echo "<font color='red'><strong>Offline!</strong></font>";
                        //print_r($outcome);
                    }
         ?>
		</td>
		<td>
			<a class="ajax-action-links" onclick="deleteRecord(<?php echo $row_product[$k]["id"]; ?>);">
				Delete
			</a>
		</td>
	  </tr>
	  <?php
	}
	}
	?>
  </tbody>
</table>
<div class="add-data-style" id="add-more" onClick="createNew();"><center>Add More</center></div>
    </div>
    <div id="map"></div>
    <script>

      var customLabel = {
      };
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng( -7.798142, 110.384464),
          zoom: 15
        });
        var infoWindow = new google.maps.InfoWindow ({maxWidth:150});

          // Change this depending on the name of your PHP or XML file
          downloadUrl('xmlecho.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label

              });
                marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
                //title: 'test';

              });
               google.maps.event.addListener(map,'click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.close();
                //title: 'test';
              });
            });
          });

        }
      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;
        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }
      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUmtA3LFlKt8LOh3liCiYS6aGMh1rBZZ8&callback=initMap">
    </script>
  </body>
</html>