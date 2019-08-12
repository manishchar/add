<?php
$pid=$_REQUEST['pid'];
$status=$_REQUEST['status'];

 //$pid='PI-0001';
//$status='0';
$myreboot=1;

$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");

// Check connection
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $selectRecords = "SELECT * FROM otherservices WHERE PIID='".$pid."'";

  $result = mysqli_query($con, $selectRecords);
$operationStatus ='';
if (mysqli_num_rows($result) > 0) {
	//echo "data exist";
	$row=mysqli_fetch_array($result);
	$myreboot=$row['reboot'];
	
	if($myreboot=='1'){
			
		}else{
		    $sql1 = "UPDATE otherservices SET reboot=1 WHERE PIID='".$pid."'";
		    $con->query($sql1);
		}
	
	
	
	$sql = "UPDATE otherservices SET Status='".$status."' WHERE PIID= '".$pid."'";
	$operationStatus ='2';
}else{
	//echo "No records";
	$sql = "INSERT INTO otherservices (PIID, Status)
		VALUES ('".$pid."', '".$status."')";
		$operationStatus ='1';
}

$sql2="INSERT INTO timelogs (PIID, status) VALUES ('".$pid."', '".$status."')";
//$con->query($sql2);

if ($con->query($sql) === TRUE) {
	if($operationStatus =='1'){
echo $myreboot;
	}else{
echo $myreboot;
	}
    
} else {
    echo $myreboot;
}

//$conn->close();
  
?>