<?php
$vid=$_REQUEST['vid'];




$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");

// Check connection
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $updaterecord = "UPDATE schedules SET STATUS=4 WHERE id=".$vid;

  $result = mysqli_query($con, $updaterecord);
  ?>
  