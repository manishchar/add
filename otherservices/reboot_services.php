<?php


$con = mysqli_connect("localhost","firstadd_digital","admin@123","firstadd_digital");

// Check connection
if (mysqli_connect_errno())
  {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  /*

echo $since_start->days.' days total<br>';
echo $since_start->y.' years<br>';
echo $since_start->m.' months<br>';
echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';
*/


  $selectRecords = "SELECT * FROM otherservices WHERE STATUS=1";

  $result = mysqli_query($con, $selectRecords);
$operationStatus ='';
if (mysqli_num_rows($result) > 0) {
	//echo "data exist";
	while($row = $result->fetch_assoc()) {
	    $myreboot=$row['updateddatetime'];
	    $timestamp = date('Y-m-d G:i:s');
	    $start_date = new DateTime($timestamp);
	    $since_start = $start_date->diff(new DateTime($myreboot));
	    echo $since_start->i.' minits<br>';
	    if($since_start->i>5)
	    {
	         //echo $since_start->i.' hours<br>';
	        $updatesql="UPDATE otherservices SET STATUS=0 WHERE id=".$row['id'];
	        $result2 = mysqli_query($con, $updatesql);
	    }
	    //echo $myreboot;
	}
	
	
	
}else{
	
}


//$conn->close();
  
?>