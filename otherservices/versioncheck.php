<?php
$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");
$myversion=1;
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $selectRecords = "SELECT * FROM app_versions WHERE status=1";

  $result = mysqli_query($con, $selectRecords);
  if (mysqli_num_rows($result) > 0) {
	
	$row=mysqli_fetch_array($result);
	$myversion=$row['version'];
	}
	
	echo $myversion;
?>